<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaService
{
    public function upload(UploadedFile $file, string $folder = 'uploads'): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        return $file->storeAs($folder, $filename, $this->disk());
    }

    public function delete(?string $path): void
    {
        if ($path && Storage::disk($this->disk())->exists($path)) {
            Storage::disk($this->disk())->delete($path);
        }
    }

    public function url(?string $path): ?string
    {
        return self::urlFor($path);
    }

    /**
     * Resolve a stored media path to a public URL using the configured media
     * disk (local "public" in development, "r2" in production).
     */
    public static function urlFor(?string $path): ?string
    {
        if (!$path) return null;

        // Legacy assets shipped with the repo (still in public/assets/)
        if (str_starts_with($path, 'assets/')) {
            return asset($path);
        }

        return Storage::disk(config('filesystems.media_disk', 'public'))->url($path);
    }

    private function disk(): string
    {
        return (string) config('filesystems.media_disk', 'public');
    }
}
