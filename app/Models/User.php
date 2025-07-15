<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\HasAuditLog;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasAuditLog;

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
            'admin', 'super_admin', 'content_manager', 'service_manager', 
            'opd_manager', 'wbs_manager', 'admin_wbs', 'admin_berita', 
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
     * Get role hierarchy level
     */
    public function getRoleLevel(): int
    {
        return match($this->role) {
            'super_admin' => 100,
            'admin' => 90,
            'content_manager' => 80,
            'service_manager' => 80,
            'opd_manager' => 80,
            'wbs_manager' => 80,
            'admin_wbs', 'admin_berita', 'admin_portal_opd', 
            'admin_pelayanan', 'admin_dokumen', 'admin_galeri', 'admin_faq' => 70,
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
            'admin' => ['beranda', 'profil', 'unit_kerja', 'pelayanan', 'dokumen', 'berita', 'galeri', 'kontak', 'statistik', 'wbs', 'portal_opd'],
            'content_manager' => ['beranda', 'berita', 'galeri', 'faq'],
            'service_manager' => ['beranda', 'pelayanan', 'dokumen', 'kontak'],
            'opd_manager' => ['beranda', 'portal_opd', 'unit_kerja'],
            'wbs_manager' => ['beranda', 'wbs', 'statistik'],
            'admin_wbs' => ['beranda', 'wbs'],
            'admin_berita' => ['beranda', 'berita'],
            'admin_portal_opd' => ['beranda', 'portal_opd'],
            'admin_pelayanan' => ['beranda', 'pelayanan'],
            'admin_dokumen' => ['beranda', 'dokumen'],
            'admin_galeri' => ['beranda', 'galeri'],
            'admin_faq' => ['beranda', 'faq'],
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
            'admin_wbs' => 'Admin WBS',
            'admin_berita' => 'Admin Berita',
            'admin_portal_opd' => 'Admin Portal OPD',
            'admin_pelayanan' => 'Admin Pelayanan',
            'admin_dokumen' => 'Admin Dokumen',
            'admin_galeri' => 'Admin Galeri',
            'admin_faq' => 'Admin FAQ',
            'content_manager' => 'Content Manager',
            'service_manager' => 'Service Manager',
            'opd_manager' => 'OPD Manager',
            'wbs_manager' => 'WBS Manager',
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
            'admin' => 'Akses ke semua modul operasional',
            'content_manager' => 'Mengelola konten: berita, galeri, FAQ',
            'service_manager' => 'Mengelola layanan: pelayanan, dokumen, kontak',
            'opd_manager' => 'Mengelola data OPD dan unit kerja',
            'wbs_manager' => 'Mengelola WBS dan statistik terkait',
            'admin_wbs' => 'Khusus mengelola WBS',
            'admin_berita' => 'Khusus mengelola berita',
            'admin_portal_opd' => 'Khusus mengelola Portal OPD',
            'admin_pelayanan' => 'Khusus mengelola pelayanan',
            'admin_dokumen' => 'Khusus mengelola dokumen',
            'admin_galeri' => 'Khusus mengelola galeri',
            'admin_faq' => 'Khusus mengelola FAQ',
            'user' => 'Akses terbatas, hanya view',
            default => 'Role tidak dikenali'
        };
    }
}
