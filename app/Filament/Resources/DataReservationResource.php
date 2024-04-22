<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DataReservationResource\Pages;
use App\Filament\Resources\DataReservationResource\RelationManagers;
use App\Helpers\Helper;
use App\Models\Customer;
use App\Models\Hotel;
use App\Models\Kabupaten;
use App\Models\Kamar;
use App\Models\Provinsi;
use App\Models\Reservation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class DataReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make([
                    'default' => 2,
                ])->schema([
                    Forms\Components\Select::make('customer_id')
                        ->required()
                        ->label('Customer')
                        ->searchable()
                        ->columnSpanFull()
                        ->options(Customer::where('user_type', 'customer')->pluck('name', 'id')),
                    // Forms\Components\Select::make('provinsi_id')
                    //     ->required()
                    //     ->label('Provinsi')
                    //     ->searchable()
                    //     ->live()
                    //     ->options(Provinsi::all()->pluck('name', 'id')),
                    // Forms\Components\Select::make('kota_id')
                    //     ->required()
                    //     ->label('Kota')
                    //     ->searchable()
                    //     ->live()
                    //     ->options(fn (Get $get): Collection => Kabupaten::query()
                    //         ->where('provinsi_id', $get('provinsi_id'))
                    //         ->pluck('name', 'id')),
                    Forms\Components\Select::make('hotel_id')
                        ->required()
                        ->label('Hotel')
                        ->searchable()
                        ->live()
                        ->options(fn (Get $get): Collection => Hotel::query()
                            // ->where('kota_id', $get('kota_id'))
                            ->pluck('nama_hotel', 'id')),
                    Forms\Components\Select::make('kamar_id')
                        ->required()
                        ->label('Kamar')
                        ->searchable()
                        ->live()
                        ->options(fn (Get $get): Collection => Kamar::query()
                            ->selectRaw("CONCAT(tipe_kamar, ' - ', nomor_kamar) AS name, id")
                            ->where('hotel_id', $get('hotel_id'))
                            ->where('status', 'tersedia')
                            ->pluck('name', 'id')),
                    Forms\Components\DatePicker::make('tanggal_masuk')
                        ->required(),
                    Forms\Components\DatePicker::make('tanggal_keluar')
                        ->required()
                        ->live()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            $checkin    = $get('tanggal_masuk');
                            $checkout   = $get('tanggal_keluar');
                            $kamar_id   = $get('kamar_id');
                            $days       = Helper::GetTotalDays($checkin, $checkout);
                            
                            if($checkout < $checkin) {
                                Notification::make()
                                    ->title('Tanggal Checkout tidak boleh kurang dari tanggal checkin')
                                    ->danger()
                                    ->send();
                                $set('tanggal_keluar', '');
                                return;
                            }

                            if(!$kamar_id) {
                                Notification::make()
                                    ->title('Silahkan pilih kamar terlebih dahulu')
                                    ->danger()
                                    ->send();
                                $set('tanggal_keluar', '');
                                return;
                            }
                        
                            $room   = Kamar::find($kamar_id);
                            $price  = $days * $room->harga;
                            $set('total_harga', number_format($price));
                        }),
                    Forms\Components\TextInput::make('jumlah_tamu')
                        ->numeric()
                        ->required(),
                    Forms\Components\TextInput::make('total_harga')
                        ->readOnly()
                        ->required(),
                ])
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
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('checkout')
                    ->requiresConfirmation()
                    ->icon('heroicon-o-arrow-right-start-on-rectangle')
                    ->modalIcon('heroicon-o-arrow-right-start-on-rectangle')
                    ->action(function (Reservation $record): void {
                        $reservation    = Reservation::find($record->id);
                        $reservation->status    = 'selesai';
                        $reservation->save();

                        $room           = $record->room;
                        $room->status   = 'tersedia';
                        $room->save();

                        Notification::make()
                            ->title('Checkout Berhasil')
                            ->success()
                            ->send();
                    })
            ])
            ->bulkActions([
                
            ])
            ->recordUrl(
                fn (Reservation $record) => null,
            )
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'dipesan'));
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
            'index' => Pages\ListDataReservations::route('/'),
            'create' => Pages\CreateDataReservation::route('/create'),
            'edit' => Pages\EditDataReservation::route('/{record}/edit'),
        ];
    }
}
