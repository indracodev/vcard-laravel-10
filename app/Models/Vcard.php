<?php

namespace App\Models;

use App\Enums\VcardStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'photo',
    'name',
    'full_name',
    'title',
    'email',
    'mobile_phone',
    'link_name',
    'status',
])]
class Vcard extends Model
{
    protected function casts(): array
    {
        return [
            'status' => VcardStatus::class,
            'views' => 'integer',
        ];
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        $term = trim((string) $search);

        if ($term === '') {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term): void {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('full_name', 'like', "%{$term}%")
                ->orWhere('title', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%")
                ->orWhere('mobile_phone', 'like', "%{$term}%")
                ->orWhere('link_name', 'like', "%{$term}%");
        });
    }

    public function hasPhoto(): bool
    {
        return ! empty($this->photo) && is_file(public_path('storage/photos/'.$this->photo));
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->hasPhoto() ? asset('storage/photos/'.$this->photo) : null;
    }
}
