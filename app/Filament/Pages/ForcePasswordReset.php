<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ForcePasswordReset extends Page
{
    protected static string $view = 'filament.pages.force-password-reset';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Redefinição de Senha';

    protected static string $layout = 'filament-panels::components.layout.simple';

    public function hasLogo(): bool
    {
        return true;
    }

    public function getLogoHeight(): string
    {
        return '3rem';
    }

    public ?array $data = [];

    public function mount(): void
    {
        if (! auth()->user()->force_password_reset) {
            $this->redirect(\Filament\Facades\Filament::getUrl());

            return;
        }

        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('password')
                    ->label(__('Senha'))
                    ->password()
                    ->revealable()
                    ->required()
                    ->rule(Password::defaults())
                    ->same('password_confirmation')
                    ->suffixAction(
                        \Filament\Forms\Components\Actions\Action::make('generatePassword')
                            ->icon('heroicon-m-key')
                            ->tooltip('Gerar senha forte')
                            ->action(function (\Filament\Forms\Set $set) {
                                $password = \Illuminate\Support\Str::password(16);
                                
                                $set('password', $password);
                                $set('password_confirmation', $password);
                            })
                    ),
                TextInput::make('password_confirmation')
                    ->label(__('Confirme a Senha'))
                    ->password()
                    ->revealable()
                    ->required(),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = $this->form->getState();
        $user = auth()->user();

        $user->update([
            'password' => Hash::make($data['password']),
            'force_password_reset' => false,
        ]);

        // Use the same technique as Filament Breezy to update password without logout
        session()->forget('password_hash_'.\Filament\Facades\Filament::getCurrentPanel()->getAuthGuard());
        \Filament\Facades\Filament::auth()->login($user);

        Notification::make()
            ->title('Senha alterada com sucesso!')
            ->success()
            ->send();

        $this->redirect(\Filament\Facades\Filament::getUrl());
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSubmitFormAction(),
        ];
    }

    protected function getSubmitFormAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('submit')
            ->label(__('Alterar Senha'))
            ->color('primary')
            ->submit('submit');
    }
}
