<?php

namespace App\Livewire\Components;

use Domain\Accuracies\Models\CartonBox;
use Domain\Accuracies\Models\Tag;
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
    #[Reactive]
    public $tags;

    public function render()
    {
        if (session()->get('carton.type') === 'RATIO' || session()->get('carton.type') === 'MIX') {
            return view('livewire.components.polybag-stats', ['count' => $this->polybags->count(), 'tags_count' => $this->tags->count()]);
        }
        return view('livewire.components.polybag-stats', ['count' => $this->polybags->count()]);
    }
}
