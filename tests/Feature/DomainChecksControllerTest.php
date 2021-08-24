<?php

namespace Tests\Feature;

use Faker\Factory;
use Faker\Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DomainChecksControllerTest extends TestCase
{
    protected Generator $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->seed();
    }

    public function testStore()
    {
        $id = $this->faker->numberBetween(1, 3);
        $domain = DB::table('urls')->find($id);
        $statusCode = 200;
        $response = $this->post(route('domain.checks.store', $domain->id));
        $data = [
            'url_id' => $domain->id,
            'status_code' => null,
            "h1" => null,
            "keywords" => null,
            "description" => null,
        ];
        $response->assertRedirect();
        $this->assertDatabaseHas('url_checks', $data);
    }
}
