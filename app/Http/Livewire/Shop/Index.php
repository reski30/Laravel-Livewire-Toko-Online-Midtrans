<?php

namespace App\Http\Livewire\Shop;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use App\Facades\Cart;

class Index extends Component
{
    use WithPagination;

    public $search;

    protected $updateQueryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.shop.index',[
            'products' => $this->search === null ?
                Product::latest()->paginate(8) :
                Product::latest()->where('title', 'like', '%'. $this->search . '%', )->paginate(8)
        ])
        ->extends('layouts.app')
        ->section('content');
    }

    public function addToCart($productId)
    {
        $product = Product::find($productId);
        Cart::add($product);

        $this->emit('addToCart');

        // dd(Cart::get()['products']);
    }
}
