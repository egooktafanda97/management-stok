<?php

namespace Tests\Unit;

use App\Dtos\AgencyDTOs;
use PHPUnit\Framework\TestCase;

class BaseDTOsTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        try {
            (new AgencyDTOs())->setNama("ego oktafanda")->toArray();
            $this->assertTrue(true);
        } catch (\Throwable $th) {
            $this->fail($th->getMessage());
        }
    }
}
