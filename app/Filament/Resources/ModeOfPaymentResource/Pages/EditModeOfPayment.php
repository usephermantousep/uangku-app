<?php

namespace App\Filament\Resources\ModeOfPaymentResource\Pages;

use App\Filament\Resources\ModeOfPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditModeOfPayment extends EditRecord
{
    protected static string $resource = ModeOfPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
