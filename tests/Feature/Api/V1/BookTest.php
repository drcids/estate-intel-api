<?php

namespace Tests\Feature\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;

use App\Models\Book;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_getExternalBooks_api_for_success_with_data_resp()
    {
        $response = $this->json('GET', '/api/external-books', [
            'name' => 'A Clash of Kings'
        ]);

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('status')
                    ->where('status', 'success')
                ->has('status_code')
                    ->where('status_code', 200)
                ->has('data',1)
                ->has('data.0', fn ($json) =>
                    $json->hasAll('name', 'isbn', 'authors', 'country', 'number_of_pages', 'publisher', 'release_date')
                )
        );

    }

    public function test_getExternalBooks_api_for_failure_without_data_resp()
    {
        $response = $this->json('GET', '/api/external-books', [
            'name' => 'A of Kings'
        ]);

        $response->assertStatus(404);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('status')
                    ->where('status', 'not found')
                ->has('status_code')
                    ->where('status_code', 404)
                ->has('data',0)
        );

    }

    public function test_create_api_for_success_resp()
    {
        $response = $this->json('POST', '/api/v1/books', [
            'name' => 'A Clash of Kings',
            'isbn' => '1234567890',
            'authors' => ['David Natk', 'Ben Buju'],
            'country' => 'Nigeria',
            'number_of_pages' => 200,
            'publisher' => 'Thomas Frank',
            'release_date' => '2020-03-05',
        ]);

        $response->assertStatus(201);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('status')
                    ->where('status', 'success')
                ->has('status_code')
                    ->where('status_code', 201)
                ->has('data')
                ->has('data.book', fn ($json) =>
                    $json->hasAll('name', 'isbn', 'authors', 'country', 'number_of_pages', 'publisher', 'release_date')
                )
        );

        $this->assertEquals(1,Book::all()->count());

    }

    public function test_create_api_for_failure_on_validation_resp()
    {
        $response = $this->json('POST', '/api/v1/books', [
            'isbn' => '1234567890',
            'authors' => ['David Natk', 'Ben Buju'],
            'country' => 'Nigeria',
            'number_of_pages' => 200,
            'publisher' => 'Thomas Frank',
            'release_date' => '2020-03-05',
        ]);

        $response->assertStatus(422);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('status')
                    ->where('status', 'failed')
                ->has('status_code')
                    ->where('status_code', 422)
                ->has('message')
                ->has('data')
        );

    }

    public function test_read_api_for_success_without_params_resp()
    {
        $books = Book::factory()->count(20)->create();

        $response = $this->json('GET', '/api/v1/books');

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('status')
                    ->where('status', 'success')
                ->has('status_code')
                    ->where('status_code', 200)
                ->has('data', 20)
                ->has('data.0', fn ($json) =>
                    $json->hasAll('id', 'name', 'isbn', 'authors', 'country', 'number_of_pages', 'publisher', 'release_date')
                )
        );

    }

    public function test_read_api_for_success_with_params_resp()
    {
        $book = Book::factory()->create([
            'name' => 'A Clash of Kings',
            'isbn' => '1234567890',
            'authors' => ['David Natk', 'Ben Buju'],
            'country' => 'Nigeria',
            'number_of_pages' => 200,
            'publisher' => 'Thomas Frank',
            'release_date' => '2020-03-05',
        ]);

        $response = $this->json('GET', '/api/v1/books', [
            'name' => 'A Clash of Kings',
            'country' => 'Nigeria',
            'publisher' => 'Thomas Frank',
            'release_date' => 2020,
        ]);

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('status')
                    ->where('status', 'success')
                ->has('status_code')
                    ->where('status_code', 200)
                ->has('data', 1)
                ->has('data.0', fn ($json) =>
                    $json->hasAll('id', 'name', 'isbn', 'authors', 'country', 'number_of_pages', 'publisher', 'release_date')
                )
        );

    }

    public function test_read_api_for_success_with_params_and_no_data_resp()
    {
        $books = Book::factory()->count(20)->create();

        $response = $this->json('GET', '/api/v1/books',  [
            'name' => 'A Clash of Kings',
            'country' => 'Nigeria',
            'publisher' => 'Thomas Frank',
            'release_date' => 2020,
        ]);

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('status')
                    ->where('status', 'success')
                ->has('status_code')
                    ->where('status_code', 200)
                ->has('data', 0)
        );

    }

    public function test_update_api_for_success_resp()
    {
        $book = Book::factory()->create([
            'name' => 'A Clash of Kings',
            'isbn' => '1234567890',
            'authors' => ['David Natk', 'Ben Buju'],
            'country' => 'Nigeria',
            'number_of_pages' => 200,
            'publisher' => 'Thomas Frank',
            'release_date' => '2020-03-05',
        ]);

        $response = $this->json('PATCH', '/api/v1/books/'.$book->id, [
            'name' => 'A Clash of Four Kings',
        ]);

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('status')
                    ->where('status', 'success')
                ->has('status_code')
                    ->where('status_code', 200)
                ->has('message')
                ->has('data', fn ($json) =>
                    $json->hasAll('id', 'name', 'isbn', 'authors', 'country', 'number_of_pages', 'publisher', 'release_date')
                )
        );

        $this->assertDatabaseHas('books', [
            'id'=> $book->id,
            'name' => 'A Clash of Four Kings'
        ]);

    }

    public function test_update_api_for_failure_on_validation_resp()
    {
        $book = Book::factory()->create([
            'name' => 'A Clash of Kings',
            'isbn' => '1234567890',
            'authors' => ['David Natk', 'Ben Buju'],
            'country' => 'Nigeria',
            'number_of_pages' => 200,
            'publisher' => 'Thomas Frank',
            'release_date' => '2020-03-05',
        ]);

        $response = $this->json('PATCH', '/api/v1/books/'.$book->id, [
            'name' => $book->name,
        ]);

        $response->assertStatus(422);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('status')
                    ->where('status', 'failed')
                ->has('status_code')
                    ->where('status_code', 422)
                ->has('message')
                ->has('data')
        );

    }

    public function test_delete_api_for_success_resp()
    {
        $book = Book::factory()->create([
            'name' => 'A Clash of Kings',
            'isbn' => '1234567890',
            'authors' => ['David Natk', 'Ben Buju'],
            'country' => 'Nigeria',
            'number_of_pages' => 200,
            'publisher' => 'Thomas Frank',
            'release_date' => '2020-03-05',
        ]);

        $response = $this->json('DELETE', '/api/v1/books/'.$book->id);

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('status')
                    ->where('status', 'success')
                ->has('status_code')
                    ->where('status_code', 200)
                ->has('message')
                ->has('data')
        );

        $this->assertEquals(null,Book::where('id',$book->id)->first());

    }

    public function test_show_api_for_success_resp()
    {
        $book = Book::factory()->create([
            'name' => 'A Clash of Kings',
            'isbn' => '1234567890',
            'authors' => ['David Natk', 'Ben Buju'],
            'country' => 'Nigeria',
            'number_of_pages' => 200,
            'publisher' => 'Thomas Frank',
            'release_date' => '2020-03-05',
        ]);

        $response = $this->json('GET', '/api/v1/books/'.$book->id);

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('status')
                    ->where('status', 'success')
                ->has('status_code')
                    ->where('status_code', 200)
                ->has('data', fn ($json) =>
                    $json->hasAll('id', 'name', 'isbn', 'authors', 'country', 'number_of_pages', 'publisher', 'release_date')
                )
        );

    }

    public function test_show_api_for_failure_with_no_data_resp()
    {
        $response = $this->json('GET', '/api/v1/books/6');

        $response->assertStatus(404);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('status')
                    ->where('status', 'not found')
                ->has('status_code')
                    ->where('status_code', 404)
                ->has('data')
                ->has('message')
        );

    }

}
