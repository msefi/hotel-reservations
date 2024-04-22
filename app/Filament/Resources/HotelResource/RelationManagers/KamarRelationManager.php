<?php

namespace App\Filament\Resources\HotelResource\RelationManagers;

use App\Helpers\Helper;
use App\Models\Kamar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class KamarRelationManager extends RelationManager
{
    protected static string $relationship = 'kamar';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nomor_kamar')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('tipe_kamar')
                    ->required()
                    ->options(Helper::ListTipeKamar()),
                Forms\Components\Select::make('fasilitas')
                    ->required()
                    ->multiple()
                    ->options(Helper::ListFasilitasKamar()),
                Forms\Components\TextInput::make('harga')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('foto')
                    ->required()
                    ->multiple()
                    ->reorderable()
                    ->appendFiles()
                    ->image()
                    ->directory('rooms')
                    ->visibility('private')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_kamar')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipe_kamar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('harga')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('foto')
                    ->circular()
                    ->stacked()
                    ->limit(3)
                    ->limitedRemainingText()
                    ->extraImgAttributes(['loading' => 'lazy']),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'dipesan'       => 'gray',
                        'tersedia'      => 'success',
                        'tidak tersedia'=> 'danger',
                    }),
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->authorize(true),
                Tables\Actions\DeleteAction::make()->authorize(true),
                Tables\Actions\ReplicateAction::make()
                    ->requiresConfirmation()
                    ->modalDescription('Replikasi kamar akan menggenerate nomor kamar baru')
                    ->excludeAttributes(['nomor_kamar'])
                    ->beforeReplicaSaved(function (Kamar $replica): void {
                        $replica->nomor_kamar   = Helper::GenerateNoKamar($replica->hotel_id);
                    })
                    ->authorize(true),
            ])
            ->bulkActions([
                
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make('create_kamar')->authorize(true)
            ])
            ->recordUrl(
                fn (Kamar $record) => null,
            );
    }


}
