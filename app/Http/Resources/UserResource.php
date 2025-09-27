<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'role_label' => $this->getRoleLabel(),
            'is_admin' => $this->isAdmin(),
            'is_active' => $this->is_active ?? true,
            'last_login_at' => $this->last_login_at?->format('Y-m-d H:i:s'),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Get role label
     */
    private function getRoleLabel(): string
    {
        $roles = [
            'super_admin' => 'Super Admin',
            'admin' => 'Admin',
            'content_admin' => 'Content Admin',
        ];

        return $roles[$this->role] ?? 'Unknown';
    }
}
