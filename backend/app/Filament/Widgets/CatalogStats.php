<?php
namespace App\Filament\Widgets;
use App\Models\{Organization,Specialty,Question,Exam,Attempt};
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
class CatalogStats extends StatsOverviewWidget {
 protected function getStats(): array { return [
  Stat::make('Organismes',Organization::count()),Stat::make('Spécialités',Specialty::count()),
  Stat::make('Questions actives',Question::where('is_active',true)->count()),
  Stat::make('Examens publiés',Exam::where('is_published',true)->count()),
  Stat::make('Examens remis',Attempt::whereNotNull('submitted_at')->count()),
 ]; }
}
