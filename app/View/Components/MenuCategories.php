<?php

namespace App\View\Components;

use App\Models\Category;
use Illuminate\View\Component;
use Illuminate\Database\Eloquent\Collection;

class MenuCategories extends Component
{
    public Collection $categories;

    /**
     * Create a new component instance.
     *
     * @param \App\Models\Category $category
     *
     * @return void
     */
    public function __construct(private Category $category)
    {
        $this->categories = $category->mustBeMenu()->oldest('order')->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.menu-categories');
    }
}
