<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemoirNotification extends Model
{
    protected $fillable = ['user_id','memoir_id','kind','title','message','action_label','action_url','read_at'];
    protected $casts = ['read_at' => 'datetime'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function memoir(): BelongsTo { return $this->belongsTo(Memoir::class); }
}
