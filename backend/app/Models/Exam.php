<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    protected $fillable = ['specialty_id', 'title', 'duration_minutes', 'is_published'];
    protected function casts(): array { return ['duration_minutes' => 'integer', 'is_published' => 'boolean']; }
    public function specialty(): BelongsTo { return $this->belongsTo(Specialty::class); }
    public function questions(): BelongsToMany { return $this->belongsToMany(Question::class)->orderBy('questions.id'); }
    public function attempts(): HasMany { return $this->hasMany(Attempt::class); }
}
