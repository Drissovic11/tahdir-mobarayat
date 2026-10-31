<?php
namespace App\Filament\Resources\Exams;

use App\Models\Exam;
use App\Models\Question;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\CheckboxList;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;

class ExamResource extends Resource
{
    protected static ?string $model = Exam::class;
    protected static ?string $navigationLabel = 'Examens';
    protected static ?string $modelLabel = 'examen';
    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')->label('Titre')->required()->maxLength(255),
            Select::make('specialty_id')->label('Spécialité')->relationship('specialty','name')->searchable()->preload()->required()->live()
                ->afterStateUpdated(fn (Set $set) => $set('questions', [])),
            TextInput::make('duration_minutes')->label('Durée en minutes')->numeric()->integer()->minValue(1)->maxValue(240)->required()->default(30),
            Toggle::make('is_published')->label('Publié')->default(false),
            CheckboxList::make('questions')->label('Questions de cet examen')
                ->relationship('questions','prompt',modifyQueryUsing: fn (Builder $query, Get $get) => $query->where('specialty_id',$get('specialty_id')))
                ->searchable()->required()->columnSpanFull()
                ->rules([fn (Get $get) => function (string $attribute, mixed $value, \Closure $fail) use ($get) {
                    if (!is_array($value) || count($value)>200 || Question::where('specialty_id',$get('specialty_id'))->whereIn('id',$value)->count() !== count($value)) {
                        $fail('Choisissez entre 1 et 200 questions de la spécialité sélectionnée.');
                    }
                    if ($get('is_published') && Question::whereIn('id',$value ?? [])->where('is_active',false)->exists()) {
                        $fail('Toutes les questions doivent être actives avant publication.');
                    }
                }]),
        ]);
    }
    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('title')->label('Titre')->searchable(), TextColumn::make('specialty.name')->label('Spécialité'),
            TextColumn::make('duration_minutes')->label('Minutes'), TextColumn::make('questions_count')->counts('questions')->label('Questions'),
            IconColumn::make('is_published')->label('Publié')->boolean(),
        ])->recordActions([EditAction::make()]);
    }
    public static function getPages(): array
    {
        return ['index'=>Pages\ListExams::route('/'),'create'=>Pages\CreateExam::route('/create'),'edit'=>Pages\EditExam::route('/{record}/edit')];
    }
}
