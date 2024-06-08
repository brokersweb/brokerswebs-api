<?php

namespace App\Observers;

use App\Models\Immovable;
use Illuminate\Support\Str;

class ImmovableObserver
{

    public function creating(Immovable $immovable)
    {
        $this->setSlug($immovable);
    }

    public function updating(Immovable $immovable)
    {
        $this->setSlug($immovable);
    }

    protected function setSlug(Immovable $immovable)
    {
        $slug = Str::slug($immovable->title);
        $slugCount = Immovable::where('slug', 'like', $slug . '%')
            ->where('id', '!=', $immovable->id)
            ->count();

        if ($slugCount > 0) {
            $immovable->slug = $slug . '-' . ($slugCount + 1);
        } else {
            $immovable->slug = $slug;
        }
    }
}
