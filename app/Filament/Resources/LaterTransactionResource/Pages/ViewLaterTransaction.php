<?php

namespace App\Filament\Resources\LaterTransactionResource\Pages;

use App\Filament\Resources\LaterTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLaterTransaction extends ViewRecord
{
    protected static string $resource = LaterTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
