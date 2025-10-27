<?php

namespace App\Livewire\Settings\PermissionGroup;

use App\Models\PermissionGroup as ModelsPermissionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
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
                                TextInput::make('name')
                                        ->required(),
                                TextInput::make('description'),
                            ])
                            ->action(function (ModelsPermissionGroup $record, array $data): void {
                                $record->fill($data);
                                $record->save();
                            })
            ])
            ->columns([
                TextColumn::make('name')
                        ->searchable(),
                TextColumn::make('description'),
            ])
            ->filters([
                // ...
            ])
            ->recordActions([
                // ...
            ]);
    }
}
