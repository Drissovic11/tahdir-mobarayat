<?php
namespace App\Filament\Resources\Organizations;
use App\Models\Organization;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
class OrganizationResource extends Resource {
 protected static ?string $model = Organization::class;
 protected static ?string $recordTitleAttribute = 'name';
 public static function form(Schema $schema): Schema { return $schema->components([TextInput::make('name')->required()->maxLength(255), TextInput::make('slug')->required()->regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/')->unique(ignoreRecord: true)->maxLength(80), TextInput::make('logo')->helperText('Nom du fichier dans frontend/public/images')->maxLength(255), TextInput::make('aliases')->maxLength(255), Toggle::make('is_active')->default(false)]); }
 public static function table(Table $table): Table { return $table->columns([TextColumn::make('name')->searchable()->sortable(), TextColumn::make('slug'), IconColumn::make('is_active')->boolean()])->recordActions([EditAction::make()]); }
 public static function getPages(): array { return [
 'index' => Pages\ListOrganizations::route('/'),
 'create' => Pages\CreateOrganization::route('/create'),
 'edit' => Pages\EditOrganization::route('/{record}/edit'),
 ]; }
}

