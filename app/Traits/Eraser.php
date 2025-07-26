<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use ReflectionClass;
use ReflectionMethod;
use ReflectionUnionType;
use Throwable;

trait Eraser
{
    protected static array $deletionRegistry = [];

    public static function resetDeletionRegistry(): void
    {
        self::$deletionRegistry = [];
    }

    public function deleteRelations(): void
    {
        $key = get_class($this).':'.$this->getKey();
        if (isset(self::$deletionRegistry[$key])) {
            return; // Already processed
        }
        self::$deletionRegistry[$key] = true;

        $class = new ReflectionClass($this);
        foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if (! $this->isDeletableRelationMethod($method, $this)) {
                continue;
            }

            try {
                Relation::noConstraints(function () use ($method) {
                    $relation = $method->invoke($this);
                    $this->deleteRelation($relation, $method->getName());
                });
            } catch (Throwable $e) {
                error_log("Error in method {$method->getName()}: {$e->getMessage()}");
            }
        }
    }

    private function isDeletableRelationMethod(ReflectionMethod $method, Model $model): bool
    {
        if ($method->class !== get_class($model) || $method->getNumberOfParameters() > 0) {
            return false;
        }

        $type = $method->getReturnType();
        if ($type) {
            $returnTypes = $type instanceof ReflectionUnionType
                ? array_map(fn ($t) => class_basename($t->getName()), $type->getTypes())
                : [class_basename($type->getName())];

            $preventTypes = ['BelongsTo', 'BelongsToOne'];
            if (array_intersect($preventTypes, $returnTypes)) {
                return false;
            }
        }

        return true;
    }

    private function deleteRelation(mixed $relation, string $method): void
    {
        if (! $relation instanceof Relation) {
            return;
        }

        $related = $relation->getResults();
        if ($related instanceof Collection) {
            $related->each->delete();
        } elseif ($related instanceof Model) {
            $related->delete();
        }

        error_log("Deleted relation of type: $method");
    }
}
