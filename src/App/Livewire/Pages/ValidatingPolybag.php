<?php

namespace App\Livewire\Pages;

use App\Livewire\Forms\ValidateForm;
use Domain\Accuracies\Models\CartonBox;
use Domain\Accuracies\Models\CartonBoxAttribute;
use Domain\Accuracies\Models\Polybag;
use Domain\Accuracies\Models\Tag;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Teresa\CartonBoxGuard\Facades\CartonBoxFacade;
use Mary\Traits\Toast;
//use Teresa\CartonBoxGuard\Models\CartonBox;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Teresa\CartonBoxGuard\Facades\PolybagFacade;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Computed;

class ValidatingPolybag extends Component
{
    use Toast;
    use LivewireAlert;
    public $carton;
    public $type;
    public $polybags;
    public $tags;
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
        session()->forget('carton');
        $this->carton = CartonBox::with('polybags', 'cartonBoxAttributes')->find($carton);
        $this->polybags = $this->carton->polybags;
        session()->put('carton.id', $this->carton->id);

        if (session()->get('carton.max_quantity') === null || empty(session()->get('carton.max_quantity'))) {
            if (!Session::has('carton.first_polybag')) {

                session()->put('carton.first_polybag', $this->carton->polybags->sortBy('created_at')->first()->polybag_code ?? null);
            }
            if (!Session::has('carton.type')) {
                session()->put('carton.type', $this->carton->type);
            }

            session()->put('carton.validated', $this->polybags->count());
            session()->put('carton.max_quantity', $this->carton->quantity);
        }
        if (session()->get('carton.type') === 'RATIO' || session()->get('carton.type') === 'MIX') {
            $cartonBox = $this->carton;
            $this->tags =  Tag::whereHas('attributable', function (Builder $a) use ($cartonBox) {
                $a->where('carton_box_id', $cartonBox->id);
            })->whereNull('taggable_id')->get();
            if (session()->get('carton.tags') === null || count(session()->get('carton.tags')) > 0 || empty(session()->get('carton.tags'))) {
                session()->put(
                    'carton.tags',
                    $this->tags
                );
            }
            if (session()->get('carton.attributes') === null || count(session()->get('carton.attributes')) > 0 || empty(session()->get('carton.attributes'))) {
                session()->put('carton.attributes', $this->carton->cartonBoxAttributes->toArray());
                session()->put('carton.total_attributes', $this->carton->cartonBoxAttributes->sum('quantity'));
            }
        }

