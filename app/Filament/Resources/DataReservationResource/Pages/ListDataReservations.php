<?php

namespace App\Filament\Resources\DataReservationResource\Pages;

use App\Filament\Resources\DataReservationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDataReservations extends ListRecords
{
    protected static string $resource = DataReservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
