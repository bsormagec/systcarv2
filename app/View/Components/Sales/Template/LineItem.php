<?php

namespace App\View\Components\Sales\Template;

use Illuminate\View\Component;

class LineItem extends Component
{
    public $item;

    public function __construct($item){
        $this->item = $item;
    }
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.sales.template.line-item');
    }
}
