<?php

namespace App\Http\Livewire\Product;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Contracts\Service\Attribute\Required;

class Create extends Component
{
    use WithFileUploads;

    public $title;
    public $description;
    public $price;
    public $image;

    public function render()
    {
        return view('livewire.product.create')
        ->extends('layouts.app')
        ->section('content');
    }

    public function store()
    {
        $this->validate([
            'title' => "required|min:3",
            'description' => 'required|max:180',
            'price' => 'required|numeric',
            'image' => 'image|max:1024'
        ]);

        $imageName = '';

        if ($this->image) {
            $imageName = \Str::slug($this->title,'-')
                . '-'
                . uniqid()
                . '.' . $this->image->getClientOriginalExtension();

            $this->image->storeAs('public', $imageName, 'local');
        }

        Product::create([
            "title" => $this->title,
            "description" => $this->description,
            "price" => $this->price,
            "image" => $imageName
        ]);

        $this->emit('productStore');
    }
}
