<?php

namespace App\Filament\Pages;

use App\Models\Family;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant as BaseRegisterTenant;

class RegisterFamily extends BaseRegisterTenant
{
    public static function getLabel(): string
    {
        return 'Register Family';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->unique(Family::class)
                    ->label('Name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    protected function handleRegistration(array $data): Family
    {
        $entity = parent::handleRegistration($data);
        $user = auth()->user();
        $user->family()->attach($entity->id);

        return $entity;
    }
}
