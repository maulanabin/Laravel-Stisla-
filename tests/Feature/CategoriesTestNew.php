<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class CategoriesTestNew extends TestCase
{
    /**
     * authorized user.
     *
     * @return void
     */
    use RefreshDatabase;
    protected function authorized()
    {
        if (!Auth::attempt(['email' => 'superadmin@gmail.com', 'password' => 'password'])) {
            return response((['message' => 'Login Gagal']));
        }

        return auth()->user()->createToken('token')->accessToken;
    }

    /**
     * test get all products.
     *
     * @return void
     */
    public function test_get_all_product()
    {
        $token = $this->authorized();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer' . $token,
        ])->json('GET', 'http://localhost:8070/api/categories');

        $response->assertStatus(200);
    }

    public function test_store_category()
    {
        $token = $this->authorized();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', 'http://localhost:8070/api/categories', [
            'name' => 'White Box Testing' . rand(1, 100),
        ]);

        $response->assertStatus(201);
    }

    public function test_show_by_id_category()
    {
        $id = rand(1, 5);
        $token = $this->authorized();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', 'http://localhost:8070/api/categories/3');

        $response->assertStatus(200);
    }

    public function test_update_by_id_category()
    {
        $token = $this->authorized();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('PUT', 'http://localhost:8070/api/categories/2', [
            'name' => 'White Box Testing',
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_by_id_category()
    {
        $token = $this->authorized();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('DELETE', 'http://localhost:8070/api/categories/3', [
            'name' => 'White Box Testing',
        ]);

        $response->assertStatus(204);
    }
}
