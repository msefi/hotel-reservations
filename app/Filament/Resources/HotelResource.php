<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HotelResource\Pages;
use App\Filament\Resources\HotelResource\RelationManagers;
use App\Models\Hotel;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HotelResource extends Resource
{
    protected static ?string $model = Hotel::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Hotel';

    protected static ?string $navigationGroup = 'Master';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_hotel')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\Grid::make([
                    'default' => 3,
                ])->schema([
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
                    Forms\Components\TextInput::make('rating')
                        ->required()
                        ->numeric()
                        ->maxValue(5)
                        ->maxLength(1)
                        ->suffixIconColor('warning')
                        ->suffixIcon('heroicon-s-star'),
                ]),
                Forms\Components\Textarea::make('alamat')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->autocomplete(false)
                    ->rows(5),
                Forms\Components\FileUpload::make('foto')
                    ->required()
                    ->multiple()
                    ->reorderable()
                    ->appendFiles()
                    ->image()
                    ->directory('hotels')
                    ->visibility('private')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_hotel')
                    ->searchable(),
                Tables\Columns\TextColumn::make('provinsi.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kabupaten.name')
                    ->label('Kota')
                    ->sortable(),
                Tables\Columns\TextColumn::make('rating')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('foto')
                    ->circular()
                    ->stacked()
                    ->limit(3)
                    ->limitedRemainingText()
                    ->extraImgAttributes(['loading' => 'lazy']),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
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
                    ])
                    // handle the query
                    ->query(function (Builder $query, array $data) {
                        $provinsi_id    = (int) $data['provinsi_id'];
                        $kota_id        = (int) $data['kota_id'];
                 
                        if (!empty($kota_id)) {
                            $query->where('kota_id', '=', $kota_id);
                        }
                 
                        if (!empty($provinsi_id)) {
                            $query->where('provinsi_id', '=', $provinsi_id);
                        }
                 
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make()->label('Data Kamar'),
            ])
            ->recordUrl(
                fn (Hotel $record) => null,
            );;
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\KamarRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHotels::route('/'),
            'create' => Pages\CreateHotel::route('/create'),
            'edit' => Pages\EditHotel::route('/{record}/edit'),
            'view' => Pages\ViewHotel::route('/{record}'),
        ];
    }
}
