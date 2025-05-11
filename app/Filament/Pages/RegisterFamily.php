<?php

namespace App\Filament\Pages;

use App\Models\Category;
use App\Models\Family;
use App\Models\ModeOfPayment;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant as BaseRegisterTenant;

class RegisterFamily extends BaseRegisterTenant
{
    public static function getLabel(): string
    {
        return __('global.register_family');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('global.family_name'))
                    ->unique(Family::class)
                    ->required()
                    ->maxLength(255),
            ]);
    }

    protected function handleRegistration(array $data): Family
    {
        $entity = parent::handleRegistration($data);
        $user = auth()->user();
        $user->family()->attach($entity->id);
        Category::initNewFamily($entity->id);
        ModeOfPayment::initNewFamily($entity->id);

        return $entity;
    }
}
