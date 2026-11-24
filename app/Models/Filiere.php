<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Filiere extends Model
{
    protected $fillable = ['name','slug'];
    public function users(): HasMany { return $this->hasMany(User::class); }
    public function memoirs(): HasMany { return $this->hasMany(Memoir::class); }
}
