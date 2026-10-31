<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attempt extends Model
{
    use HasUuids;
    protected $guarded = [];
    protected $hidden = ['token_hash', 'snapshot'];
    protected function casts(): array { return ['snapshot'=>'array', 'answers'=>'array', 'expires_at'=>'immutable_datetime', 'submitted_at'=>'immutable_datetime', 'score'=>'integer']; }
    public function exam(): BelongsTo { return $this->belongsTo(Exam::class); }
}
