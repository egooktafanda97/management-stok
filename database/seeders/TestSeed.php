<?php

namespace Database\Seeders;

use App\Contract\AttributesFeature\Utils\AttributeExtractor;
use App\Models\User;
use App\Repositories\KasirRepository;
use App\Services\ActorService;
use App\Services\KasirService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestSeed extends Seeder
{
    public function __construct(
        public ActorService $actor,
    ) {
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // dd((new AttributeExtractor())->setClass(KasirRepository::class)->extractAttributes()->getAttributes());
        // auth()->login(User::find());
        // dd($this->actor->agency());
    }
}
