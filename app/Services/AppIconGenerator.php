<?php

namespace App\Services;

use App\Models\Profile;

/**
 * Builds the web-app (home screen) icons from the current portfolio profile photo.
 * Output: public/icons/app-512.png, app-192.png and app-180.png (square head-and-shoulders crop).
 */
class AppIconGenerator
{
    public const SIZES = [512, 192, 180];

    public static function available(): bool
    {
        return extension_loaded('gd') && function_exists('imagecreatefromstring');
    }

    /** Regenerate from the profile photo stored in the database. */
    public static function fromCurrentProfile(): bool
    {
        $profile = Profile::first();
        $path = $profile?->profile_image;
        if (! $path) {
            return false;
        }
        $absolute = public_path(ltrim($path, '/'));

        return self::generate($absolute);
    }

    public static function generate(string $sourcePath): bool
    {
        if (! self::available() || ! is_file($sourcePath)) {
            return false;
        }

        $data = @file_get_contents($sourcePath);
        $src = $data ? @imagecreatefromstring($data) : false;
        if (! $src) {
            return false;
        }

        $w = imagesx($src);
        $h = imagesy($src);

        // Square crop that keeps the face centred: tall photos are cropped to head and shoulders,
        // wide photos to their centre with a slight upward bias.
        if ($h > $w) {
            $side = (int) round($w * 0.88);
            $x = (int) round(($w - $side) / 2);
            $y = (int) round($h * 0.08);
        } else {
            $side = min($w, $h);
            $x = (int) round(($w - $side) / 2);
            $y = (int) round(($h - $side) * 0.2);
        }
        $side = max(1, min($side, $w - $x, $h - $y));

        $dir = public_path('icons');
        if (! is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        $ok = true;
        foreach (self::SIZES as $size) {
            $dst = imagecreatetruecolor($size, $size);
            imagealphablending($dst, true);
            imagecopyresampled($dst, $src, 0, 0, $x, $y, $size, $size, $side, $side);

            $tmp = $dir . '/.app-' . $size . '.tmp.png';
            $final = $dir . '/app-' . $size . '.png';
            if (@imagepng($dst, $tmp, 8)) {
                @rename($tmp, $final);
            } else {
                $ok = false;
            }
            imagedestroy($dst);
        }
        imagedestroy($src);

        return $ok;
    }
}
