<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class CategoryObserver
{
    protected function clear(Category $category): void
    {
        Cache::forget('all_categories');
    }
    /**
     * Handle the Product "created" event.
     */
    public function created(Category $category): void
    {
        $this->clear($category);
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Category $category): void
    {
        $this->clear($category);
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Category $category): void
    {
        $this->clear($category);
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Category $category): void
    {
        $this->clear($category);
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Category $category): void
    {
        $this->clear($category);
    }
}
