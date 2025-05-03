<?php

namespace App\Filament\Resources\ModeOfPaymentResource\Pages;

use App\Filament\Resources\ModeOfPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewModeOfPayment extends ViewRecord
{
    protected static string $resource = ModeOfPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
