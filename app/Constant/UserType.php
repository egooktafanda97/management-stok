<?php

namespace App\Constant;

enum UserType: string
{
    const Siswa = 'siswa';
    const General = 'general';
    const Merchant = 'merchant';
    const Agency = 'agency';
    const Owner = 'owner';
}
