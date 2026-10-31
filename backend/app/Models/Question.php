<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Validator;

class Question extends Model
{
    protected $fillable = ['specialty_id', 'prompt', 'options', 'correct_option', 'explanation', 'is_active'];
    protected function casts(): array { return ['options' => 'array', 'correct_option' => 'integer', 'is_active' => 'boolean']; }
    public function specialty(): BelongsTo { return $this->belongsTo(Specialty::class); }
    public function exams(): BelongsToMany { return $this->belongsToMany(Exam::class); }
    protected static function booted(): void
    {
        static::saving(function (self $question) {
            Validator::make([
                'options' => $question->options,
                'correct_option' => $question->correct_option,
            ], [
                'options' => ['required', 'array', 'size:4'],
                'options.*' => ['required', 'string', 'max:1000', 'distinct'],
                'correct_option' => ['required', 'integer', 'between:0,3'],
            ])->validate();
            if ($question->exists && $question->isDirty('specialty_id') && $question->exams()->exists()) {
                throw \Illuminate\Validation\ValidationException::withMessages(['specialty_id' => 'Retirez d’abord cette question des examens associés.']);
            }
        });
    }
}
