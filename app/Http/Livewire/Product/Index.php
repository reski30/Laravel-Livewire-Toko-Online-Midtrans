<?php

namespace App\Http\Livewire\Product;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\paginationTheme;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $paginate = 10;

    public $search;

    public $formVisible;

    public $formUpdate = false;

    protected $listeners = [
        'formClose' => 'formCloseHandler',
        'productStore' => 'productStoreHandler',
        'productUpdated' => 'productUpdatedHandler'
    ];

    protected $updateQueryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.product.index', [
            'products' => $this->search === null ?
                Product::latest()->paginate($this->paginate) :
                Product::latest()->where('title', 'like', '%'. $this->search .'%')->paginate($this->paginate)            
        ])
        ->extends('layouts.app')
        ->section('content');
    }

    public function formCloseHandler()
    {
        $this->formVisible = false;
    }

    public function productStoreHandler()
    {
        $this->formVisible = false;
        session()->flash('message', 'Your Product has been stored successfully');
    }

    public function editProduct($productId)
    {
        $this->formUpdate = true;
        $this->formVisible = true;
        $product = Product::find($productId);
        $this->emit('editProduct', $product);
    }

    public function productUpdatedHandler()
    {
        $this->formVisible = false;
        session()->flash('message', 'Your product was updated');
    }

    public function deleteProduct($productId)
    {
        $product = Product::find($productId);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        session()->flash('message', 'Your Product Deleted Succsessfully !');
    }
}

