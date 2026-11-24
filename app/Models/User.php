<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name','email','matricule','role','filiere_id','password'];

    protected $hidden = ['password','remember_token'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    public function filiere(): BelongsTo { return $this->belongsTo(Filiere::class); }
    public function authoredMemoirs(): HasMany { return $this->hasMany(Memoir::class, 'author_id'); }
    public function supervisedMemoirs(): HasMany { return $this->hasMany(Memoir::class, 'supervisor_id'); }
    public function favorites(): BelongsToMany { return $this->belongsToMany(Memoir::class, 'favorites')->withTimestamps(); }
    public function readingHistories(): HasMany { return $this->hasMany(ReadingHistory::class); }
    public function memoirNotifications(): HasMany { return $this->hasMany(MemoirNotification::class); }

    public function initials(): string
    {
        $parts = preg_split('/\s+/', trim(preg_replace('/^(Pr\.|Dr\.)\s*/', '', $this->name)));
        return strtoupper(substr($parts[0] ?? '',0,1).substr($parts[1] ?? '',0,1));
    }

    public function hasRole(string ...$roles): bool { return in_array($this->role, $roles, true); }
}
