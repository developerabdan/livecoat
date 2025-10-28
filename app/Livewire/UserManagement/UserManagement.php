<?php

namespace App\Livewire\UserManagement;

use App\Models\Role;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section as FormSection;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Users')]
class UserManagement extends Component implements HasTable, HasForms, HasActions
{
    use InteractsWithTable, InteractsWithForms, InteractsWithActions;

    public function table(Table $table): Table
    {
        return $table
            ->query(User::query())
            ->columns([
                ImageColumn::make('avatar')
                    ->visibility('public')
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-avatar.svg')),
                
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Email copied')
                    ->icon('heroicon-o-envelope'),
                
                TextColumn::make('roles.name')
                    ->badge()
                    ->separator(',')
                    ->colors([
                        'primary',
                        'success' => 'Admin',
                    ]),
                
                TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->authorize('Create Users')
                    ->schema([
                        FormSection::make('User Information')
                            ->schema([
                                FileUpload::make('avatar')
                                    ->image()
                                    ->avatar()
                                    ->imageEditor()
                                    ->disk('public')
                                    ->directory('avatars')
                                    ->visibility('public')
                                    ->maxSize(1024)
                                    ->columnSpanFull(),
                                
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                
                                TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->unique(User::class, 'email')
                                    ->maxLength(255),
                            ])
                            ->columns(2),
                        
                        FormSection::make('Password')
                            ->schema([
                                TextInput::make('password')
                                    ->password()
                                    ->required()
                                    ->minLength(8)
                                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                    ->same('password_confirmation'),
                                
                                TextInput::make('password_confirmation')
                                    ->password()
                                    ->required()
                                    ->minLength(8)
                                    ->dehydrated(false),
                            ])
                            ->columns(2),
                        
                        FormSection::make('Roles & Permissions')
                            ->schema([
                                Select::make('roles')
                                    ->multiple()
                                    ->relationship('roles', 'name')
                                    ->preload()
                                    ->searchable()
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->using(function (array $data) {
                        $user = User::create([
                            'name' => $data['name'],
                            'email' => $data['email'],
                            'password' => $data['password'],
                            'avatar' => $data['avatar'] ?? null,
                        ]);
                        
                        if (isset($data['roles'])) {
                            $user->syncRoles($data['roles']);
                        }
                        
                        return $user;
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('User created')
                            ->body('The user has been created successfully.')
                    ),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->authorize('Edit Users')
                        ->schema([
                            FormSection::make('User Information')
                                ->schema([
                                    FileUpload::make('avatar')
                                        ->image()
                                        ->avatar()
                                        ->imageEditor()
                                        ->disk('public')
                                        ->directory('avatars')
                                        ->visibility('public')
                                        ->maxSize(1024)
                                        ->columnSpanFull(),
                                    
                                    TextInput::make('name')
                                        ->required()
                                        ->maxLength(255),
                                    
                                    TextInput::make('email')
                                        ->email()
                                        ->required()
                                        ->unique(User::class, 'email', ignoreRecord: true)
                                        ->maxLength(255),
                                ])
                                ->columns(2),
                            
                            FormSection::make('Roles & Permissions')
                                ->schema([
                                    Select::make('roles')
                                        ->multiple()
                                        ->relationship('roles', 'name')
                                        ->preload()
                                        ->searchable()
                                        ->columnSpanFull(),
                                ]),
                        ])
                        ->using(function (User $record, array $data) {
                            $record->update([
                                'name' => $data['name'],
                                'email' => $data['email'],
                                'avatar' => $data['avatar'] ?? $record->avatar,
                            ]);
                            
                            if (isset($data['roles'])) {
                                $record->syncRoles($data['roles']);
                            }
                            
                            return $record;
                        })
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('User updated')
                                ->body('The user has been updated successfully.')
                        ),
                    
                    Action::make('changePassword')
                        ->label('Change Password')
                        ->icon('heroicon-o-key')
                        ->authorize('Edit Users')
                        ->schema([
                            TextInput::make('new_password')
                                ->label('New Password')
                                ->password()
                                ->required()
                                ->minLength(8)
                                ->same('new_password_confirmation'),
                            
                            TextInput::make('new_password_confirmation')
                                ->label('Confirm New Password')
                                ->password()
                                ->required()
                                ->minLength(8)
                                ->dehydrated(false),
                        ])
                        ->action(function (User $record, array $data) {
                            $record->update([
                                'password' => Hash::make($data['new_password']),
                            ]);
                            
                            Notification::make()
                                ->success()
                                ->title('Password changed')
                                ->body('The password has been changed successfully.')
                                ->send();
                        })
                        ->modalWidth('md'),
                    
                    DeleteAction::make()
                        ->requiresConfirmation()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('User deleted')
                                ->body('The user has been deleted successfully.')
                        ),
                ])
                ->icon('heroicon-o-ellipsis-vertical')
                ->button()
                ->label('Actions'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Users deleted')
                                ->body('The selected users have been deleted successfully.')
                        ),
                    
                    BulkAction::make('assignRole')
                        ->label('Assign Roles')
                        ->icon('heroicon-o-user-group')
                        ->authorize('Edit Users')
                        ->schema([
                            Select::make('roles')
                                ->label('Roles')
                                ->multiple()
                                ->options(Role::all()->pluck('name', 'id'))
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            foreach ($records as $record) {
                                $record->syncRoles($data['roles']);
                            }
                            
                            Notification::make()
                                ->success()
                                ->title('Roles assigned')
                                ->body('Roles have been assigned to ' . $records->count() . ' users.')
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->poll('30s');
    }

    public function render()
    {
        return view('livewire.user-management.user-management');
    }
}
