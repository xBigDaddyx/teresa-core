<?php

namespace App\Livewire\Pages;

use App\Livewire\Forms\ValidateForm;
use Domain\Accuracies\Models\CartonBox;
use Domain\Accuracies\Models\Polybag;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Teresa\CartonBoxGuard\Facades\CartonBoxFacade;
use Mary\Traits\Toast;
//use Teresa\CartonBoxGuard\Models\CartonBox;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Teresa\CartonBoxGuard\Facades\PolybagFacade;
use Livewire\Attributes\On;

class ValidatingPolybag extends Component
{
    use Toast;
    use LivewireAlert;
    public $carton;
    public $type;
    public $polybags;
    public bool $showTable = false;
    public bool $completed = false;
    public bool $polybagCompleted = false;
    public ValidateForm $form;
    public $polybagForm = [
        'polybag_code' => null,
    ];
    public $tagForm = [
        'tag_code' => null,
    ];
    public function resetTagForm()
    {
        $this->tagForm['tag_code'] = null;
    }
    public function resetPolybagForm()
    {
        $this->polybagForm['polybag_code'] = null;
    }
    public function mount($carton)
    {

        $this->carton = CartonBox::find($carton);
        $this->type = $this->carton->type;
        $this->polybags = $this->carton->polybags;

        if ($this->carton->is_completed === true) {
            $this->completed = true;
        }
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
    #[On('validation')]
    public function changeCompleted($value)
    {

        if ($value === 'saved' || $value === 'validated') {

            $polybag_count = Polybag::where('carton_box_id', $this->carton->id)->count();
            $max_qty = (int)$this->carton->quantity;

            if ($polybag_count === $max_qty) {

                $this->completed = true;
                return redirect(route('accuracy.completed.carton', ['carton' => $this->carton->id]));
            }
        }
        return $this->completed = false;
    }
    public function validation()
    {
        if ($this->carton->type === 'SOLID') {
            $validate = CartonBoxFacade::validatePolybag($this->carton, $this->polybagForm['polybag_code']);
        } elseif ($this->carton->type === 'RATIO') {
            if ($this->polybagCompleted) {
                $validate = CartonBoxFacade::validatePolybag($this->carton, $this->form->tag_barcode, $this->form->polybag_barcode, $this->polybagCompleted);


                $this->resetTagForm();
                return $this->redirect(route('accuracy.completed.carton', ['carton' => $this->carton->id]));
            }
            $validate = CartonBoxFacade::validatePolybag($this->carton, $this->form->tag_barcode, null, $this->polybagCompleted);
        }



        if ($validate === 'validated') {

            $this->polybags = CartonBox::find($this->carton->id)->polybags;
            $this->showToast('warning', 'Polybag Validated', 'Go for next!');
            $this->resetPolybagForm();
        } elseif ($validate === 'invalid') {
            $this->dispatch('swal', [
                'title' => 'Invalid Polybag',
                'text' => 'Please check the polybag may wrong size or color.',
                'icon' => 'error',
                'allowOutsideClick' => false,
                'showConfirmButton' => true,

            ]);

            $this->resetPolybagForm();
        } elseif ($validate === 'polybag completed') {
            $this->polybagCompleted = true;
            $this->dispatch('swal', [
                'title' => 'All garment validated',
                'text' => 'Next, Please scan the polybag/carton barcode to complete.',
                'icon' => 'success',
                'allowOutsideClick' => false,
                'showConfirmButton' => true,

            ]);
        } else if ($validate === 'incorrect') {
            $this->dispatch('swal', [
                'title' => 'Incorrect garment tag',
                'text' => 'Please check the garment may wrong size or color.',
                'icon' => 'error',
                'allowOutsideClick' => false,
                'showConfirmButton' => true,

            ]);
        } else if ($validate === 'max') {
            $this->dispatch('swal', [
                'title' => 'Maximum ratio reached',
                'text' => 'The ratio for this garment is reached maximum, please validate next ratio.',
                'icon' => 'warning',
                'allowOutsideClick' => false,
                'showConfirmButton' => true,

            ]);
        } else if ($validate === 'saved') {
            $this->resetTagForm();
            $this->showToast('warning', 'Garment Validated', 'Go for next!');
        }
    }
}
