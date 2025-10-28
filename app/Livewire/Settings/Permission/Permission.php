<?php

namespace App\Livewire\Settings\Permission;

use App\Models\Permission as ModelsPermission;
use App\Models\PermissionGroup;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Permission')]
class Permission extends Component implements HasSchemas
{
    use InteractsWithSchemas;
    public $selectedGroupId;
    public $selectedPermissionId;

    public ?array $data = [];
    
    public function mount(): void
    {
        $this->form->fill();
    }
    public function render()
    {
        return view('livewire.settings.permission.permission');
    }
    #[Computed] #[On('refresh-permission-group')]
    public function permissionGroup(){
        return PermissionGroup::query()->with('permissions')->get()->groupBy('category');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('permission_group_id')
                        ->relationship('permissionGroup','name')
                        ->label('Permission Group')
                        ->required(),
                TextInput::make('name')
                    ->placeholder('Enter permission name')
                    ->maxLength(255)
                    ->required(),
                TextInput::make('description')
                    ->placeholder('Enter permission description')
                    ->maxLength(255)
                    ->required(),
            ])
            ->model(ModelsPermission::class)
            ->statePath('data');
    }
    public function create(){
        $data = $this->form->getState();
        ModelsPermission::query()->create([
            'permission_group_id' => $data['permission_group_id'],
            'name' => $data['name'],
            'description' => $data['description'],
            'guard_name' => 'web'
        ]);
        Notification::make()
            ->title(__('Saved successfully'))
            ->success()
            ->send();
        $this->dispatch('refresh-permission-group');
        $this->dispatch('close-modal',id:'addPermission');
    }
    public function update()
    {
        $data = $this->form->getState();
        ModelsPermission::query()->where('id',$this->selectedPermissionId)->update([
            'permission_group_id' => $data['permission_group_id'],
            'name' => $data['name'],
            'description' => $data['description'],
            'guard_name' => 'web'
        ]);
        Notification::make()
            ->title(__('Updated successfully'))
            ->success()
            ->send();
        $this->dispatch('refresh-permission-group');
        $this->dispatch('close-modal',id:'editPermission');
    }
    public function delete()
    {
        ModelsPermission::query()->where('id',$this->selectedPermissionId)->delete();
        Notification::make()
            ->title(__('Deleted successfully'))
            ->success()
            ->send();
        $this->dispatch('refresh-permission-group');
        $this->dispatch('close-modal',id:'confirmDelete');
    }
    #[On('open-modal')]
    public function openModal($id,$data)
    {
        if($id == 'addPermission'){
            $this->selectedGroupId = $data['group_id'];
            $this->form->fill([
                'permission_group_id' => $this->selectedGroupId
            ]);
        }
        if($id == 'editPermission'){
            $this->selectedPermissionId = $data['id'];
            $this->form->fill(ModelsPermission::query()->where('id',$this->selectedPermissionId)->first()->toArray());
        }
        if($id == 'confirmDelete'){
            $this->selectedPermissionId = $data['id'];
        }
    }
}
