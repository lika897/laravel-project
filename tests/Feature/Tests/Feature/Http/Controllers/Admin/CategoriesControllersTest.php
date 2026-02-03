<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Enums\RoleEnum;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;


class CategoriesControllersTest extends TestCase
{

    public static function indexSuccessProvider(): array
    {
//        return [
//            'admin_role' => [
//                'role' => RoleEnum::ADMIN,
//                'categoriesCount' => 3,
//            ],
//            'moderator role' => [
//                'role' => RoleEnum::MODERATOR,
//                'categoriesCount' => 3,
//            ],
//            '10 categories' => [
//                'role' => RoleEnum::ADMIN,
//                'categoriesCount' => 10,
//            ]
//
//
//        ];
        return [
            'admin_role' => [RoleEnum::ADMIN, 3],
            'moderator_role' => [RoleEnum::MODERATOR, 3],
            '7 categories' => [RoleEnum::ADMIN, 7],
        ];
    }
    #[Test]
    #[DataProvider('indexSuccessProvider')]
    public function index_displays_categories_for_roles(RoleEnum $role, int $categoriesCount): void
    {
        $categories = Category::factory($categoriesCount)->create()
            ;

        $response = $this->actingAs($this->user($role))->get(route('admin.categories.index'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.categories.index');
        foreach ($categories->take(7) as $category) {
            $response->assertSee($category->title);
        }


    }

    #[Test]
    public function index_not_allowed_for_customers(): void
    {
        $response = $this->actingAs($this->user(RoleEnum::CUSTOMER))->get(route('admin.categories.index'));

        $response->assertForbidden();
    }

    #[Test]
    public function it_creates_category_without_parent(): void {
        $data = Category::factory()->makeOne()->toArray();

        $this->assertDatabaseEmpty('categories');

        $response = $this->actingAs($this->user(RoleEnum::ADMIN))->post(route('admin.categories.store'), $data);

        $response->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseHas('categories', $data);

    }

    #[Test]
    public function it_creates_category_with_parent()
    {
        $parent = Category::factory()->createOne();
        $data = Category::factory()->makeOne([
            'parent_id' => $parent->id,
        ])->toArray();

        $this->assertDatabaseMissing('categories', $data);

        $response = $this->actingAs($this->user(RoleEnum::ADMIN))->post(route('admin.categories.store'), $data);

        $response->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseHas('categories', $data);

    }

    #[Test]
    public function it_returns_an_error_with_invalid_title(): void
    {
        $data = Category::factory()->makeOne([
            'title' => '1',
        ])->toArray();

        $this->assertDatabaseEmpty('categories');

        $response = $this
            ->actingAs($this->user(RoleEnum::ADMIN))
            ->post(route('admin.categories.store'), $data);

        $response->assertSessionHasErrors('title');
        $this->assertDatabaseEmpty('categories');
    }

    #[Test]
    public function create_form_is_accessible_for_admin(): void
    {
        $response = $this->actingAs($this->user(RoleEnum::ADMIN))
            ->get(route('admin.categories.create'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.categories.create');
    }

    #[Test]
    public function create_form_is_forbidden_for_customer(): void
    {
        $response = $this->actingAs($this->user(RoleEnum::CUSTOMER))
            ->get(route('admin.categories.create'));

        $response->assertForbidden();
    }

    #[Test]
    public function edit_form_is_accessible_for_admin(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user(RoleEnum::ADMIN))
            ->get(route('admin.categories.edit', $category));

        $response->assertSuccessful();
        $response->assertViewIs('admin.categories.edit');
    }

    #[Test]
    public function edit_form_is_forbidden_for_customer(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user(RoleEnum::CUSTOMER))
            ->get(route('admin.categories.edit', $category));

        $response->assertForbidden();
    }
    #[Test]
    public function admin_can_update_category_with_valid_data(): void
    {
        $category = Category::factory()->create([
            'title' => 'Old Title'
        ]);

        $data = ['title' => 'New Title'];

        $response = $this->actingAs($this->user(RoleEnum::ADMIN))
            ->put(route('admin.categories.update', $category), $data);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', $data);
    }

//    #[Test]
//    public function admin_cannot_update_category_with_invalid_data(): void
//    {
//        $category = Category::factory()->create([
//            'title' => 'Old Title'
//        ]);
//
//        $data = ['title' => '1'];
//
//        $response = $this->actingAs($this->user(RoleEnum::ADMIN))
//            ->put(route('admin.categories.update', $category), $data);
//
//        $response->assertSessionHasErrors('title');
//        $this->assertDatabaseHas('categories', ['title' => 'Old Title']);
//    }

    #[Test]
    public function customer_cannot_update_category(): void
    {
        $category = Category::factory()->create([
            'title' => 'Old Title'
        ]);

        $data = ['title' => 'New Title'];

        $response = $this->actingAs($this->user(RoleEnum::CUSTOMER))
            ->put(route('admin.categories.update', $category), $data);

        $response->assertForbidden();
        $this->assertDatabaseHas('categories', ['title' => 'Old Title']);
    }

    #[Test]
    public function admin_can_delete_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user(RoleEnum::ADMIN))
            ->delete(route('admin.categories.destroy', $category));

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    #[Test]
    public function customer_cannot_delete_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user(RoleEnum::CUSTOMER))
            ->delete(route('admin.categories.destroy', $category));

        $response->assertForbidden();
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }








}

