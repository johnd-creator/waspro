<?php

namespace App\Enums;

enum LogStatus: string
{
    case Tersimpan = 'Tersimpan';
    case Diangkut = 'Diangkut';
    case Kadaluarsa = 'Kadaluarsa';

    public function label(): string
    {
        return match ($this) {
            self::Tersimpan => 'Tersimpan',
            self::Diangkut => 'Diangkut',
            self::Kadaluarsa => 'Kadaluarsa',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Tersimpan => 'bg-blue-100 text-blue-800',
            self::Diangkut => 'bg-purple-100 text-purple-800',
            self::Kadaluarsa => 'bg-red-100 text-red-800',
        };
    }
}
