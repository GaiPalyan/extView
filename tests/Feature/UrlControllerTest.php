<?php

namespace Tests\Feature;

use App\Models\Url;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class UrlControllerTest extends TestCase
{
    protected Url $url;

    public function setUp(): void
    {
        parent::setUp();
        $this->url = Url::create(['name' => 'https://www.google.com.']);
    }

    public function testMain(): void
    {
        $response = $this->get(route('urls.create'));
        $response->assertOk();
    }

    /**
     * @return void
     * @throws \JsonException
     */
    public function testIndex(): void
    {
        $url = [
            'id' => $this->url->id,
            'name' => $this->url->name,
            'last_check' => null,
            'status_code' => null,
        ];

        $expected = json_encode($url, JSON_THROW_ON_ERROR);
        $response = $this->get(route('api.index'));
        $response->assertOk();
        $response->assertSessionHasNoErrors();
        $this->assertEquals("[{$expected}]", $response->content());
    }

    /**
     * @dataProvider fullNameUrlsProvider
     */
    public function testFullNameUrlsStore(string $urlName): void
    {
        $url = ['name' => $urlName];
        Http::fake([$url['name'] => Http::response([], 200)]);
        $response = $this->post(route('api.store'), $url);

        $response->assertJson(['success' => 'Url added']);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('urls', $url);
    }

    /**
     * @dataProvider shortNameUrlsProvider
     */
    public function testShortNameUrlsStore(string $urlName): void
    {
        $url = ['name' => $urlName];
        Http::fake([$url['name'] => Http::response([], 200)]);

        $response = $this->post(route('api.store'), $url);
        $response->assertSessionHasNoErrors();
        $response->assertJson(['success' => 'Url added']);
        $this->assertDatabaseHas('urls', ['name' => "https://{$url['name']}"]);
    }

    public function testStoreExistingDomain(): void
    {
        $url = ['name' => 'https://www.google.com.'];

        $response = $this->post(route('api.store'), $url);
        $response->assertJson(['error' => "This address already exist."]);
        $response->assertStatus(422);
        $this->assertDatabaseHas('urls', $url);
    }

    /**
     * @dataProvider incorrectDomainNamesProvider
     */
    public function testStoreIncorrectDomainNames(string $incorrectDomainNames): void
    {
        $url = ['name' => $incorrectDomainNames];


        Http::fake([$url['name'] => Http::response([], 422)]);
        $response = $this->post(route('api.store'), $url);
        $response->assertJson(['error' => 'Address is not exist or action violates this site\'s security policy']);
        $response->assertStatus(422);
        $this->assertDatabaseMissing('urls', $url);
    }

    public function testStoreUrlCheck(): void
    {
        $body = '<h1>Header</h1> \n
                 <meta name="keywords" content="awesome content"> \n
                 <meta name="description" content="most popular app">';
        Http::fake([$this->url->name => Http::response($body, 200)]);
        $response = $this->post(route('api.check_store', $this->url->id));

        $data = [
            'url_id' => $this->url->id,
            'status_code' => 200,
            "h1" => 'Header',
            "keywords" => 'awesome content',
            "description" => 'most popular app',
        ];
        $this->assertDatabaseHas('url_checks', $data);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
        $response->assertJson(['latest' => $data, 'success' => 'Url has been checked']);
    }

    public function fullNameUrlsProvider(): array
    {
        return [
            ['https://www.ya.ru'],
            ['https://www.google.com'],
            ['https://www.php.net'],
        ];
    }

    public function shortNameUrlsProvider(): array
    {
        return [
            ['google.com'],
            ['php.net'],
            ['dd.ru'],
        ];
    }

    public function incorrectDomainNamesProvider(): array
    {
        return [
            ['googleru'],
            ['htt://ya.ru'],
            ['you//tube.com']
        ];
    }
}
