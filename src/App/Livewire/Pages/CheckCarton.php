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

    public function showToast($type, $title, $description = null, $position = 'toast-top toast-end', $redirect = null)
    {
        return $this->toast(
            type: $type,
            title: $title,
            description: $description,                  // optional (text)
            position: $position,    // optional (daisyUI classes)
            timeout: 6000,                      // optional (ms)
            redirectTo: $redirect                    // optional (uri)
        );
    }
    public function updated($property)
    {
        // $property: The name of the current property that was updated

        if ($property === 'extraForm.selectedPo') {
            $box_code = (string)$this->boxForm['box_code'];
            $po = (string)$this->extraForm['selectedPo'];
            $null_carton_numbers = [
                null => ['carton_number' => '-- Select Carton Number --']
            ];
            $this->carton_numbers = array_merge(CartonBox::select('carton_number')->where('box_code', $box_code)->whereHas('packingList', function (Builder $query) use ($po) {
                $query->where('po', $po);
            })->distinct('carton_number')->get()->toArray(), $null_carton_numbers);
        }
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
            $this->showToast('warning', 'Many carton found!', 'Please select PO and Carton Number for more specific.');
            $null_pos = [
                null => ['po' => '-- Select PO --']
            ];

            $this->pos = array_merge(PackingList::select('po')->whereHas('cartonBoxes', function (Builder $query) use ($box_code) {
                $query->where('box_code', $box_code);
            })->distinct('po')->get()->toArray(), $null_pos);


            return $this->showExtraForm = true;
        }
        if (!empty($box->is_completed)) {
            if ($box->is_completed === true || $box->is_completed === 'true') {

                return redirect(route('accuracy.completed.carton', ['carton' => $box->id]));
            }
        }

        $this->showToast('success', 'Carton box found!', 'Going to validate of this carton');
        return redirect(route('accuracy.validation.polybag', ['carton' => $box->id]));
    }
}
