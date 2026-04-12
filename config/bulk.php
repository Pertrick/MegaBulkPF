<?php

return [
    /** Max GET /verify hits per IP per minute (enumeration / abuse protection). */
    'payment_verify_per_minute' => (int) env('PAYMENT_VERIFY_THROTTLE_PER_MINUTE', 20),

    'chunk_size' => (int) env('BULK_DATA_CHUNK_SIZE', 150),
    /** Generic name; defaults to same env as planetf_http_timeout. */
    'provider_http_timeout' => (int) env('BULK_PROVIDER_TIMEOUT', env('BULK_PLANETF_TIMEOUT', 25)),
    'planetf_http_timeout' => (int) env('BULK_PLANETF_TIMEOUT', 25),
    'throttle_ms_between_requests' => (int) env('BULK_PLANETF_THROTTLE_MS', 0),
];
