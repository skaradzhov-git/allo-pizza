<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class TopProductsWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Най-продавани продукти';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                OrderItem::query()
                    ->select([
                        DB::raw('MIN(id) as id'),
                        'product_id',
                        'product_name',
                        DB::raw('SUM(quantity) as total_quantity'),
                        DB::raw('SUM(total_price) as total_revenue'),
                    ])
                    ->whereNotNull('product_id')
                    ->groupBy('product_id', 'product_name')
                    ->orderByDesc('total_quantity')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('product_name')
                    ->label('Продукт'),
                Tables\Columns\TextColumn::make('total_quantity')
                    ->label('Продадени')
                    ->numeric(),
                Tables\Columns\TextColumn::make('total_revenue')
                    ->label('Приход')
                    ->money('EUR'),
            ])
            ->paginated(false);
    }

    public function getTableRecordKey($record): string
    {
        return (string) ($record->product_id ?? $record->product_name);
    }
}
