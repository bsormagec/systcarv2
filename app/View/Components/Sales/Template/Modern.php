<?php

namespace App\View\Components\Sales\Template;
use Illuminate\View\Component;

class Modern extends Component
{
    public $invoice;
    public $montoliteral;

    public function __construct($invoice,$montoliteral){
        $this->invoice = $invoice;
        $this->montoliteral = $montoliteral;
    }
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.sales.template.modern');
    }
}
