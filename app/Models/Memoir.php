<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Memoir extends Model
{
    protected $fillable = [
        'author_id','supervisor_id','filiere_id','academic_year_id','title','abstract','pdf_path','cover_path','pages',
        'status','rejection_reason','embargo_until','download_disabled','restricted_access','submitted_at','validated_at','published_at','reads_count'
    ];

    protected $casts = [
        'embargo_until' => 'date',
        'download_disabled' => 'boolean',
        'restricted_access' => 'boolean',
        'submitted_at' => 'datetime',
        'validated_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    public function author(): BelongsTo { return $this->belongsTo(User::class, 'author_id'); }
    public function supervisor(): BelongsTo { return $this->belongsTo(User::class, 'supervisor_id'); }
    public function filiere(): BelongsTo { return $this->belongsTo(Filiere::class); }
    public function academicYear(): BelongsTo { return $this->belongsTo(AcademicYear::class); }
    public function keywords(): HasMany { return $this->hasMany(MemoirKeyword::class); }
    public function favoritedBy(): BelongsToMany { return $this->belongsToMany(User::class, 'favorites')->withTimestamps(); }

    public function scopePublished($q) { return $q->whereIn('status', ['valide','publie']); }

    public function statusLabel(): string
    {
        return match($this->status) {
            'soumis' => 'Soumis',
            'verification' => 'En vérification',
            'valide', 'publie' => 'Validé',
            'rejete' => 'Rejeté',
            default => ucfirst($this->status),
        };
    }

    public function initials(): string
    {
        $stop = ['le','la','les','de','des','du','et','l','d','sur','par','dans','au','aux','cas','en'];
        $words = preg_split('/\s+/', preg_replace('/[«»:’\'(),–-]/u',' ', $this->title));
        $letters = [];
        foreach ($words as $w) {
            if ($w === '' || in_array(mb_strtolower($w), $stop, true)) continue;
            if (preg_match('/[A-Za-zÀ-ÿ]/u', mb_substr($w,0,1))) $letters[] = mb_strtoupper(mb_substr($w,0,1));
            if (count($letters) === 2) break;
        }
        return implode('', $letters);
    }
}
