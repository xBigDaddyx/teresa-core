<?php

namespace App\Livewire;

use Filament\Facades\Filament;
use Jeffgreco13\FilamentBreezy\Livewire\MyProfileComponent;
use Livewire\Component;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Storage;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;
use Saade\FilamentAutograph\Forms\Components\Enums\DownloadableFormat;

class MyCustomProfileComponent extends MyProfileComponent
{
    protected string $view = "livewire.my-custom-profile-component";
    public array $data;
    public $user;
    public array $only = ['signature'];
    public static $sort = 15;
    public function mount()
    {
        $this->user = Filament::getCurrentPanel()->auth()->user();
        $this->form->fill($this->user->only($this->only));
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('signature')
                    ->directory('signatures')
                    ->image()
                    ->imageEditor(),

                // SignaturePad::make('signature')
                //     ->confirmable()
                //     ->label(__('Sign here'))
                //     ->dotSize(2.0)
                //     ->lineMinWidth(0.5)
                //     ->lineMaxWidth(2.5)
                //     ->throttle(16)
                //     ->minDistance(5)
                //     ->velocityFilterWeight(0.7)
                //     ->filename('autograph')             // Filename of the downloaded file (defaults to 'signature')
                //     ->downloadable()                    // Allow download of the signature (defaults to false)
                //     ->downloadableFormats([             // Available formats for download (defaults to all)
                //         DownloadableFormat::PNG,
                //         DownloadableFormat::JPG,
                //         DownloadableFormat::SVG,
                //     ])
                //     ->downloadActionDropdownPlacement('center-end')
                //     ->confirmable()

            ])
            ->statePath('data');
    }
    public function submit(): void
    {

        $data = collect($this->form->getState())->only($this->only)->all();

        $this->user->update($data);
    }
}
