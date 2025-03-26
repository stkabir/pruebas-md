<?php

use App\Models\Category;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\Attributes\Validate;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

new class extends Component {
    #[Validate('required|string|max:30|min:3', as: 'Nombre')]
    public string $name = '';

    public $id = null;

    public $categories = [];

    public function mount(): void {
        $this->categories = Category::all();
    }

    public function updateOrCreate(): void {
        $this->validate();
        Category::updateOrCreate(['id' => $this->id], [
            'name' => $this->name,
        ]);
        $this->reset();
        $this->categories = Category::all();
    }

    public function edit(int $id): void {
        $category = Category::find($id);
        $this->id = $category->id;
        $this->name = $category->name;
    }

    public function delete($id): void {
        LivewireAlert::title('¿Deseas eliminar la categoría?')
        ->warning()
        ->withConfirmButton('Eliminar')
        ->withCancelButton('Cancelar')
        ->onConfirm('deleteCategory', ['id' => $id])
        ->show();
    }

    public function deleteCategory($id): void {
        $category = Category::destroy($id);
        // $category->delete();
        $this->categories = Category::all();
    }

}; ?>

<div class="flex">
    <div class="w-1/2">
        <table style="width: 100%" class="table-auto">
            <thead>
              <tr class="text-left">
                <th>Categoría</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($categories as $row)
                <tr>
                    <td>{{ $row->name }}</td>
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
                :label="'Categoría'"
                type="text"
                required
                autofocus
                autocomplete="name"
                :placeholder="'Nombre de la categoría'"
            />
            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full">
                    Guardar categoría
                </flux:button>
            </div>
        </form>
    </div>
</div>