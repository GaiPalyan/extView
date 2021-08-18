<?php

namespace Tests\Feature;

use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DomainControllerTest extends TestCase
{
    use RefreshDatabase;

    protected \Faker\Generator $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->seed();
        dump($this);
    }

    public function testIndex()
    {
        $response = $this->get(route('domains.create'));
        $response->assertOk();
    }

    public function testShow()
    {
        $id = $this->faker->numberBetween(1, 4);
        $domain = DB::table('urls')->find(23);
        $response = $this->get(route('domains.show', $id));
    }

    /**
     * @param $domainName
     * @dataProvider domainNames
     */
    public function testStore($domainName)
    {
        $domain = ['name' => $domainName];
        $response = $this->post(route('domains.store'), $domain);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('urls', $domain);
    }


    public function domainNames()
    {
        return [
            'name' => [
                'https://www.yandex.ru',
                'https://www.google.com',
                'https://www.php.net'
            ]
        ];
    }
}
