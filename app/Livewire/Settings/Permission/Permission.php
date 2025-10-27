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

    public ?array $data = [];
    
    public function mount(): void
    {
        $this->form->fill();
    }
    public function render()
    {
        return view('livewire.settings.permission.permission');
    }
    #[Computed]
    public function permissionGroup(){
        return PermissionGroup::query()->with('permissions')->get();
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
    }
    #[On('open-modal')]
    public function openModal($id,$data)
    {
        $this->selectedGroupId = $data['group_id'];
        $this->form->fill([
            'permission_group_id' => $this->selectedGroupId
        ]);
    }
}
