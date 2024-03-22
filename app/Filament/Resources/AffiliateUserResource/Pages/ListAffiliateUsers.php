<?php

namespace App\Filament\Resources\AffiliateUserResource\Pages;

use App\Filament\Resources\AffiliateUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAffiliateUsers extends ListRecords
{
    protected static string $resource = AffiliateUserResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
