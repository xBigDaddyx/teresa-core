<?php

namespace App\Livewire\Pages;

use Domain\Accuracies\Models\CartonBox;
use Livewire\Component;
use Teresa\CartonBoxGuard\Facades\CartonBoxFacade;
use Mary\Traits\Toast;
//use Teresa\CartonBoxGuard\Models\CartonBox;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ValidatingPolybag extends Component
{
    use Toast;
    use LivewireAlert;
    public $carton;
    public $type;
    public $polybags;
    public bool $showTable = false;
    public $polybagForm = [
        'polybag_code' => null,
    ];
    public function resetPolybagForm()
    {
        $this->polybagForm['polybag_code'] = null;
    }
    public function mount($carton)
    {
        $this->carton = CartonBox::find($carton);
        $this->type = $this->carton->type;
        $this->polybags = $this->carton->polybags;
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
    public function toggleShowTable()
    {
        return $this->showTable = !$this->showTable;
    }
    public function render()
    {
        return view('livewire.pages.validating-polybag');
    }
    public function validation()
    {
        $validate = CartonBoxFacade::validatePolybag($this->carton, $this->polybagForm['polybag_code']);
        if ($validate === 'validated') {
            $this->polybags = CartonBox::find($this->carton->id)->polybags;
            $this->showToast('warning', 'Polybag Validated', 'Go for next!');
            $this->resetPolybagForm();
        } elseif ($validate === 'invalid') {
            $this->alert('warning', 'Invalid polybag, may wrong size or color.', [
                'toast' => false,
                'timer' => null,
                'position' => 'center',
                'allowOutsideClick' => false,
                'showConfirmButton' => true,
            ]);
            return $this->resetPolybagForm();
        }
        if ($validate === 'completed') {
            dd('completed');
        }
    }
}
