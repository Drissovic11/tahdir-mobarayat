<?php
namespace App\Filament\Resources\Attempts;
use App\Models\Attempt;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
class AttemptResource extends Resource {
 protected static ?string $model=Attempt::class;
 protected static ?string $navigationLabel='Résultats';
 public static function canCreate(): bool { return false; }
 public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool { return false; }
 public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool { return false; }
 public static function table(Table $table): Table { return $table->columns([
  TextColumn::make('id')->label('Tentative')->limit(8),TextColumn::make('exam.title')->label('Examen')->searchable(),
  TextColumn::make('score')->label('Bonnes réponses')->placeholder('En cours'),
  TextColumn::make('created_at')->label('Début')->dateTime()->sortable(),
  TextColumn::make('submitted_at')->label('Remise')->dateTime()->placeholder('En cours'),
 ])->defaultSort('created_at','desc'); }
 public static function getPages(): array { return ['index'=>Pages\ListAttempts::route('/')]; }
}
