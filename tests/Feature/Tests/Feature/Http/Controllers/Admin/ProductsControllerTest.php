<?php

namespace Tests\Feature\Http\Controllers\Admin;


use App\Enums\RoleEnum;
use App\Models\Category;
use App\Models\Product;
use PHPUnit\Framework\Attributes\Test;
use App\Services\Contracts\FileServiceContract;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mockery\MockInterface;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;



class ProductsControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_product_with_valid_data(): void
    {
        Storage::fake('public');

        $fileName = 'test.jpg';

        $title = 'Test product';

        $data = $this->buildProduct($title, $fileName);

        $this->mockFileService("/test/$data[slug]/$fileName"
        );

        $this->assertDatabaseEmpty('products');

        $response = $this->actingAs($this->user(RoleEnum::MODERATOR))->post(route('admin.products.store'), $data);


        $response->assertRedirect(route('admin.products.index'));


        $this->assertDatabaseHas('products', [
            'title' => $title,
            'SKU' => $data['SKU']
        ]);
    }

    #[Test]
    public function it_creates_product_with_categories(): void
    {
        Storage::fake('public');
        $categoriesIds = Category::factory(2)->create()->pluck('id')->toArray();

        $title = 'Test product';

        $data = $this->buildProduct($title, 'test.jpg', ['categories' => $categoriesIds]);
        $this->mockFileService("/test/$data[slug]/test.jpg");

        $this->assertDatabaseEmpty('products');

        $response = $this->actingAs($this->user(RoleEnum::MODERATOR))->post(route('admin.products.store'), $data);


        $response->assertRedirect(route('admin.products.index'));


        $this->assertDatabaseHas('products', [
            'title' => $title,
            'SKU' => $data['SKU']
        ]);

        $product = Product::where('slug', $data['slug'])->firstOrFail();

        $this->assertSame($categoriesIds, $product->categories()->pluck('id')->toArray());
    }

    protected function mockFileService(string $path): void
    {
        $this->mock(FileServiceContract::class, function (MockInterface $mock) use ($path) {
            $mock->allows('upload')->andReturn($path);
        });


    }
    protected function buildProduct(string $title, string $fileName = 'test.png', array $params = []): array
    {
        $slug = Str::slug($title);
        $file = UploadedFile::fake()->image($fileName);

        return [

            ...Product::factory()->makeOne([
                'title' => $title,
                'slug' => $slug,
                'thumbnail' => $file,
                ...$params
            ])->toArray(),
            'thumbnail' => $file,

        ];
    }
}
