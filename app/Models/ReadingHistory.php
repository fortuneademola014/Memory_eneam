<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReadingHistory extends Model
{
    protected $fillable = ['user_id','memoir_id','last_page','total_pages','last_read_at'];
    protected $casts = ['last_read_at' => 'datetime'];
    public function memoir(): BelongsTo { return $this->belongsTo(Memoir::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
