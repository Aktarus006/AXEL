<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum Status: string implements HasLabel, HasColor, HasIcon
{
    case ONLINE = "Online";
    case OFFLINE = "Offline";

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ONLINE => "En ligne",
            self::OFFLINE => "Hors ligne",
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::ONLINE => "green",
            self::OFFLINE => "red",
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::ONLINE => "heroicon-o-check-circle",
            self::OFFLINE => "heroicon-o-x-circle",
        };
    }
}
