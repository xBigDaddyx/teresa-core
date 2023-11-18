<?php

namespace Awcodes\FilamentBadgeableColumn\Components;

use Awcodes\FilamentBadgeableColumn\Concerns\HasBadges;
use Filament\Tables\Columns\TextColumn;

class BadgeableColumn extends TextColumn
{
    use HasBadges;
}
