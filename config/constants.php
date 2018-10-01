<?php

return [

    /*
    |--------------------------------------------------------------------------
    | User Defined Variables
    |--------------------------------------------------------------------------
    |
    | This is a set of variables that are made specific to this application
    | that are better placed here rather than in .env file.
    | Use config('your_key') to get the values.
    |
    */

    'servernamemedia' => env('APP_DOMAIN_MEDIA').'/'.env('APP_SERVER_PUBLIC'),
    'media-server' => env('APP_DOMAIN_MEDIA').'/'.env('APP_SERVER_MEDIA'),
    'media-path' => env('APP_PATH').env('APP_SERVER_MEDIA'),
    'media-server-images' => env('APP_DOMAIN_MEDIA').'/'.env('MEDIA_ORIGINAL'),
    'media-path-images' => env('APP_PATH').env('MEDIA_ORIGINAL'),
    'media-server-images-thumb' => env('APP_DOMAIN_MEDIA').'/'.env('MEDIA_THUMBS'),
    'media-path-images-thumb' => env('APP_PATH').env('MEDIA_THUMBS'),
    'media-server-images-temp'=> env('APP_DOMAIN_MEDIA').'/'.env('MEDIA_TEMP'),
    'media-path-images-temp'=> env('APP_PATH').env('MEDIA_TEMP'),
    'media-server-micrositecover'=> env('APP_DOMAIN_MEDIA').'/'.env('MEDIA_MICROSITE'),
    'media-server-oldmedia'=> env('APP_DOMAIN_MEDIA').'/'.env('MEDIA_OLDMEDIA'),
    'media-path-oldmedia'=> env('APP_PATH').env('MEDIA_OLDMEDIA'),
    'media-server-oldmedia-thumb'=> env('APP_DOMAIN_MEDIA').'/'.env('MEDIA_OLDMEDIA_THUMB'),
    'media-path-oldmedia-thumb'=> env('APP_PATH').env('MEDIA_OLDMEDIA_THUMB'),
    'media-server-files'=> env('APP_DOMAIN_MEDIA').'/'.env('MEDIA_FILE'),
    'media-path-files'=> env('APP_PATH').env('MEDIA_FILE'),
    'media-server-blog'=> env('APP_DOMAIN_MEDIA').'/'.env('MEDIA_BLOG'),
    'media-path-blog'=> env('APP_PATH').env('MEDIA_BLOG'),
    'media-server-blog-thumb'=> env('APP_DOMAIN_MEDIA').'/'.env('MEDIA_BLOG_THUMB'),
    'media-path-blog-thumb'=> env('APP_PATH').env('MEDIA_BLOG_THUMB'),
    'media-server-produk'=> env('APP_DOMAIN_MEDIA').'/'.env('MEDIA_PRODUK'),
    'media-path-produk'=> env('APP_PATH').env('MEDIA_PRODUK'),
    'media-server-produk-thumb'=> env('APP_DOMAIN_MEDIA').'/'.env('MEDIA_PRODUK_THUMB'),
    'media-path-produk-thumb'=> env('APP_PATH').env('MEDIA_PRODUK_THUMB'),
    'media-server-chart-thumbnail'=> env('APP_DOMAIN_MEDIA').'/'.env('MEDIA_CHART'),
    'media-path-chart-thumbnail'=> env('APP_PATH').env('MEDIA_CHART'),
    'media-server-files-iklan'=> env('APP_DOMAIN_MEDIA').'/'.env('MEDIA_IKLAN'),
    'media-path-files-iklan'=> env('APP_PATH').env('MEDIA_IKLAN'),
    'media-server-files-partner'=> env('APP_DOMAIN_MEDIA').'/'.env('MEDIA_PARTNER'),
    'media-path-files-partner'=> env('APP_PATH').env('MEDIA_PARTNER'),
    'media-server-files-partner-thumb'=> env('APP_DOMAIN_MEDIA').'/'.env('MEDIA_PARTNER_THUMB'),
    'media-path-files-partner-thumb'=> env('APP_PATH').env('MEDIA_PARTNER_THUMB'),
    'media-path-sample'=> env('APP_PATH').env('MEDIA_PATH_SAMPLE'),
    'frontend_template_v2'=> env('APP_DOMAIN_MEDIA').'/'.env('FRONTEND_TEMPLATE_V2'),
    'media-path-images-thumb-webp'=>env('APP_PATH').env('MEDIA_IMAGES_THUMB_WEBP'),
    'media-server-images-thumb-webp'=>env('APP_DOMAIN_MEDIA').'/'.env('MEDIA_IMAGES_THUMB_WEBP'),
    'url_production_googlestorage_thumb_webp'=>"https://storage.googleapis.com/webserver-public/newsportal/public/media/images/webp/thumb/",
    'url_production_googlestorage_thumb'=>'https://storage.googleapis.com/webserver-public/newsportal/public/media/images/thumb/',
    'url_production_googlestorage_thumb_ori'=>'https://storage.googleapis.com/webserver-public/newsportal/public/media/images/original-thumb/',
    'category-id-opini'=>'2'

];