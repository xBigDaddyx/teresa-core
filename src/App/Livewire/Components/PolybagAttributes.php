<?php

namespace App\Livewire\Components;

<<<<<<< Updated upstream:src/App/Livewire/Components/PolybagAttributes.php
=======
use Domain\Accuracies\Models\CartonBox;
use Domain\Accuracies\Models\Tag;
>>>>>>> Stashed changes:src/App/Livewire/Components/PolybagStats.php
use Livewire\Component;
//use Teresa\CartonBoxGuard\Models\CartonBox;
use Livewire\Attributes\Reactive;

class PolybagAttributes extends Component
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
<<<<<<< Updated upstream:src/App/Livewire/Components/PolybagAttributes.php
        return view('livewire.components.polybag-attributes');
=======
        if (session()->get('carton.type') === 'RATIO' || session()->get('carton.type') === 'MIX') {
            return view('livewire.components.polybag-stats', ['count' => $this->polybags->count(), 'tags_count' => $this->tags->count()]);
        }
        return view('livewire.components.polybag-stats', ['count' => $this->polybags->count()]);
>>>>>>> Stashed changes:src/App/Livewire/Components/PolybagStats.php
    }
}
