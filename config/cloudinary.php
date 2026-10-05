<?php

return [
    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
    'api_key' => env('CLOUDINARY_API_KEY'),
    'api_secret' => env('CLOUDINARY_API_SECRET'),

    // All uploads land under this prefix, e.g. hotel-app/gallery/xxxx.jpg
    'folder_prefix' => env('CLOUDINARY_FOLDER_PREFIX', 'hotel-app'),
];
