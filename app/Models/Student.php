<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    /**
     * Fillable attributes yang boleh di-assign secara massal
     */
    protected $fillable = [
        'nis',
        'name',
        'class',
    ];

    /**
     * Relationship: Student memiliki banyak Portfolio
     */
    public function portfolios(): HasMany
    {
        return $this->hasMany(Portfolio::class);
    }
}
