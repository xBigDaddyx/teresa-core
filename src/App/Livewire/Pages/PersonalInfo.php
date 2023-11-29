<?php

namespace App\Livewire\Pages;

use Filament\Forms;
use Filament\Notifications\Notification;
use Jeffgreco13\FilamentBreezy\Livewire\PersonalInfo as LivewirePersonalInfo;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;
use Saade\FilamentAutograph\Forms\Components\Enums\DownloadableFormat;
use Filament\Forms\Form;

class PersonalInfo extends LivewirePersonalInfo
{
    public array $only = ['name', 'email', 'signature'];
    protected function getProfileFormSchema()
    {
        $groupFields = Forms\Components\Group::make([
            $this->getNameComponent(),
            $this->getEmailComponent(),
        ])->columnSpan(2);

        return ($this->hasAvatars)
            ? [filament('filament-breezy')->getAvatarUploadComponent(), $groupFields, $this->getSignatureComponent()]
            : [$groupFields];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema($this->getProfileFormSchema())->columns(3)
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = collect($this->form->getState())->only($this->only)->all();
        $this->user->update($data);
        $this->sendNotification();
    }
    protected function getNameComponent(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('name')
            ->required()
            ->label(__('filament-breezy::default.fields.name'));
    }

    protected function getEmailComponent(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('email')
            ->required()
            ->email()
            ->unique($this->userClass, ignorable: $this->user)
            ->label(__('filament-breezy::default.fields.email'));
    }
    protected function getSignatureComponent(): Forms\Components\FileUpload
    {
        return Forms\Components\FileUpload::make('signature')
            ->label('Signature')
            ->image()
            ->imagePreviewHeight('250')
            ->loadingIndicatorPosition('left')
            ->panelAspectRatio('2:1')
            ->panelLayout('integrated')
            ->removeUploadedFileButtonPosition('right')
            ->uploadButtonPosition('left')
            ->uploadProgressIndicatorPosition('left');
    }
}
