<?php

namespace App\Filament\Resources\LaterTransactionResource\Pages;

use App\Filament\Resources\LaterTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLaterTransaction extends EditRecord
{
    protected static string $resource = LaterTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
