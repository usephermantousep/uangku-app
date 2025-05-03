<?php

namespace App\Observers;

use App\Models\Category;

class CategoryObserver
{
    /**
     * Handle the Category "created" event.
     */
    public function created(Category $category): void
    {
        if (auth()->user()) {
            $category->family()->associate(auth()->user()->family);
        }
    }
}
