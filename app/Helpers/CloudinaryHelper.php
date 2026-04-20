<?php

namespace App\Helpers;

use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;

class CloudinaryHelper
{
    private static function client(): Cloudinary
    {
        return new Cloudinary(
            Configuration::instance([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key'    => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ],
                'url' => [
                    'secure' => true
                ]
            ])
        );
    }

    public static function upload(string $filePath, string $folder = 'general'): string
    {
        $result = self::client()->uploadApi()->upload($filePath, [
            'folder' => $folder
        ]);

        return $result['public_id'];
    }

    public static function delete(string $publicId): void
    {
        self::client()->uploadApi()->destroy($publicId);
    }

    public static function getUrl(string $publicId): string
    {
        return self::client()->image($publicId)->toUrl();
    }
}
