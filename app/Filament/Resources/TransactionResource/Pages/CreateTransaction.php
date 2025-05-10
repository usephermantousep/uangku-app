<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;

    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user_id = auth()->user()->id;
        $data['amount'] = str_replace('.', '', $data['amount']);
        $data['created_by'] = $user_id;
        $data['updated_by'] = $user_id;
        return $data;
    }
}
