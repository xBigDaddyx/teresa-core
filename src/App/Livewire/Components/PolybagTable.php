<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Domain\Accuracies\Models\CartonBox;

class PolybagTable extends Component
{

    public $carton;
    public $type;

    public array $headers;

    public function mount($carton)
    {
        $this->carton = CartonBox::find($carton->id);
        $this->type = $this->carton->type;

        $this->headers = [
            ['key' => 'polybag_code', 'label' => 'Polybag Code'],
            ['key' => 'cartonBox.box_code', 'label' => 'Carton Box Code'],
        ];
    }
    public function render()
    {

        return view('livewire.components.polybag-table');
    }
}
