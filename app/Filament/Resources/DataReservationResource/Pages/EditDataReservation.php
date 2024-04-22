<?php

namespace App\Filament\Resources\DataReservationResource\Pages;

use App\Filament\Resources\DataReservationResource;
use App\Helpers\Helper;
use App\Models\Kamar;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDataReservation extends EditRecord
{
    protected static string $resource = DataReservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $days   = Helper::GetTotalDays($data['tanggal_masuk'], $data['tanggal_keluar']);
        $room   = Kamar::find($data['kamar_id']);
        $price  = $days * $room->harga;
        
        $data['total_harga'] = $price;
    
        return $data;
    }
}
