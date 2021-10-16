<?php

namespace App\View\Components\Sales;

use Illuminate\View\Component;

class Input extends Component
{
    public $invoice;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.sales.input');
    }
}
