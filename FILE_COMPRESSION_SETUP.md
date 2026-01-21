# File Compression Setup Guide

## Current Implementation

Your social network now has **professional-grade file compression** matching industry standards:

### Image Compression
- **Format**: WebP (used by Instagram, Facebook, Google, Twitter)
- **Quality**: 75/100 (optimal balance between size & quality)
- **Size Reduction**: 25-35% smaller than original JPEG
- **Max Dimensions**: 2048px × 2048px
- **Automatic Resizing**: Images larger than 2048px are scaled down

### Video Compression (Requires FFmpeg)
- **Codec**: H.264 (YouTube, TikTok, Instagram standard)
- **Quality**: CRF 28 (professional streaming quality)
- **Size Reduction**: 40-60% smaller than original
- **Audio**: AAC codec @ 128kbps
- **Features**: Web-optimized streaming support

### Audio Compression (Requires FFmpeg)
- **Codec**: AAC (Spotify, Apple Music, YouTube standard)
- **Bitrate**: 128kbps (professional streaming quality)
- **Size Reduction**: 50-70% smaller than original

### File Limits
- **Max File Size**: 20 MB per file (before compression)
- **Max Files**: 15 files per post
- **Total Storage**: 300 MB per post (worst case)

---

## Installation Requirements

### 1. FFmpeg (Required for video/audio compression)

**Windows:**
1. Download FFmpeg from: https://ffmpeg.org/download.html
2. Download the Windows static build
3. Extract to: `C:\ffmpeg`
4. Add to System Environment Variables:
   - Right-click "This PC" → Properties
   - Advanced system settings → Environment Variables
   - New System variable:
     - **Variable name**: `FFMPEG_PATH`
     - **Variable value**: `C:\ffmpeg\bin`
   - Edit PATH and add: `C:\ffmpeg\bin`
5. Restart your terminal and verify: `ffmpeg -version`

**macOS (Homebrew):**
```bash
brew install ffmpeg
```

**Linux (Ubuntu/Debian):**
```bash
sudo apt-get install ffmpeg
```

### 2. ImageMagick or GD Library (For image compression)
Already installed with Intervention/Image package ✅

---

## Automatic Cleanup

The system automatically cleans up compressed temporary files older than 1 hour in `storage/temp/`.

To manually trigger cleanup, add to your Laravel Scheduler in `app/Console/Kernel.php`:

```php
$schedule->call(function () {
    \App\Services\FileCompressionService::cleanupTempFiles();
})->hourly();
```

---

## How It Works

1. **User uploads file** (browser shows: "max 15 files, 20 MB each")
2. **File validation** (type & size checks)
3. **Automatic compression**:
   - Images → WebP (25-35% size reduction)
   - Videos → H.264 (40-60% size reduction) *if FFmpeg available*
   - Audio → AAC (50-70% size reduction) *if FFmpeg available*
4. **Compressed file stored** to `storage/app/public/posts/`
5. **Original file discarded** (temporary file cleaned up)

---

## Compression Service Usage

In any component or controller:

```php
use App\Services\FileCompressionService;

$compressionService = new FileCompressionService();
$compressedFile = $compressionService->compress($uploadedFile);
```

The service handles:
- ✅ Auto-detection of file type
- ✅ Graceful fallback if FFmpeg unavailable (uses original)
- ✅ Error logging for troubleshooting
- ✅ Temp file cleanup

---

## Testing Compression

```php
// In tinker or controller
$file = request()->file('upload');
$service = new App\Services\FileCompressionService();
$compressed = $service->compress($file);
echo "Original: " . $file->getSize() . " bytes\n";
echo "Compressed: " . $compressed->getSize() . " bytes\n";
echo "Reduction: " . round((1 - $compressed->getSize() / $file->getSize()) * 100) . "%\n";
```

---

## Recommended Next Steps

1. Install FFmpeg for full video/audio compression
2. Add storage symlink: `php artisan storage:link`
3. Test by creating a post with images/videos
4. Monitor `storage/logs/laravel.log` for compression errors
5. Set up scheduled cleanup task (optional)
