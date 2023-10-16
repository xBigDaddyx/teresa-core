<?php

namespace App\Livewire\Pages;

use Domain\Accuracies\Models\CartonBox;
use Domain\Accuracies\Models\PackingList;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Teresa\CartonBoxGuard\Facades\CartonBoxFacade;
use Mary\Traits\Toast;


class CheckCarton extends Component
{
    use Toast;
    public $tenant;
    public bool $showExtraForm = false;
    public $pos;
    public $carton_numbers;

    public ?array $extraForm = [
        'selectedPo' => '-- Select PO --',
        'selectedCartonNumber' => '-- Select Carton Number --'
    ];

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
        return view('livewire.pages.check-carton');
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
        $box_code = (string)$this->boxForm['box_code'];

        if ($this->extraForm['selectedPo'] !== '-- Select PO --' || $this->extraForm['selectedCartonNumber'] !== '-- Select Carton Number --') {

            $box = CartonBoxFacade::validateCarton($box_code, $this->extraForm['selectedPo'], $this->extraForm['selectedCartonNumber']);
        } else {
            $box = CartonBoxFacade::validateCarton($box_code);
        }


        if (empty($this->boxForm['box_code'])) {
            return $this->dispatch('swal', [
                'title' => 'Missing carton box barcode',
                'text' => 'Please scan the carton box barcode',
                'icon' => 'warning',

            ]);
        }
        if (empty($box)) {
            $this->resetBoxForm();
            return $this->dispatch('swal', [
                'title' => 'Carton box not found!',
                'text' => 'Please check to your admin for available this carton.',
                'icon' => 'error',

            ]);
            //return $this->alert = true;
        }
        if ($box === 'multiple') {
            $null_pos = [
                null => ['po' => '-- Select PO --']
            ];
            $null_carton_numbers = [
                null => ['carton_number' => '-- Select Carton Number --']
            ];
            $this->pos = array_merge(PackingList::select('po')->whereHas('cartonBoxes', function (Builder $query) use ($box_code) {
                $query->where('box_code', $box_code);
            })->distinct('po')->get()->toArray(), $null_pos);

            $this->carton_numbers = array_merge(CartonBox::select('carton_number')->where('box_code', $box_code)->distinct('carton_number')->get()->toArray(), $null_carton_numbers);
            return $this->showExtraForm = true;
        }

        $this->showToast('warning', 'Carton box found!', 'Going to validate of this carton');
        return redirect(route('accuracy.validation.polybag', ['carton' => $box->id]));
    }
}
