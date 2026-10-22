<?php

namespace App\Filament\Resources\SpSiswas\Pages;

use App\Filament\Resources\SpSiswas\SpSiswaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageSpSiswas extends ManageRecords
{
    protected static string $resource = SpSiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
