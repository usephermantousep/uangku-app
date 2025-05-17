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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user_id = auth()->user()->id;
        $data['amount'] = str_replace('.', '', $data['amount']);
        $data['updated_by'] = $user_id;
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
