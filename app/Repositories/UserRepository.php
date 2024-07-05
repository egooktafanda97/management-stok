<?php

namespace App\Repositories;

use App\Constant\Status;
use App\Contract\AttributesFeature\Attributes\Repository;
use App\Models\User;
use App\Services\RolePermissionsService;
use Illuminate\Support\Facades\DB;

#[Repository(model: User::class)]
class UserRepository extends BaseRepository
{

    public function __construct(
        public RolePermissionsService $rolePermissionsService
    ) {
    }

    public function transformer(array $data): self
    {
        $this->data = [
            'nama' => $data['nama'],
            'username' => $data['username'],
            'password' => bcrypt($data['password']),
            'role' => $data['role'],
            'status_id' => $data['status_id'] ?? Status::ACTIVE,
        ];
        return $this;
    }

    public function setRole($user, $role)
    {
        $this->rolePermissionsService->setRole($user, [$role, "api"]);
    }
    public function store(array $data, $role)
    {
        try {
            $user = $this
                ->transformer(array_merge($data, [
                    "status_id" => $data['status_id']  ?? Status::ACTIVE
                ]))
                ->validate()
                ->create();
            $this->setRole($user, $role);
            return $user;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }
}
