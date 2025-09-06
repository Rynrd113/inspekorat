<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\HasAuditLog;
use App\Traits\EagerLoadingOptimized;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasAuditLog, EagerLoadingOptimized;

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
        'status',
    ];

    /**
     * Contextual eager loading untuk berbagai use case
     */
    protected $contextualEagerLoad = [
        'api' => [
            // Minimal data untuk API
        ],
        'web' => [
            'createdContent:id,judul,created_at,created_by',
        ],
        'admin' => [
            'createdContent:id,judul,created_at,created_by',
            'updatedContent:id,judul,updated_at,updated_by',
            'wbsReports:id,subjek,status,created_at,assigned_to',
            'auditLogs:id,event,auditable_type,created_at,user_id'
        ]
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
            'status' => 'boolean',
        ];
    }
    
    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, [
            'admin', 'super_admin', 'content_admin'
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
     * Get role hierarchy level
     */
    public function getRoleLevel(): int
    {
        return match($this->role) {
            'super_admin' => 100,
            'admin' => 90,
            'content_admin' => 70,
            'user' => 10,
            default => 0
        };
    }
    
    /**
     * Check if user can manage other users
     */
    public function canManageUsers(): bool
    {
        return $this->role === 'super_admin';
    }
    
    /**
     * Check if user can approve content
     */
    public function canApproveContent(): bool
    {
        return $this->hasAnyRole(['super_admin', 'admin', 'content_manager']);
    }
    
    /**
     * Get modules accessible by user
     */
    public function getAccessibleModules(): array
    {
        return match($this->role) {
            'super_admin' => ['all'],
            'admin' => ['beranda', 'profil', 'unit_kerja', 'pelayanan', 'dokumen', 'berita', 'galeri', 'kontak', 'statistik', 'wbs', 'pengaduan', 'portal_opd', 'faq'],
            'content_admin' => ['beranda', 'berita', 'galeri', 'faq', 'dokumen'],
            default => ['beranda']
        };
    }
    
    /**
     * Get available roles
     */
    public static function getRoles(): array
    {
        return [
            'user' => 'User',
            'content_admin' => 'Content Admin',
            'admin' => 'Admin',
            'super_admin' => 'Super Admin'
        ];
    }
    
    /**
     * Get role description
     */
    public function getRoleDescription(): string
    {
        return match($this->role) {
            'super_admin' => 'Akses penuh ke semua modul termasuk manajemen user',
            'admin' => 'Akses ke semua modul operasional dan management',
            'content_admin' => 'Mengelola konten: berita, galeri, FAQ, dokumen',
            'user' => 'Akses terbatas, hanya view',
            default => 'Role tidak dikenali'
        };
    }
}
