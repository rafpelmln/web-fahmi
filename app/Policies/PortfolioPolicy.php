<?php

namespace App\Policies;

use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PortfolioPolicy
{
    /**
     * Determine whether the user can view any models.
     * 
     * Guru dan Admin boleh melihat semua portfolio
     * Siswa hanya boleh melihat portfolio sendiri (dihandle di controller)
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role ?? 'student', ['teacher', 'admin']);
    }

    /**
     * Determine whether the user can view the model.
     * 
     * Siswa hanya boleh lihat milik sendiri
     * Guru dan Admin boleh lihat semua
     */
    public function view(User $user, Portfolio $portfolio): bool
    {
        if ($user->role === 'admin') {
            return true;
        }
        
        if ($user->role === 'teacher') {
            return true;
        }
        
        // Siswa hanya bisa lihat milik sendiri
        return $user->id === $portfolio->student_id;
    }

    /**
     * Determine whether the user can create models.
     * 
     * Hanya siswa dan admin yang boleh membuat portfolio
     */
    public function create(User $user): bool
    {
        return in_array($user->role ?? 'student', ['student', 'admin']);
    }

    /**
     * Determine whether the user can update the model.
     * 
     * Siswa hanya boleh edit milik sendiri
     * Admin boleh edit semua
     */
    public function update(User $user, Portfolio $portfolio): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        // Siswa hanya bisa edit milik sendiri, dan hanya jika status pending
        return $user->id === $portfolio->student_id && $portfolio->verified_status === 'pending';
    }

    /**
     * Determine whether the user can delete the model.
     * 
     * Siswa hanya boleh hapus milik sendiri
     * Admin boleh hapus semua
     */
    public function delete(User $user, Portfolio $portfolio): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        // Siswa hanya bisa hapus milik sendiri, dan hanya jika status pending
        return $user->id === $portfolio->student_id && $portfolio->verified_status === 'pending';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Portfolio $portfolio): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Portfolio $portfolio): bool
    {
        return false;
    }

    /**
     * Determine whether the user can verify the model.
     * 
     * Hanya guru dan admin yang boleh verifikasi
     */
    public function verify(User $user, Portfolio $portfolio): bool
    {
        return in_array($user->role ?? 'student', ['teacher', 'admin']);
    }
}
