<?php

namespace App\Livewire\Pages;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Teresa\CartonBoxGuard\Facades\CartonBoxFacade;
use Mary\Traits\Toast;

class Validation extends Component
{
    use Toast;
    public $tenant;
    public bool $alert = false;

    public ?array $boxForm = [
        'box_code' => null,
        'po' => null,
        'carton_number' => null,
    ];
    public function resetBoxForm()
    {
        $this->boxForm = [
            'box_code' => null,
            'po' => null,
            'carton_number' => null,
        ];
    }
    public function mount()
    {
        $this->tenant = Auth::user()->company->short_name;
    }
    public function render()
    {
        return view('livewire.pages.validation');
    }
    public function showToast($type, $title, $description = null)
    {
        return $this->toast(
            type: $type,
            title: $title,
            description: $description,                  // optional (text)
            position: 'toast-top toast-end',    // optional (daisyUI classes)
            timeout: 6000,                      // optional (ms)
            redirectTo: null                    // optional (uri)
        );
    }
    public function check()
    {
        $box = CartonBoxFacade::validateCarton((string)$this->boxForm['box_code']);
        if (empty($this->boxForm['box_code'])) {
            if ($this->alert === true) {
                return $this->showToast('warning', 'Please confirm the available alert');
            }
            return $this->showToast('error', 'Please scan the carton box!', 'missing barcode to check.');
        }
        if (empty($box)) {
            $this->resetBoxForm();
            return $this->alert = true;
        }
        //pass
    }
}
