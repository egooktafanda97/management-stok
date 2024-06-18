<?php

namespace App\Services;

use App\Constant\Role;
use App\Models\User;
use App\Repositories\KasirRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;

class KasirService
{
    public function __construct(
        public ActorService $actor,
        public UserRepository $userRepository,
        public KasirRepository $kasirRepository
    ) {
    }
    //create
    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $user = $this->userRepository->store(array_merge($data, [
                "role" => Role::KASIR
            ]), Role::KASIR);

            if (!$this->actor->gudang()) throw new \Exception("Anda tidak memiliki akses");
            $toko = $this->kasirRepository
                ->transformer(array_merge($data, [
                    "agency_id" => $this->actor->agency()->id,
                    "user_id" => $user->id,
                    "gudang_id" => $this->actor->gudang()->id,
                ]))
                ->validate()
                ->create();
            DB::commit();
            return $toko;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \Exception($th->getMessage());
        }
    }

    //update
    public function update(int $id, array $data)
    {
        DB::beginTransaction();
        try {
            $toko = $this->kasirRepository
                ->setId($id)
                ->transformer(array_merge($data, [
                    "agency_id" => $this->actor->agency()->id,
                    "gudang_id" => $this->actor->gudang()->id,
                ]))
                ->validate()
                ->save();
            DB::commit();
            return $toko;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \Exception($th->getMessage());
        }
    }

    //delete
    public function delete(int $id)
    {
        DB::beginTransaction();
        try {
            // remove user
            $usr = $this->kasirRepository->find($id)->user_id;
            $toko = $this->kasirRepository->delete($id);
            User::find($usr)->delete();
            DB::commit();
            return $toko;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \Exception($th->getMessage());
        }
    }
}
