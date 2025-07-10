<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, [
            'admin', 'super_admin', 'admin_wbs', 'admin_berita', 
            'admin_portal_opd', 'admin_pelayanan', 'admin_dokumen', 
            'admin_galeri', 'admin_faq'
        ]);
    }
    
    /**
     * Check if user is superadmin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }
    
    /**
     * Check if user has specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }
    
    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }
    
    /**
     * Get available roles
     */
    public static function getRoles(): array
    {
        return [
            'user' => 'User',
            'admin_wbs' => 'Admin WBS',
            'admin_berita' => 'Admin Berita',
            'admin_portal_opd' => 'Admin Portal OPD',
            'admin_pelayanan' => 'Admin Pelayanan',
            'admin_dokumen' => 'Admin Dokumen',
            'admin_galeri' => 'Admin Galeri',
            'admin_faq' => 'Admin FAQ',
            'admin' => 'Admin',
            'super_admin' => 'Super Admin'
        ];
    }
}
