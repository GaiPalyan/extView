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
        $this->url = Url::create(['name' => 'https://www.w.com.']);
    }

    public function testMain(): void
    {
        $response = $this->get(route('urls.create'));
        $response->assertOk();
    }

    public function testIndex(): void
    {
        $response = $this->get(route('urls.index', $this->url));
        $response->assertOk();
    }

    /**
     * @dataProvider fullNameUrlsProvider
     */
    public function testFullNameUrlsStore(string $urlName): void
    {
        $url = ['name' => $urlName];
        Http::fake([$url['name'] => Http::response([], 200)]);
        $response = $this->post(route('urls.store'), $url);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('urls', $url);
    }

    /**
     * @dataProvider shortNameUrlsProvider
     */
    public function shortNameUrlsStore(string $urlName): void
    {
        $url = ['name' => $urlName];
        Http::fake([$url['name'] => Http::response([], 200)]);

        $response = $this->post(route('urls.store'), $url);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('urls', ['name' => "https://{$url['name']}"]);
    }

    public function testStoreExistingDomain(): void
    {
        $url = ['name' => 'https://www.w.com.'];

        $response = $this->post(route('urls.store'), $url);
        $response->assertSessionHasErrors();
        $response->assertRedirect(route('urls.create'));
        $this->assertDatabaseHas('urls', $url);
    }

    /**
     * @param string $incorrectDomainNames
     * @dataProvider incorrectDomainNamesProvider
     */
    public function testStoreIncorrectDomainNames(string $incorrectDomainNames): void
    {
        $url = ['name' => $incorrectDomainNames];


        Http::fake([$url['name'] => Http::response([], 500)]);

        $response = $this->post(route('urls.store'), $url);
        $response->assertSessionHasErrors();
        $response->assertRedirect(route('urls.create'));
        $this->assertDatabaseMissing('urls', $url);
    }

    public function testStoreUrlCheck(): void
    {
        $body = '<h1>Header</h1> \n
                 <meta name="keywords" content="awesome content"> \n
                 <meta name="description" content="most popular app">';
        Http::fake([$this->url->name => Http::response($body, 200)]);
        $response = $this->post(route('url_checks.store', $this->url->id));

        $data = [
            'url_id' => $this->url->id,
            'status_code' => 200,
            "h1" => 'Header',
            "keywords" => 'awesome content',
            "description" => 'most popular app',
        ];
        $this->assertDatabaseHas('url_checks', $data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
    }

    public function fullNameUrlsProvider(): array
    {
        return [
            ['https://www.ya.ru'],
            ['https://www.google.com'],
            ['https://www.php.net']
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
            ['//wwww.youtube.com'],
            ['you//tube.com']
        ];
    }
}
