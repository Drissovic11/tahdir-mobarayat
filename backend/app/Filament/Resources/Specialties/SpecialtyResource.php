<?php
namespace App\Filament\Resources\Specialties;
use App\Models\Specialty;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
class SpecialtyResource extends Resource {
 protected static ?string $model = Specialty::class;
 protected static ?string $recordTitleAttribute = 'name';
 protected static ?string $navigationLabel = 'Spécialités';
 public static function form(Schema $schema): Schema { return $schema->components([
  Select::make('organization_id')->label('Organisme')->relationship('organization','name')->searchable()->preload()->required(),
  TextInput::make('name')->label('Nom')->required()->maxLength(255),
  TextInput::make('slug')->required()->regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/')->maxLength(80)
   ->unique(ignoreRecord:true,modifyRuleUsing:fn(\Illuminate\Validation\Rules\Unique $rule,\Filament\Schemas\Components\Utilities\Get $get)=>$rule->where('organization_id',$get('organization_id'))),
  Toggle::make('is_active')->label('Active')->default(false),
 ]); }
 public static function table(Table $table): Table { return $table->columns([TextColumn::make('name')->searchable()->sortable(), TextColumn::make('slug'), IconColumn::make('is_active')->boolean()])->recordActions([EditAction::make()]); }
 public static function getPages(): array { return [
 'index' => Pages\ListSpecialties::route('/'),
 'create' => Pages\CreateSpecialty::route('/create'),
 'edit' => Pages\EditSpecialty::route('/{record}/edit'),
 ]; }
}

