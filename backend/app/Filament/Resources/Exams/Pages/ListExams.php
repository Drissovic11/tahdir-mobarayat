<?php
namespace App\Filament\Resources\Exams\Pages;
use App\Filament\Resources\Exams\ExamResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;
class ListExams extends ListRecords {
 protected static string $resource = ExamResource::class;
 protected function getHeaderActions(): array { return [CreateAction::make()]; }
}

