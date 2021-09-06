<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DomainControllerTest extends TestCase
{
    protected int $id;
    protected string $time;
    protected array $data;

    public function setUp(): void
    {
        parent::setUp();
        $this->time = Carbon::now()->toDateTimeString();

        $this->data = [
            'name' => 'https://www.w.com.',
            'created_at' => $this->time,
            'updated_at' => $this->time
        ];

        $this->id = DB::table('urls')->insertGetId($this->data);
    }

    public function testIndex(): void
    {
        $response = $this->get(route('domains.create'));
        $response->assertOk();
    }

    public function testShow(): void
    {
        $response = $this->get(route('domains_list.show', $this->id));
        $response->assertOk();
    }

    /**
     * @param string $domainName
     * @dataProvider domainNamesProvider
     */
    public function testStore(string $domainName): void
    {
        $domain = ['name' => $domainName];
        $response = $this->post(route('urls.store'), ['url' => $domain]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('urls', $domain);
    }

    public function testStoreExistingDomain(): void
    {
        $domain = ['name' => $this->data['name']];
        $response = $this->post(route('urls.store'), ['url' => $domain]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('domain_personal_page.show', ['id' => $this->id]));
        $this->assertDatabaseHas('urls', $this->data);
    }

    /**
     * @param string $incorrectDomainNames
     * @dataProvider incorrectDomainNamesProvider
     */
    public function testStoreIncorrectDomainNames(string $incorrectDomainNames): void
    {
        $domain = ['name' => $incorrectDomainNames];
        $response = $this->post(route('urls.store'), ['url' => $domain]);
        $response->assertRedirect();
        $this->assertDatabaseMissing('urls', $domain);
    }

    public function testStoreDomainCheck(): void
    {
        $statusCode = 200;
        $body = '<h1>Header</h1> \n
                 <meta name="keywords" content="awesome content"> \n
                 <meta name="description" content="most popular app">';
        Http::fake([$this->data['name'] => Http::response($body, $statusCode)]);
        $response = $this->post(route('domain_checks.store', $this->id));

        $data = [
            'url_id' => $this->id,
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
