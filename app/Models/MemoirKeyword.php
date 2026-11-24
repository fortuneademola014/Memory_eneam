<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemoirKeyword extends Model
{
    protected $fillable = ['memoir_id','keyword'];
    public function memoir(): BelongsTo { return $this->belongsTo(Memoir::class); }
}
