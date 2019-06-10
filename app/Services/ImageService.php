<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image as InterImage;

class ImageService
{
    private $directory;
    private $width;
    private $height;
    private $mime;
    private $length;

    public function __construct(string $configKey)
    {
        $config = config('images.'. $configKey);
        $this->directory = trim($config['directory']) ?? 'images';
        $this->width = $config['width'] ?? 320;
        $this->height = $config['height'] ?? 200;
        $this->mime = $config['mime'] ?? 'jpg';
        $this->length = $config['length'] ?? 50;
    }

    public function store(UploadedFile $file): string
    {
        $image = InterImage::make($file);
        $name = $this->getRandomImageName();
        $storePath = $this->getStorePath($name);

        $image->resize($this->width, $this->height)
            ->save($storePath);

        return $this->getDbSavePath($name);
    }

    public function unlink(string $source): void
    {
        $unlinkPath = $this->getUnlinkPath($source);

        if (file_exists($unlinkPath)) {
            unlink($unlinkPath);
        }
    }

    public function unlinkMany(array $sources): void
    {
        foreach ($sources as $source) {
            $this->unlink($source);
        }
    }

    private function getRandomImageName(): string
    {
        return Str::random($this->length) . '.' . $this->mime;
    }

    private function getDbSavePath(string $name): string
    {
        return $this->directory . '/' . $name;
    }

    private function getStorePath(string $name): string
    {
        $path = Storage::path($this->directory) . '/' . $name;

        return $this->changeSeparator($path);
    }

    private function getUnlinkPath(string $source): string
    {
        $path = Storage::path($source);

        return $this->changeSeparator($path);
    }

    private function changeSeparator(string $path): string
    {
        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }
}
