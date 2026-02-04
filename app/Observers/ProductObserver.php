<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ProductObserver
{
    protected function clear(Product $product): void
    {
        Cache::forget('user_' . $product->user_id. '_products');
        Cache::forget('trashed_'.$product->user_id.'_products');
    }
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        $this->clear($product);
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        $this->clear($product);
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        $this->clear($product);
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        $this->clear($product);
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        $this->clear($product);
    }
}
