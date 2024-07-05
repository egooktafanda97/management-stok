<?php

namespace App\Services;

use App\Constant\Role;
use App\Constant\Status;
use App\Models\User;
use App\Repositories\GudangRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GudangService
{
    public function __construct(
        public ActorService $actor,
        public UserRepository $userRepository,
        public GudangRepository $gudangRepository
    ) {
    }

    // required data [username,password,nama,alamat,telepon,logo,deskripsi]
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $user = $this->userRepository->store(array_merge($data, [
                "role" => Role::GUDANG
            ]), Role::GUDANG);
            if (!$this->actor->agency()) throw new \Exception("Anda tidak memiliki akses");
            $gudang = $this->gudangRepository
                ->transformer(array_merge($data, [
                    "agency_id" => $this->actor->agency()->id,
                    'user_id' => $user->id
                ]))
                ->validate()
                ->create();
            DB::commit();
            return $gudang;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \Exception('create gudang:' . $th->getMessage());
        }
    }
    // gudang update
    public function update(int $id, array $data)
    {
        DB::beginTransaction();
        try {
            $agency = $this->gudangRepository
                ->setId($id)
                ->transformer(array_merge($data, [
                    "agency_id" => $this->actor->agency()->id
                ]))
                ->validate()
                ->save();
            DB::commit();
            return $agency;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \Exception($th->getMessage());
        }
    }

    // gudang delte
    public function delete(int $id)
    {
        DB::beginTransaction();
        try {
            $usId = $this->gudangRepository->find($id)->user_id;
            $gudang = $this->gudangRepository
                ->setId($id)
                ->delete();

            if (!$gudang) throw new \Exception("Gudang tidak ditemukan");
            if ($usId)
                User::find($usId)->delete();
            DB::commit();
            return $gudang;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \Exception($th->getMessage());
        }
    }

    public function find(int $id)
    {
        return $this->gudangRepository->find($id);
    }
    // getPaginate
    public function getPaginate($limit = 10)
    {
        return $this->gudangRepository->getGudangPaginate($this->actor, $limit);
    }
}
