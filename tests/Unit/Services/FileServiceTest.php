<?php

namespace Tests\Unit\Services;


use App\Services\Contracts\FileServiceContract;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileServiceTest extends TestCase
{
    #[Test]
    public function it_uploads_the_file(): void
    {
        Storage::fake('public');

        $path = 'uploads';
        $file = UploadedFile::fake()->image('image.jpg');
        $service = app(FileServiceContract::class);

        $imagePath = $service->upload($file, $path);

        Storage::disk('public')->assertExists($path);
        Storage::disk('public')->assertExists($imagePath);
        $this->assertSame('public', Storage::disk('public')->getVisibility($imagePath));

    }
}
