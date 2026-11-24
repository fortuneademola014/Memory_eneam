<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicYear extends Model
{
    protected $fillable = ['label','is_current'];
    protected $casts = ['is_current' => 'boolean'];
    public function memoirs(): HasMany { return $this->hasMany(Memoir::class); }
}
