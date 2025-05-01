<?php

namespace App\Filament\Resources\LaterTransactionResource\Pages;

use App\Filament\Resources\LaterTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLaterTransactions extends ListRecords
{
    protected static string $resource = LaterTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
