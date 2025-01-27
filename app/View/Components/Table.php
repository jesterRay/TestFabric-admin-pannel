<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Table extends Component
{
    /**
     * Create a new component instance.
     */
    public $thead;
    public $createLink;
    public $createLinkText;
    public $columns;
    public $route;
    public $title;

    public function __construct($title,$createLink = '', $thead = [], $columns = [], $route = '', $createLinkText = '' )
    {

        $this->title = $title;
        $this->createLink = $createLink;
        $this->createLinkText = $createLinkText;
        $this->thead = $thead;
        $this->columns = $columns;
        $this->route = $route;
        
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.table');
    }
}
