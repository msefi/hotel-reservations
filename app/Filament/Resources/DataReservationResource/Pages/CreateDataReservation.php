<?php

namespace App\Filament\Resources\DataReservationResource\Pages;

use App\Filament\Resources\DataReservationResource;
use App\Helpers\Helper;
use App\Models\Kamar;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDataReservation extends CreateRecord
{
    protected static string $resource = DataReservationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $days   = Helper::GetTotalDays($data['tanggal_masuk'], $data['tanggal_keluar']);
        $room   = Kamar::find($data['kamar_id']);
        $price  = $days * $room->harga;
        
        $data['total_harga'] = $price;
    
        return $data;
    }

    protected function afterCreate(): void
    {
        $dataform   = $this->data;
        $kamar_id   = $dataform['kamar_id'];

        $room           = Kamar::find($kamar_id);
        $room->status   = 'dipesan';
        $room->save();

    }
}
