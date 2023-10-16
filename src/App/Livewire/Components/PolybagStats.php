<?php

namespace App\Livewire\Components;

use Domain\Accuracies\Models\CartonBox;
use Livewire\Component;
//use Teresa\CartonBoxGuard\Models\CartonBox;
use Livewire\Attributes\Reactive;

class PolybagStats extends Component
{

    public $carton;
    #[Reactive]
    public $polybags;
    #[Reactive]
    public $type;


    public function render()
    {
        return view('livewire.components.polybag-stats', ['count' => $this->polybags->count()]);
    }
}
