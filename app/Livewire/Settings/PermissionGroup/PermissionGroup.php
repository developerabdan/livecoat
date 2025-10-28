<?php

namespace App\Livewire\Settings\PermissionGroup;

use App\Models\PermissionGroup as ModelsPermissionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Permission Group')]
class PermissionGroup extends Component implements HasTable, HasSchemas, HasActions
{
    use InteractsWithTable, InteractsWithSchemas, InteractsWithActions;
    
    public function render()
    {
        return view('livewire.settings.permission-group.permission-group');
    }
    public function table(Table $table): Table
    {
        return $table
            ->query(ModelsPermissionGroup::query())
            ->toolbarActions([
                CreateAction::make()
                            ->schema([
                                TextInput::make('category')
                                        ->datalist(function(){
                                            return ModelsPermissionGroup::query()->pluck('category')->unique()->toArray();
                                        })
                                        ->autocomplete(false)
                                        ->placeholder(__('Category')),
                                TextInput::make('name')
                                        ->placeholder(__('Name'))
                                        ->required(),
                                TextInput::make('icon')
                                        ->placeholder(__('Icon')),
                                TextInput::make('description')
                                        ->placeholder(__('Description')),
                            ])
                            ->successNotificationTitle(__('Permission Group Created'))
                            ->action(function (array $data): void {
                                ModelsPermissionGroup::query()->create([
                                    'category' => Str::slug($data['category']),
                                    'name' => $data['name'],
                                    'slug' => Str::slug($data['name']),
                                    'icon' => $data['icon'],
                                    'description' => $data['description'],
                                ]);
                            })
            ])
            ->columns([
                TextColumn::make('category')
                        ->searchable(),
                TextColumn::make('name')
                        ->searchable(),
                TextColumn::make('icon'),
                TextColumn::make('description'),
            ])
            ->filters([
                // ...
            ])
            ->recordActions([
                EditAction::make()
                            ->schema([
                                TextInput::make('category')
                                        ->placeholder(__('Category')),
                                TextInput::make('name')
                                        ->placeholder(__('Name'))
                                        ->required(),
                                TextInput::make('icon')
                                    ->placeholder(__('Icon')),
                                TextInput::make('description')
                                        ->placeholder(__('Description')),
                            ])
                            ->successNotificationTitle(__('Permission Group Updated'))
                            ->action(function (array $data,$record): void {
                                $record->update([
                                    'category' => Str::slug($data['category']),
                                    'name' => $data['name'],
                                    'slug' => Str::slug($data['name']),
                                    'icon' => $data['icon'],
                                    'description' => $data['description'],
                                ]);
                            }),
                DeleteAction::make()
                            ->successNotificationTitle(__('Permission Group Deleted'))
                            ->action(function (array $data,$record): void {
                                $record->delete();
                            })
            ], position: RecordActionsPosition::BeforeColumns);
    }
}
