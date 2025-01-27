<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;

class SelectTwoDropDown extends Component
{
    /**
     * Create a new component instance.
     */

    public  $name,
            $title,
            $options,
            $placeholder,
            $multiple,
            $selected;

    public function __construct($name,
                                $title, 
                                $options, 
                                $placeholder = "Select Option", 
                                $multiple = false,
                                $selected){
        $this->name = $name;
        $this->title = $title;
        $this->options = $options;
        $this->placeholder = $placeholder;
        $this->multiple = $multiple;
        $this->selected = $selected;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select-two-drop-down');
    }
}
