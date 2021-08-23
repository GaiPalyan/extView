<?php

namespace Tests\Feature;

use Faker\Factory;
use Faker\Generator;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DomainControllerTest extends TestCase
{
    protected Generator $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->seed();
    }

    public function testIndex()
    {
        $response = $this->get(route('domains.create'));
        $response->assertOk();
    }

    public function testShow()
    {
        $id = $this->faker->numberBetween(1, 3);
        $domain = DB::table('urls')->find($id)->name;
        $response = $this->get(route('domains.show', $id));
        $response->assertOk();
        $response->assertSee($domain);
    }

    /**
     * @param $domainName
     * @dataProvider domainNamesProvider
     */
    public function testStore($domainName)
    {
        $domain = ['name' => $domainName];
        $response = $this->post(route('domains.store'), ['url' => $domain]);
        dump($response);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('urls', $domain);
    }

    /**
     * @param $incorrectDomainNames
     * @dataProvider incorrectDomainNamesProvider
     */
    public function testStoreIncorrectDomainNames($incorrectDomainNames)
    {
        $domain = ['name' => $incorrectDomainNames];
        $response = $this->post(route('domains.store'), ['url' => $domain]);
        $response->assertRedirect();
        $this->assertDatabaseMissing('urls', $domain);
    }

    public function domainNamesProvider(): array
    {
        return [
                ['https://www.dd.ru'],
                ['https://www.google.com'],
                ['https://www.php.net']
        ];
    }

    public function incorrectDomainNamesProvider(): array
    {
        return [
            ['google.ru'],
            ['htt://ya.ru'],
            ['www.youtube.com']
        ];
    }
}
