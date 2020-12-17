<?php

namespace App\Http\Livewire\Shop;

use App\Facades\Cart;
use Livewire\Component;

class Cartnav extends Component
{
    public $cartTotal = 0;

    public function mount()
    {
        $this->updateCartTotal();
    }

    protected $listeners = [
        'addToCart' => 'updateCartTotal',
        'removeFromCart' => 'updateCartTotal',
        'cartClear' => 'updateCartTotal',
    ];

    public function render()
    {
        return view('livewire.shop.cartnav')
        ->extends('layouts.app')
        ->section('content');
    }

    public function updateCartTotal()
    {
        $this->cartTotal = count(Cart::get()['products']);
    }
}
