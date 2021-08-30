<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
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
     * @param string $domainName
     * @dataProvider domainNamesProvider
     */
    public function testStore(string $domainName)
    {
        $domain = [
            'name' => $domainName,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];
        $response = $this->post(route('domains.store'), ['url' => $domain]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('urls', $domain);
    }

    /**
     * @param string $incorrectDomainNames
     * @dataProvider incorrectDomainNamesProvider
     */
    public function testStoreIncorrectDomainNames(string $incorrectDomainNames)
    {
        $domain = ['name' => $incorrectDomainNames];
        $response = $this->post(route('domains.store'), ['url' => $domain]);
        $response->assertRedirect();
        $this->assertDatabaseMissing('urls', $domain);
    }

    public function testStoreDomainCheck()
    {
        $statusCode = 200;
        $domain = DB::table('urls')->first('*');
        $body = '<h1>Header</h1> \n
                 <meta name="keywords" content="awesome content"> \n
                 <meta name="description" content="most popular app">';
        Http::fake([$domain->name => Http::response($body, $statusCode)]);
        $response = $this->post(route('domain.checks.store', (int) $domain->id));

        $data = [
            'url_id' => $domain->id,
            'status_code' => $statusCode,
            "h1" => 'Header',
            "keywords" => 'awesome content',
            "description" => 'most popular app',
        ];
        $this->assertDatabaseHas('url_checks', $data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
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
