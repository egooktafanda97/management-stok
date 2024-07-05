<?php

namespace App\Dtos;

use App\Contract\AttributesFeature\Attributes\Getter;
use App\Contract\AttributesFeature\Attributes\Setter;

class UsersDTOs extends BaseDTOs
{
    public function __construct(

        #[Setter] #[Getter]
        public ?int $id = null,

        #[Setter] #[Getter]
        public ?string $nama = null,

        #[Setter] #[Getter]
        public ?string $username = null,

        #[Setter] #[Getter]
        public ?string $password = null,

        #[Setter] #[Getter]
        public ?string $role = null,

        #[Setter] #[Getter]
        public ?int $status_id = null
    ) {
        parent::__construct();
    }

    public static function fromArray(array $data): UsersDTOs
    {
        return new UsersDTOs(
            id: $data["id"] ?? 0,
            nama: $data["nama"] ?? null,
            username: $data["username"] ?? null,
            password: $data["password"] ?? null,
            role: $data["role"] ?? null,
            status_id: $data["status_id"] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            "id" => $this->id,
            "nama" => $this->nama,
            "username" => $this->username,
            "role" => $this->role,
            "status_id" => $this->status_id
        ];
    }
}
