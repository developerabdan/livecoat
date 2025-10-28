<?php

namespace App\Livewire\Settings\Role;

use App\Models\Permission;
use App\Models\Role as ModelsRole;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Role')]
class Role extends Component implements HasTable, HasSchemas, HasActions
{
    use InteractsWithTable, InteractsWithSchemas, InteractsWithActions;
    public function render()
    {
        return view('livewire.settings.role.role');
    }
    public function table(Table $table): Table
    {
        return $table
            ->query(ModelsRole::query())
            ->toolbarActions([
                CreateAction::make()
                            ->schema([
                                TextInput::make('name')
                                        ->placeholder(__('Name'))
                                        ->required(),
                                TextInput::make('guard')
                                        ->default('web'),
                                CheckboxList::make('permission_id')
                                    ->label('Select Permissions')
                                    ->searchable()
                                    ->bulkToggleable()
                                    ->required()
                                    ->options(
                                        Permission::query()->pluck('name', 'id')->toArray()
                                    )
                                    ->searchPrompt('Search for a permission')
                                    ->descriptions(function (){
                                        return Permission::query()->pluck('description','id')->toArray();
                                    })
                            ])
                            ->successNotificationTitle(__('Role Created'))
                            ->action(function (array $data): void {
                                DB::transaction(function () use($data){
                                    $role = ModelsRole::query()->create([
                                        'name' => $data['name'],
                                        'guard_name' => 'web',
                                    ]);
                                    
                                    // Sync permissions to the role
                                    if (isset($data['permission_id'])) {
                                        $role->syncPermissions($data['permission_id']);
                                    }
                                });
                            })
            ])
            ->columns([
                TextColumn::make('name')
                        ->searchable(),
                TextColumn::make('guard_name')
                        ->searchable(),
                TextColumn::make('permissions_count')
                        ->counts('permissions')
                        ->label('Permissions')
                        ->badge(),
            ])
            ->filters([
                // ...
            ])
            ->recordActions([
                EditAction::make()
                            ->fillForm(function (ModelsRole $record): array {
                                return [
                                    'name' => $record->name,
                                    'icon' => $record->icon,
                                    'permission_id' => $record->permissions->pluck('id')->toArray(),
                                ];
                            })
                            ->stickyModalFooter()
                            ->stickyModalHeader()
                            ->schema([
                                TextInput::make('name')
                                        ->placeholder(__('Name'))
                                        ->required(),
                                CheckboxList::make('permission_id')
                                    ->label('Select Permissions')
                                    ->searchable()
                                    ->bulkToggleable()
                                    ->required()
                                    ->options(
                                        Permission::query()->pluck('name', 'id')->toArray()
                                    )
                                    ->searchPrompt('Search for a permission')
                                    ->descriptions(function (){
                                        return Permission::query()->pluck('description','id')->toArray();
                                    })
                            ])
                            ->successNotificationTitle(__('Role Updated'))
                            ->action(function (array $data, ModelsRole $record): void {
                                $record->update([
                                    'name' => $data['name'],
                                ]);
                                
                                // Sync permissions to the role
                                if (isset($data['permission_id'])) {
                                    $record->syncPermissions($data['permission_id']);
                                }
                            }),
                DeleteAction::make()
                            ->successNotificationTitle(__('Role Deleted'))
                            ->action(function (array $data,$record): void {
                                $record->delete();
                            })
            ], position: RecordActionsPosition::BeforeColumns);
    }
}
