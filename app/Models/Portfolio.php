<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Enum;

class Portfolio extends Model
{
    /**
     * Fillable attributes yang boleh di-assign secara massal
     */
    protected $fillable = [
        'student_id',
        'title',
        'description',
        'type',
        'file_path',
        'verified_status',
        'verified_by',
        'verified_at',
    ];

    /**
     * Cast attributes
     */
    protected $casts = [
        'verified_status' => 'string', // enum di database
        'verified_at' => 'datetime',
    ];

    /**
     * Relationship: Portfolio milik satu Student
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Relationship: Portfolio diverifikasi oleh satu User (guru)
     */
    public function verifiedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Accessor: Dapatkan URL lengkap file
     */
    public function getFileUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    /**
     * Accessor: Dapatkan nama file dari path
     */
    public function getFileNameAttribute(): string
    {
        return basename($this->file_path);
    }

    /**
     * Check apakah portfolio sudah diverifikasi
     */
    public function isVerified(): bool
    {
        return $this->verified_status === 'approved';
    }

    /**
     * Check apakah portfolio dalam status pending
     */
    public function isPending(): bool
    {
        return $this->verified_status === 'pending';
    }

    /**
     * Check apakah portfolio ditolak
     */
    public function isRejected(): bool
    {
        return $this->verified_status === 'rejected';
    }
}
