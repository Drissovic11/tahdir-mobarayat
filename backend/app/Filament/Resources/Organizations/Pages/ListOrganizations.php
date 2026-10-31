<?php
namespace App\Filament\Resources\Organizations\Pages;
use App\Filament\Resources\Organizations\OrganizationResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;
class ListOrganizations extends ListRecords {
 protected static string $resource = OrganizationResource::class;
 protected function getHeaderActions(): array { return [CreateAction::make()]; }
}

