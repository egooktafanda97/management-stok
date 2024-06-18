<?php

namespace App\Services;

use App\Constant\Role;
use App\Models\Agency;
use App\Models\Kasir;
use App\Models\Supplier;
use App\Models\User;
use App\Repositories\AgencyRepository;
use App\Repositories\GeneralActorRepository;
use App\Repositories\GudangRepository;
use App\Repositories\KasirRepository;
use App\Repositories\UserRepository;

class ActorService
{
    public function __construct(
        public AgencyRepository $agencyRepository,
        public GudangRepository $gudangRepository,
        public KasirRepository $kasirRepository,
        public GeneralActorRepository $generalActorRepository,
    ) {
    }
    public function authId()
    {
        return auth()->user()->id ?? 1;
    }

    //actor used
    public function agency($agencyId = null)
    {
        if ($agencyId) {
            return $this->agencyRepository->model->where('id', $agencyId)
                ->with("user")
                ->first();
        }
        $authUser = auth()->user();
        if ($authUser->hasRole("agency")) {
            return  $this->agencyRepository->model->where('user_id', $authUser->id)
                ->with("user")
                ->first();
        } else {
            if ($authUser->hasRole("kasir")) {
                $kasir = $this->kasirRepository->model->where('user_id', $authUser->id)
                    ->with("user")
                    ->first();
                return $this->agencyRepository->model->where('id', $kasir->agency_id)
                    ->with("user")
                    ->first();
            } else if ($authUser->hasRole("gudang")) {
                $gudang = $this->gudangRepository->model->where('user_id', $authUser->id)
                    ->with("user")
                    ->first();
                return $this->agencyRepository->model->where('id', $gudang->agency_id)
                    ->with("user")
                    ->first();
            } else {
                $userAuthId = auth()->user()->id;
                $usr =  User::find($userAuthId)
                    ->agency();
                return $this->agencyRepository->model->where('user_id', $usr->id)
                    ->with("user")
                    ->first();
            }
        }
    }

    // actor gudang
    public function gudang($gudangId = null)
    {
        if ($gudangId) {
            return $this->gudangRepository->model->where('id', $gudangId)
                ->with("user")
                ->first();
        }
        $authUser = auth()->user();
        if ($authUser->hasRole("gudang")) {
            return  $this->gudangRepository->model->where('user_id', $authUser->id)
                ->with("user")
                ->first();
        } else {
            if ($authUser->hasRole("kasir")) {
                $kasir = $this->kasirRepository->model->where('user_id', $authUser->id)
                    ->with("user")
                    ->first();
                return $this->gudangRepository->model->where('id', $kasir->gudang_id)
                    ->with("user")
                    ->first();
            } else {
                $userAuthId = auth()->user()->id;
                $usr =  User::find($userAuthId)
                    ->gudang();
                dd($usr);
                return $this->gudangRepository->model::where('user_id', $usr->id)
                    ->with("user")
                    ->first();
            }
        }
    }

    // actor kasir
    public function kasir($kasirId = null)
    {
        if ($kasirId) {
            return $this->kasirRepository->model->where('id', $kasirId)
                ->with("user")
                ->first();
        }
        $authUser = auth()->user();
        if ($authUser->hasRole(Role::AGENCY)) {
            return  $this->kasirRepository->model->where('agency_id', $this->agency()->id)
                ->with("user")
                ->get();
        }
        if ($authUser->hasRole(Role::KASIR)) {
            return  $this->kasirRepository->model->where('user_id', $authUser->id)
                ->with("user")
                ->first();
        }
        if ($authUser->hasRole(Role::GUDANG)) {
            return  $this->kasirRepository->model->where('gudang_id', $this->gudang()->id)
                ->with("user")
                ->get();
        } else {
            $userAuthId = auth()->user()->id;
            $usr =  User::find($userAuthId)
                ->kasir();
            return $this->kasirRepository->model->where('user_id', $usr->id)
                ->with("user")
                ->first();
        }
    }

    // actor general user
    public function general($pelangganId = null)
    {
        if ($pelangganId) {
            return $this->generalActorRepository->model->where('id', $pelangganId)
                ->with("user")
                ->first();
        }
        $authUser = auth()->user();
        if ($authUser->hasRole(Role::AGENCY)) {
            return  $this->generalActorRepository->model->where('agency_id', $this->agency()->id)
                ->with("user")
                ->get();
        }

        if ($authUser->hasRole(Role::GUDANG)) {
            return  $this->generalActorRepository->model->where('agency_id', $this->gudang()->id)
                ->with("user")
                ->get();
        }

        if ($authUser->hasRole(Role::KASIR)) {
            $kasir = $this->kasirRepository->model->where('user_id', $authUser->id)
                ->with("user")
                ->first();
            return  $this->generalActorRepository->model->where('agency_id', $kasir->agency_id)
                ->with("user")
                ->get();
        } else {
            $userAuthId = auth()->user()->id;
            $usr =  User::find($userAuthId)
                ->kasir();
            return $this->kasirRepository->model->where('user_id', $usr->id)
                ->with("user")
                ->first();
        }
    }



    public function login($username, $password = null)
    {
        $user = User::where('username', $username)->first();
        auth()->login($user);
    }
    /**
     * agency set in array data key
     * [kode_instansi,nama,username,password,nama,alamat]
     */

    // public function kasir()
    // {
    //     $user = auth()->user();

    //     if ($user->hasRole("kasir")) {
    //         return $this->kasir->where('user_id', $user->id)->with('user')->first();
    //     }

    //     return null;
    // }

    // public function supplier($supplier_id = null)
    // {
    //     $supId = request()->supplier_id ?? $supplier_id ?? null;
    //     if ($supId) {
    //         return $this->supplier->where('id', $supId)
    //             ->first();
    //     }

    //     return null;
    // }
    // public function blok($id)
    // {
    //     try {
    //         $actor = $this->user->find($id)->actor;
    //         $actor->status = 14;
    //         $actor->save();
    //         return $this->user->find($id)->actor;
    //     } catch (\Throwable $th) {
    //         return $th;
    //     }
    // }
}
