<?php

return [

    /*
     * The disk on which to store uploaded files and derived images by default. Choose
     * one of the disks you've configured in config/filesystems.php.
     */
    'disk_name' => 'public',

    /*
     * The maximum file size of an item in bytes.
     * Adding 1/4 to the original size to account for JPEG compression can help
     * if you experience page breaks or cut off text when generating PDF's.
     */
    'max_file_size' => 1024 * 1024 * 100, // 100MB for video support

    /*
     * This queue connection will be used to generate derived and responsive images.
     * Leave empty to use the default queue connection.
     */
    'queue_name' => '',

    /*
     * This queue will be used to generate derived and responsive images.
     * Leave empty to use the default queue.
     */
    'queue_connection_name' => '',

    /*
     * The fully qualified class name of the media model.
     */
    'media_model' => Spatie\MediaLibrary\MediaCollections\Models\Media::class,

    /*
     * The fully qualified class name of the file adder.
     */
    'file_adder' => Spatie\MediaLibrary\MediaCollections\FileAdder\FileAdder::class,

    /*
     * The fully qualified class name of the file adder factory.
     */
    'file_adder_factory' => Spatie\MediaLibrary\MediaCollections\FileAdder\FileAdderFactory::class,

    /*
     * Here you can specify which conversions should be performed on a media item.
     */
    'generate_thumbnails_for_conversions' => true,

    /*
     * Here you can specify which conversions should be performed on a media item.
     */
    'perform_conversions_on_original_file' => false,

    /*
     * FFMPEG & FFProbe binaries paths, only used if you try to generate video
     * thumbnails and have installed the php-ffmpeg/php-ffmpeg composer package.
     * Commented out until php-ffmpeg/php-ffmpeg is installed via Composer.
     */
    // 'ffmpeg_path' => env('FFMPEG_PATH', '/usr/bin/ffmpeg'),
    // 'ffprobe_path' => env('FFPROBE_PATH', '/usr/bin/ffprobe'),

    /*
     * Here you can override the class names of the jobs used by this package. Make sure
     * your custom jobs extend the ones provided by the package.
     */
    'jobs' => [
        'perform_conversions' => Spatie\MediaLibrary\Conversions\Jobs\PerformConversionsJob::class,
        'generate_responsive_images' => Spatie\MediaLibrary\ResponsiveImages\Jobs\GenerateResponsiveImagesJob::class,
    ],

    /*
     * When urls to files get generated, this class will be called. Use the default
     * if your files are stored locally above the site root or on s3.
     */
    'url_generator' => Spatie\MediaLibrary\Support\UrlGenerator\DefaultUrlGenerator::class,

    /*
     * Moves uploaded file to the path configured in 'temporary_upload_path' config on any upload
     * failures. This prevents the uploaded file from being lost when the upload fails.
     */
    'generate_thumbnails_for_temporary_uploads' => true,

    /*
     * Here you can specify which conversions should be performed on a temporary file upload.
     */
    'perform_conversions_on_temporary_upload' => false,

    /*
     * Here you can specify which conversions should be performed on a media item.
     */
    'temporary_upload' => [
        'disk_name' => null,
        'directory_prefix' => 'tmp',
        'max_age' => 24 * 60 * 60, // 24 hours
    ],

    /*
     * Here you can specify which path generator should be used for the temporary upload model.
     */
    'temporary_upload_path_generator' => Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator::class,

    /*
     * If you want to generate responsive images, you can configure the options here.
     */
    'responsive_images' => [
        'enabled' => true,
        'widths' => [300, 600, 900, 1200, 1800],
        'quality' => 90,
        'format' => 'webp',
    ],

    /*
     * Here you can specify which path generator should be used for the media model.
     */
    'path_generator' => Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator::class,

    /*
     * Here you can specify which path generator should be used for the media model.
     */
    'file_namer' => Spatie\MediaLibrary\Support\FileNamer\DefaultFileNamer::class,

    /*
     * When converting Media instances to response the media library will add
     * a `loading` attribute to the `img` tag. Here you can set the default value
     * of that attribute. You can override this value by adding a `loading()` method
     * to your Media model.
     */
    'default_loading_attribute_value' => null,

    /*
     * You can specify a prefix for that is used for storing all media.
     * If you set this to `/my-subdir`, all your media will be stored in a `/my-subdir` directory.
     */
    'prefix' => '',

    /*
     * When converting Media instances to response the media library will add
     * a `decoding` attribute to the `img` tag. Here you can set the default value
     * of that attribute. You can override this value by adding a `decoding()` method
     * to your Media model.
     */
    'default_decoding_attribute_value' => null,

    /*
     * Here you can specify which remote disk should be used for storing media.
     * This will be used when you call the `toResponsiveImage()` method.
     */
    'remote' => [
        'disk_name' => null,
        'extra_headers' => [
            'CacheControl' => 'max-age=604800',
        ],
    ],

];