<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Rule;
use Livewire\Form;

class ValidateForm extends Form
{
    #[Rule('required')]
    public $tag_barcode = '';
    #[Rule('required')]
    public $polybag_barcode = '';
}
