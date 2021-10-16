<?php

namespace App\View\Components\Sales\Recibos;
use Illuminate\View\Component;

class Pago extends Component
{
    public $cuenta;
    public $montoliteral;

    public function __construct($cuenta,$montoliteral){
        $this->cuenta = $cuenta;
        $this->montoliteral = $montoliteral;
    }
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.sales.recibos.pagos');
    }
}
