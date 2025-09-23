<?php

declare(strict_types=1);

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

trait HasOrderScopes
{
    #[Scope]
    public function latest(Builder $query)
    {
        return $query->orderByDesc('created_at');
    }
}
