<?php

use App\Models\Product;
use App\Models\Category;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\Attributes\Validate;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

new class extends Component {
    #[Validate('required|string|max:30|min:3', as: 'Nombre')]
    public string $name = '';

    #[Validate('required|numeric', as: 'Precio')]
    public string $price = '';

    #[Validate('required|integer', as: 'Existencia')]
    public string $stock = '';

    #[Validate('required|string|max:100|min:3', as: 'Descripción')]
    public string $description = '';

    #[Validate('required', as: 'ID Categoría')]
    public $category_id = null;

    public $id = null;

    public $products = [];
    public $categories = [];

    public function mount(): void {
        $this->products = Product::all();
        $this->categories = Category::all();
    }

    public function updateOrCreate(): void {
        $this->validate();
        Product::updateOrCreate(['id' => $this->id], [
            'name' => $this->name,
            'price' => $this->price,
            'stock' => $this->stock,
            'description' => $this->description,
            'category_id' => $this->category_id,
        ]);
        $this->reset();
        $this->products = Product::all();
    }

    public function edit(int $id): void {
        $product = Product::find($id);
        $this->id = $product->id;
        $this->name = $product->name;
        $this->price = $product->price;
        $this->stock = $product->stock;
        $this->description = $product->description;
        $this->category_id = $product->category_id;
    }

    public function delete($id): void {
        LivewireAlert::title('¿Deseas eliminar el Producto?')
        ->warning()
        ->withConfirmButton('Eliminar')
        ->withCancelButton('Cancelar')
        ->onConfirm('deleteProduct', ['id' => $id])
        ->show();
    }

    public function deleteProduct($id): void {
        $product = Product::destroy($id);
        // $product->delete();
        $this->products = Product::all();
    }

}; ?>

<div class="flex">
    <div class="w-1/2">
        <table style="width: 100%" class="table-auto">
            <thead>
              <tr class="text-left">
                <th>Nombre</th>
                <th>Precio</th>
                <th>Existencia</th>
                <th>Descripcion</th>
                <th>Categoría</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($products as $row)
                <tr>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->price }}</td>
                    <td>{{ $row->stock }}</td>
                    <td>{{ $row->description }}</td>
                    <td>{{ $row->category->name }}</td>
                    <td>
                        <flux:button icon="pencil" variant="outline" wire:click="edit({{ $row->id }})"></flux:button>
                        <flux:button icon="trash" variant="danger" wire:click="delete({{ $row->id }})"></flux:button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="w-1/2">
        <form wire:submit="updateOrCreate" class="flex flex-col gap-6">
            <!-- Name -->
            <flux:input
                wire:model.live="name"
                :label="'Producto'"
                type="text"
                required
                autofocus
                :placeholder="'Nombre del producto'"
            />
            <!-- Price -->
            <flux:input
                wire:model.live="price"
                :label="'Precio'"
                type="text"
                required
                autofocus
                :placeholder="'$$'"
            />
            <!-- Stock -->
            <flux:input
                wire:model.live="stock"
                :label="'Existencia'"
                type="text"
                required
                autofocus
                :placeholder="'0'"
            />
            <!-- Description -->
            <flux:input
                wire:model.live="description"
                :label="'Descrioción'"
                type="text"
                required
                autofocus
                :placeholder="'Descripción del producto'"
            />
            <!-- Category -->
            <select wire:model.live="category_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">Seleccione una categoría</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            {{-- <!-- image -->
            <flux:input
                wire:model.live="name"
                :label="'Producto'"
                type="text"
                required
                autofocus
                :placeholder="'Nombre del producto'"
            /> --}}
            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full">
                    Guardar producto
                </flux:button>
            </div>
        </form>
    </div>
</div>