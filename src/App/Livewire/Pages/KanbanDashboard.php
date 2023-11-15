<?php

namespace App\Livewire\Pages;

use Domain\Kanban\Models\Wise;
use Illuminate\Pagination\Cursor;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class KanbanDashboard extends Component
{
    use WithPagination;
    public $company;
    public $wises;

    public $nextCursor;

    public $hasMorePages;
    // public $wises;

    public function mount($company)
    {
        $this->company = $company;
        $this->wises = new Collection();

        $this->loadWises();
    }
    public function loadWises()
    {
        if ($this->hasMorePages !== null  && !$this->hasMorePages) {
            return;
        }

        $this->wises = Wise::all();
    }
    public function render()
    {
        return view('livewire.pages.kanban-dashboard');
    }
}
