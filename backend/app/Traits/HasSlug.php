<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Keeps slug fields unique and in sync with the source column.
 */
trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::saving(function (Model $model): void {
            $sourceColumn = property_exists($model, 'slugSourceColumn')
                ? $model->slugSourceColumn
                : 'title';

            $value = $model->{$sourceColumn} ?? null;

            if (empty($value)) {
                return;
            }

            if ($model->isDirty('slug') && filled($model->slug)) {
                $model->slug = static::generateUniqueSlug($model, $model->slug);

                return;
            }

            if ($model->isDirty($sourceColumn) || empty($model->slug)) {
                $model->slug = static::generateUniqueSlug($model, $value);
            }
        });
    }

    protected static function generateUniqueSlug(Model $model, string $value): string
    {
        $base = Str::slug($value) ?: Str::random(8);

        $slug = $base;
        $counter = 1;

        while (static::query()
            ->where('slug', $slug)
            ->when($model->exists, fn ($query) => $query->whereKeyNot($model->getKey()))
            ->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
