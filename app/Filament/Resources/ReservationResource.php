<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservationResource\Pages;
use App\Filament\Resources\ReservationResource\RelationManagers;
use App\Models\Hotel;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use App\Models\Reservation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Report';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('hotel.nama_hotel')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_masuk')
                    ->label('Checkin')
                    ->date()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_keluar')
                    ->label('Checkout')
                    ->date()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('jumlah_tamu')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('room.tipe_kamar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('room.nomor_kamar')
                    ->label('Nomor Kamar')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_harga')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('wilayah')
                    ->label('Wilayah')
                    // build the filter form
                    ->form([
                        Forms\Components\Select::make('provinsi_id')
                            ->required()
                            ->label('Provinsi')
                            ->searchable()
                            ->live()
                            ->options(Provinsi::all()->pluck('name', 'id')),
                        Forms\Components\Select::make('kota_id')
                            ->required()
                            ->label('Kota')
                            ->searchable()
                            ->live()
                            ->options(fn (Get $get): Collection => Kabupaten::query()
                                ->where('provinsi_id', $get('provinsi_id'))
                                ->pluck('name', 'id')),
                        Forms\Components\Select::make('hotel_id')
                            ->required()
                            ->label('Hotel')
                            ->searchable()
                            ->live()
                            ->options(fn (Get $get): Collection => Hotel::query()
                                ->where('kota_id', $get('kota_id'))
                                ->pluck('nama_hotel', 'id')),
                    ])
                        

                    // handle the query
                    ->query(function (Builder $query, array $data) {
                        $hotel_id       = (int) $data['hotel_id'];

                        if (!empty($hotel_id)) {
                            $query->where('hotel_id', '=', $hotel_id);
                        }
                 
                    }),
            ])
            ->bulkActions([
                
            ])
            ->recordUrl(
                fn (Reservation $record) => null,
            )
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'selesai'));
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
            'edit' => Pages\EditReservation::route('/{record}/edit'),
        ];
    }
}
