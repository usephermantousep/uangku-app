<?php

namespace App\Filament\Pages\Auth;

use App\Models\Family;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Database\Eloquent\Model;

class Register extends BaseRegister
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        $this->getFamilyField(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getFamilyField(): TextInput
    {
        return TextInput::make('family_name')
            ->label(__('global.family_name'))
            ->maxLength(255)
            ->required();
    }

    protected function handleRegistration(array $data): Model
    {
        $user = $this->getUserModel()::create($data);
        $user->assignRole("Head of Family");
        $family = Family::create([
            'name' => $data['family_name']
        ]);
        $user->family()->attach($family->id);

        return $user;
    }
}
