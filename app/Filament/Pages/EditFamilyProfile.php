<?php

namespace App\Filament\Pages;

use App\Models\Family;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\EditTenantProfile;

class EditFamilyProfile extends EditTenantProfile
{
    protected function getRedirectUrl(): ?string
    {
        return '/admin/' . Filament::getTenant()->id;
    }
    public static function getLabel(): string
    {
        return __('global.edit_yout_family');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->unique(Family::class)
                    ->label(__('global.family_name'))
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
