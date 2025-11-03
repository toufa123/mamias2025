<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\ManageUsers;
use App\Models\User;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static string | UnitEnum | null $navigationGroup = 'User Management';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Users';
    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->hasRole('super_admin');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //TextInput::make('avatar_url')
                //    ->url(),
                Grid::make(4)->schema([
                    Select::make('title')
                        ->autofocus()
                        ->searchable()
                        ->loadingMessage('Loading Title...')
                        ->preload()
                        ->label('Title')
                        ->options([
                            // Academic Titles
                            'Prof.' => 'Prof.',
                            'Assoc. Prof.' => 'Assoc. Prof.',
                            'Asst. Prof.' => 'Asst. Prof.',
                            'Dr.' => 'Dr.',
                            'Ph.D.' => 'Ph.D.',
                            'M.Sc.' => 'M.Sc.',
                            'B.Sc.' => 'B.Sc.',
                            // Professional Titles
                            'Mr.' => 'Mr.',
                            'Ms.' => 'Ms.',
                            'Mrs.' => 'Mrs.',
                        ])
                        ->columnSpan(1),
                    TextInput::make('first_name')->columnSpan(1),
                    TextInput::make('last_name')->columnSpan(1),
                    TextInput::make('name')->required()->columnSpan(1),
                ]),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->tel(),
                //DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required(),
                TextInput::make('country'),
                Select::make('roles')
                    ->label('Roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
                Textarea::make('bio')
                    ->columnSpanFull(),
                TextInput::make('taxonomic_area')
                    ->label('Taxonomic Area'),
                TextInput::make('geographic_area')
                    ->label('Geographic Area'),
                
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('email_verified_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('first_name')
                    ->placeholder('-'),
                TextEntry::make('last_name')
                    ->placeholder('-'),
                TextEntry::make('phone')
                    ->placeholder('-'),
                TextEntry::make('bio')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('country')
                    ->placeholder('-'),
                TextEntry::make('title')
                    ->placeholder('-'),
                TextEntry::make('taxonomic_area')
                    ->placeholder('-'),
                TextEntry::make('geographic_area')
                    ->placeholder('-'),
                TextEntry::make('avatar_url')
                    ->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Users')
            ->columns([
                ImageColumn::make('avatar')
                    ->label('Avatar')
                    ->defaultImageUrl(fn ($record): string => $record?->getFilamentAvatarUrl() ?: url('/img/avatar.png'))
                    ->circular()
                    ->size(36),
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable(),
                TextColumn::make('first_name')
                    ->label('First Name')
                    ->searchable(),
                TextColumn::make('last_name')
                    ->label('Last Name')
                    ->searchable(),
//                TextColumn::make('name')
//                    ->searchable(),
                TextColumn::make('email')
                    
                    ->label('Email address')
                    ->searchable(),
//                TextColumn::make('email_verified_at')
//                    ->dateTime()
//                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
//                TextColumn::make('updated_at')
//                    ->dateTime()
//                    ->sortable()
//                    ->toggleable(isToggledHiddenByDefault: true),

//                TextColumn::make('phone')
//                    ->searchable(),
                TextColumn::make('country')
                    ->label('Country')
                    ->html()
                    ->formatStateUsing(function ($state) {
                        $baseStyle = 'vertical-align:middle;margin-right:6px;border-radius:2px;';
                        if (! $state) {
                            $unknown = url('/img/flag.png');
                            $img = "<img src=\"{$unknown}\" alt=\"unknown flag\" width=\"20\" height=\"15\" style=\"{$baseStyle}\">";
                            return "{$img}<span style=\"vertical-align:middle;\">N/A</span>";
                        }
                        $code = strtolower(trim($state));
                        $localPath = public_path("img/flags/{$code}.png");
                        $src = file_exists($localPath)
                            ? url("img/flags/{$code}.png")
                            : "https://flagcdn.com/w20/{$code}.png";
                        $img = "<img src=\"{$src}\" alt=\"{$code} flag\" width=\"20\" height=\"15\" style=\"{$baseStyle}\">";
                        return "{$img}<span style=\"vertical-align:middle;\">".strtoupper($code)."</span>";
                    })
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->searchable(),
                TextColumn::make('taxonomic_area')
                    ->Label('Taxonomic Area')
                    ->searchable(),
                TextColumn::make('geographic_area')
                    ->Label('Geographic Area')
                    ->searchable(),
                
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageUsers::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
