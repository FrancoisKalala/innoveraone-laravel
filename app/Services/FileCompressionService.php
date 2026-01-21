<?php

namespace App\Services;

use Symfony\Component\Process\Process;

class FileCompressionService
{
    private bool $ffmpegAvailable;

    public function __construct()
    {
        $this->ffmpegAvailable = $this->checkFFmpeg();
    }

    /**
     * Check if FFmpeg is available on the system
     */
    private function checkFFmpeg(): bool
    {
        try {
            $process = new Process(['ffmpeg', '-version']);
            $process->run();
            return $process->isSuccessful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Compress file based on type (Professional social media compression)
     * Uses: WebP for images (Instagram/Facebook standard), H.264 for videos (YouTube/TikTok)
     */
    public function compress($file)
    {
        $mimeType = $file->getMimeType();

        // Images: Convert to WebP (Instagram, Facebook, Twitter standard)
        if (str_starts_with($mimeType, 'image/')) {
            return $this->compressImage($file);
        }

        // Videos: H.264 re-encoding if FFmpeg available (YouTube, TikTok, Instagram standard)
        if (str_starts_with($mimeType, 'video/')) {
            return $this->ffmpegAvailable ? $this->compressVideo($file) : $file;
        }

        // Audio: Convert to AAC/MP3 if FFmpeg available (Spotify, Apple Music standard)
        if (str_starts_with($mimeType, 'audio/')) {
            return $this->ffmpegAvailable ? $this->compressAudio($file) : $file;
        }

        // Return original for other formats
        return $file;
    }

    /**
     * Compress image to WebP format with quality 75
     * WebP reduces size by ~25-35% compared to JPEG
     * Used by: Instagram, Facebook, Google, Twitter
     */
    private function compressImage($file)
    {
        try {
            $tempPath = storage_path('temp/' . uniqid() . '.webp');
            $this->ensureTempDirectory();

            // Use ImageMagick via command line if available, fallback to copy
            $imagickPath = shell_exec('which convert 2>/dev/null') ?: (file_exists('C:\Program Files\ImageMagick-7\convert.exe') ? 'C:\Program Files\ImageMagick-7\convert.exe' : null);
            
            if ($imagickPath) {
                // Use ImageMagick to convert to WebP
                $cmd = sprintf(
                    '%s "%s" -quality 75 -resize 2048x2048 "%s"',
                    trim($imagickPath),
                    $file->getRealPath(),
                    $tempPath
                );
                shell_exec($cmd);
                
                if (file_exists($tempPath) && filesize($tempPath) > 0) {
                    return new \Symfony\Component\HttpFoundation\File\UploadedFile(
                        $tempPath,
                        pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.webp',
                        'image/webp',
                        null,
                        true
                    );
                }
            }
            
            // Fallback: return original file if compression not available
            return $file;
        } catch (\Exception $e) {
            \Log::warning('Image compression failed: ' . $e->getMessage());
            return $file;
        }
    }

    /**
     * Compress video using FFmpeg to H.264 codec
     * H.264 is the professional standard used by YouTube, TikTok, Instagram
     * Reduces size by 40-60% with minimal quality loss
     */
    private function compressVideo($file)
    {
        try {
            $tempPath = storage_path('temp/' . uniqid() . '.mp4');
            $this->ensureTempDirectory();

            // FFmpeg command: H.264 codec with CRF 28 (professional quality/size balance)
            $process = new Process([
                'ffmpeg',
                '-i', $file->getRealPath(),
                '-c:v', 'libx264',
                '-preset', 'fast',
                '-crf', '28',
                '-c:a', 'aac',
                '-b:a', '128k',
                '-movflags', 'faststart',
                $tempPath
            ]);

            $process->setTimeout(300);
            $process->run();

            if (!$process->isSuccessful()) {
                \Log::warning('Video compression failed: ' . $process->getErrorOutput());
                return $file;
            }

            return new \Symfony\Component\HttpFoundation\File\UploadedFile(
                $tempPath,
                pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.mp4',
                'video/mp4',
                null,
                true
            );
        } catch (\Exception $e) {
            \Log::warning('Video compression exception: ' . $e->getMessage());
            return $file;
        }
    }

    /**
     * Compress audio to AAC codec
     * AAC is the professional standard (Spotify, Apple Music, YouTube)
     * Reduces size by 50-70% compared to original with transparent quality
     */
    private function compressAudio($file)
    {
        try {
            $tempPath = storage_path('temp/' . uniqid() . '.m4a');
            $this->ensureTempDirectory();

            $process = new Process([
                'ffmpeg',
                '-i', $file->getRealPath(),
                '-c:a', 'aac',
                '-b:a', '128k',
                '-movflags', 'faststart',
                $tempPath
            ]);

            $process->setTimeout(300);
            $process->run();

            if (!$process->isSuccessful()) {
                \Log::warning('Audio compression failed: ' . $process->getErrorOutput());
                return $file;
            }

            return new \Symfony\Component\HttpFoundation\File\UploadedFile(
                $tempPath,
                pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.m4a',
                'audio/mp4',
                null,
                true
            );
        } catch (\Exception $e) {
            \Log::warning('Audio compression exception: ' . $e->getMessage());
            return $file;
        }
    }

    /**
     * Ensure temporary directory exists
     */
    private function ensureTempDirectory()
    {
        $tempDir = storage_path('temp');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
    }

    /**
     * Clean up temporary files (run in scheduled task)
     */
    public static function cleanupTempFiles()
    {
        $tempDir = storage_path('temp');
        if (!is_dir($tempDir)) {
            return;
        }

        $files = glob($tempDir . '/*');
        $now = time();
        foreach ($files as $file) {
            if (is_file($file) && ($now - filemtime($file)) > 3600) {
                unlink($file);
            }
        }
    }
}

