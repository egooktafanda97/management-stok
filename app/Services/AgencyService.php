<?php

namespace App\Services;

use App\Constant\Role;
use App\Constant\Status;
use App\Dtos\AgencyDTOs;
use App\Models\User;
use App\Repositories\AgencyRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AgencyService
{
    public function __construct(
        public AgencyRepository $agencyRepository,
        public UserRepository $userRepository
    ) {
    }

    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $user = $this->userRepository->store(array_merge($data, [
                "role" => Role::AGENCY,
                "status_id" => Status::ACTIVE,
            ]), Role::AGENCY);
            $agency = $this->agencyRepository
                ->transformer(array_merge($data, [
                    "user_id" => $user->id,
                    "status_id" => Status::ACTIVE,
                ]))
                ->validate()
                ->store();
            DB::commit();
            return (new AgencyDTOs())->getTransformerInAgencyModel($agency);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \Exception($th->getMessage());
        }
    }

    public function update(int $id, array $data)
    {
        DB::beginTransaction();
        try {
            $agency = $this->agencyRepository
                ->setId($id)
                ->transformer($data)
                ->validate()
                ->save();
            DB::commit();
            return $agency;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \Exception($th->getMessage());
        }
    }

    public function delete(int $id)
    {
        DB::beginTransaction();
        try {
            $ustId = $this->agencyRepository->find($id)->user_id;
            $agency = $this->agencyRepository->delete($id);
            User::whereId($ustId)
                ->delete();
            DB::commit();
            return $agency;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \Exception($th->getMessage());
        }
    }
}
