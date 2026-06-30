<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LunchMenuItemResource\Pages;
use App\Models\LunchMenuItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LunchMenuItemResource extends Resource
{
    protected static ?string $model = LunchMenuItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Маркетинг';

    protected static ?string $modelLabel = 'обеден артикул';

    protected static ?string $pluralModelLabel = 'обедни артикули';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Основна информация')
                    ->schema([
                        Forms\Components\Select::make('section')
                            ->label('Секция')
                            ->options(LunchMenuItem::SECTIONS)
                            ->required()
                            ->searchable(),
                        Forms\Components\TextInput::make('name')
                            ->label('Име')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label('Състав / описание')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('price')
                            ->label('Цена')
                            ->numeric()
                            ->prefix('€')
                            ->required(),
                        Forms\Components\FileUpload::make('image')
                            ->label('Снимка')
                            ->image()
                            ->directory('lunch-items')
                            ->visibility('public')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Настройки')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Активен')
                            ->default(true),
                        Forms\Components\Toggle::make('is_hit')
                            ->label('Хит'),
                        Forms\Components\Toggle::make('is_new')
                            ->label('Ново'),
                        Forms\Components\Toggle::make('is_spicy')
                            ->label('Люто'),
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Подредба')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('section')
                    ->label('Секция')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Име')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Цена')
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_hit')
                    ->label('Хит')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_new')
                    ->label('Ново')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_spicy')
                    ->label('Люто')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Активен')
                    ->boolean(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Подредба')
                    ->sortable(),
            ])
            ->defaultSort('section')
            ->filters([
                Tables\Filters\SelectFilter::make('section')
                    ->label('Секция')
                    ->options(LunchMenuItem::SECTIONS),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Активен'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLunchMenuItems::route('/'),
            'create' => Pages\CreateLunchMenuItem::route('/create'),
            'edit' => Pages\EditLunchMenuItem::route('/{record}/edit'),
        ];
    }
}
