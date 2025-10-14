<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Bunny.net Stream Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Bunny.net video streaming service
    |
    */

    'library_id' => env('BUNNY_LIBRARY_ID'),
    'api_key' => env('BUNNY_API_KEY'),
    'cdn_hostname' => env('BUNNY_CDN_HOSTNAME'),
    'stream_url' => env('BUNNY_STREAM_URL', 'https://video.bunnycdn.com'),

];
