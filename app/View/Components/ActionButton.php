<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ActionButton extends Component
{
    /**
     * Create a new component instance.
     */
    public $edit_link, $delete_link, $view_link;
    public function __construct($edit_link='', $delete_link='',$view_link='')
    {
        $this->edit_link = $edit_link; 
        $this->delete_link = $delete_link; 
        $this->view_link = $view_link; 
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.action-button');
    }
}
