<?php
namespace App\Filament\Resources\Specialties\Pages;
use App\Filament\Resources\Specialties\SpecialtyResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;
class ListSpecialties extends ListRecords {
 protected static string $resource = SpecialtyResource::class;
 protected function getHeaderActions(): array { return [CreateAction::make()]; }
}

