<?php
namespace App\Filament\Resources\Questions;

use App\Models\Question;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\EditAction;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;
    protected static ?string $navigationLabel = 'Questions';
    protected static ?string $modelLabel = 'question';
    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('specialty_id')->label('Spécialité')->relationship('specialty','name')->searchable()->preload()->required(),
            Textarea::make('prompt')->label('Énoncé')->required()->maxLength(5000)->columnSpanFull(),
            TextInput::make('options.0')->label('Réponse A')->required()->maxLength(1000),
            TextInput::make('options.1')->label('Réponse B')->required()->maxLength(1000),
            TextInput::make('options.2')->label('Réponse C')->required()->maxLength(1000),
            TextInput::make('options.3')->label('Réponse D')->required()->maxLength(1000),
            Select::make('correct_option')->label('Bonne réponse')->options([0=>'A',1=>'B',2=>'C',3=>'D'])->required(),
            Toggle::make('is_active')->label('Question active')->default(false),
            Textarea::make('explanation')->label('Explication de la correction')->required()->maxLength(10000)->columnSpanFull(),
        ]);
    }
    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('prompt')->label('Énoncé')->limit(80)->searchable(),
            TextColumn::make('specialty.name')->label('Spécialité')->searchable(),
            IconColumn::make('is_active')->label('Active')->boolean(),
        ])->filters([SelectFilter::make('specialty')->relationship('specialty','name')->searchable()->preload()])
          ->recordActions([EditAction::make()]);
    }
    public static function getPages(): array
    {
        return ['index'=>Pages\ListQuestions::route('/'),'create'=>Pages\CreateQuestion::route('/create'),'edit'=>Pages\EditQuestion::route('/{record}/edit')];
    }
}
