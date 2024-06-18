<?php

namespace App\Services;

use App\Constant\Role;
use App\Dtos\GeneralActorDTOs;
use App\Dtos\UsersDTOs;
use App\Repositories\GeneralActorRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;

class GeneralActorService
{
    public GeneralActorDTOs $generalActor;
    public int $id;
    public function __construct(
        public ActorService $actor,
        public UserRepository $userRepository,
        public GeneralActorRepository $generalActorRepository
    ) {
    }

    public function fromDTOs(GeneralActorDTOs $generalActor)
    {
        $this->generalActor = $generalActor
            ->setAgencyId($this->actor->agency()->id)
            ->setOncardInstansiId($generalActor->oncard_instansi_id)
            ->setOncardUserId($generalActor->oncard_user_id)
            ->setOncardAccountNumber($generalActor->oncard_account_number);
        $this->generalActor = $generalActor;
        return $this;
    }
    public function create()
    {
        DB::beginTransaction();
        try {
            if ($this->userRepository->fundBy("username", ($this->generalActor->user->username ?? null)))
                throw new \Exception("Username sudah digunakan", 500);

            if (!$this->generalActor->getId())
                $user = $this->userRepository->store([
                    "nama" => $this->generalActor->nama,
                    "username" => $this->generalActor->user->username,
                    "password" => $this->generalActor->user->password,
                    "role" => Role::GENERAL
                ], Role::GENERAL);

            $generalActor = $this->generalActorRepository
                ->transformer(GeneralActorDTOs::fromArray(array_merge($this->generalActor->toArray(), [
                    "user_id" => $user->id ??  null
                ]))->toArray())
                ->setId($this->generalActor->getId() ?? null)
                ->validate()
                ->save();
            DB::commit();
            return $this->generalActor
                ->setId($generalActor->id)
                ->setUser(!empty($user) ? UsersDTOs::fromArray($user->toArray() ?? [])->setId($user->id ?? null) : null);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