        if ($this->carton->is_completed === true) {
            $this->completed = true;
            return redirect(route('accuracy.completed.carton', ['carton' => $this->carton->id]));
        }
    }
    public function saveGarment(CartonBoxAttribute $attribute_model)
    {
        $tag_value = new Tag();
        $tag_value->type = 'RATIO';
        $tag_value->tag = $this->form->tag_barcode;
        $attribute_model->tags()->save($tag_value);
    }
    public function savePolybag()
    {

        // model create record
        Polybag::create(
            [
                'polybag_code' => $this->form->polybag_barcode,
                'carton_box_id' => $this->carton->id,
            ]
        );

        if (empty($first_polybag) || $first_polybag === null) {
            session()->put('carton.first_polybag', $this->form->polybag_barcode);
        }
        session()->increment('carton.validated');
        if ((int)session()->get('carton.validated') === (int)session()->get('carton.max_quantity')) {
            return redirect(route('accuracy.completed.carton', ['carton' => $this->carton->id]));
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
        if (session()->get('carton.type') === 'SOLID') {
            $validate = CartonBoxFacade::validatePolybag($this->carton, $this->form->polybag_barcode);
        } elseif (session()->get('carton.type') === 'RATIO' || session()->get('carton.type') === 'MIX') {
            if ($this->polybagCompleted) {
                $validate = CartonBoxFacade::validatePolybag($this->carton, $this->form->tag_barcode, $this->form->polybag_barcode, $this->polybagCompleted);
                if (session()->get('carton.type') === 'MIX') {
                    if ($validate === 'updated' && $this->carton->polybags->count() !== $this->carton->quantity) {
                        $this->form->reset();
                        $this->polybagCompleted = false;
                    }
                    return redirect(route('accuracy.validation.polybag', ['carton' => $this->carton->id]));
                }
                $this->form->reset();
                return redirect(route('accuracy.validation.polybag', ['carton' => $this->carton->id]));
            }
            $validate = CartonBoxFacade::validatePolybag($this->carton, $this->form->tag_barcode, null, $this->polybagCompleted);
        }
        // else if (session()->get('carton.type') === 'MIX') {
        //     if ($this->polybagCompleted) {
        //         $validate = CartonBoxFacade::validatePolybag($this->carton, $this->form->tag_barcode, $this->form->polybag_barcode, $this->polybagCompleted);
        //         $this->form->reset();
        //         return redirect(route('accuracy.validation.polybag', ['carton' => $this->carton->id]));
        //     }
        //     $validate = CartonBoxFacade::validatePolybag($this->carton, $this->form->tag_barcode, null, $this->polybagCompleted);
        //     $tags = $this->tags->where('tag', $this->form->tag_barcode);
        //     $attribute = collect(session()->get('carton.attributes'))->where('tag', $this->form->tag_barcode)->first();

        //     if ($tags->count() === (int)$attribute['quantity']) {
        //         $this->polybagCompleted = true;
        //         $this->dispatch('swal', [
        //             'title' => 'All garment validated',
        //             'text' => 'Next, Please scan the polybag/carton barcode to complete.',
        //             'icon' => 'success',
        //             'allowOutsideClick' => false,
        //             'showConfirmButton' => true,

        //         ]);
        //     }
        // }



        if ($validate === 'validated') {
            $this->savePolybag();
            $this->polybags = CartonBox::find($this->carton->id)->polybags;
            $this->showToast('warning', 'Polybag Validated', 'Go for next!');
            $this->form->reset();
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
            $this->form->reset();
            $this->dispatch('swal', [
                'title' => 'Incorrect garment tag',
                'text' => 'Please check the garment may wrong size or color.',
                'icon' => 'error',
                'allowOutsideClick' => false,
                'showConfirmButton' => true,

            ]);
        } else if ($validate === 'max') {
            // if (session()->get('carton.type') === 'MIX') {
            //     $this->form->reset();
            //     $this->polybagCompleted = true;
            //     return $this->dispatch('swal', [
            //         'title' => 'All garment validated',
            //         'text' => 'Next, Please scan the polybag/carton barcode to complete.',
            //         'icon' => 'success',
            //         'allowOutsideClick' => false,
            //         'showConfirmButton' => true,

            //     ]);
            // }
            $this->form->reset();
            $this->dispatch('swal', [
                'title' => 'Maximum ratio reached',
                'text' => 'The ratio for this garment is reached maximum, please validate next ratio.',
                'icon' => 'warning',
                'allowOutsideClick' => false,
                'showConfirmButton' => true,

            ]);
        } else if ($validate === 'saved') {
            $cartonBox = $this->carton;
            $this->tags =  Tag::whereHas('attributable', function (Builder $a) use ($cartonBox) {
                $a->where('carton_box_id', $cartonBox->id);
            })->with('attributable')->whereNull('taggable_id')->get();

            if (session()->get('carton.type') === 'MIX') {

                if ($this->tags->count() === (int)$this->tags->first()->attributable->quantity) {
                    $this->polybagCompleted = true;
                    $this->dispatch('swal', [
                        'title' => 'All garment validated',
                        'text' => 'Next, Please scan the polybag/carton barcode to complete.',
                        'icon' => 'success',
                        'allowOutsideClick' => false,
                        'showConfirmButton' => true,

                    ]);
                }
            }
            if (session()->get('carton.type') === 'RATIO') {

                if ($this->tags->count() === (int)session()->get('carton.total_attributes')) {
                    $this->polybagCompleted = true;
                    $this->dispatch('swal', [
                        'title' => 'All garment validated',
                        'text' => 'Next, Please scan the polybag/carton barcode to complete.',
                        'icon' => 'success',
                        'allowOutsideClick' => false,
                        'showConfirmButton' => true,

                    ]);
                }
            }

            $this->form->reset();
            $this->showToast('warning', 'Garment Validated', 'Go for next!');
        }
    }
}
