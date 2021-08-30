<?php

declare(strict_types=1);

namespace App\Domain;

use App\Repository\DomainRepository;
use Carbon\Carbon;
use DiDom\Document;
use Illuminate\Support\Facades\Http;

class DomainManager
{
    private DomainRepository $repository;
    private Carbon $time;

    public function __construct(DomainRepository $repository)
    {
        $this->repository = $repository;
        $this->time = Carbon::now();
    }

    public function getDomainsList()
    {
        return $this->repository->getList();
    }

    public function getDomainPersonalPage(int $id)
    {
        return $this->repository->getPage($id);
    }

    public function getDomainInfo(string $name)
    {
        $normalizeUrl = self::normalize($name);
        return $this->repository->getDomain(null, $normalizeUrl);
    }

    public function prepareBasicDomainData(string $domainName): void
    {
        $normalizeName = self::normalize($domainName);

        $domain = [
            'name' => $normalizeName,
            'created_at' => $this->time,
            'updated_at' => $this->time
        ];

        $this->repository->saveDomain($domain);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|void
     * @throws \DiDom\Exceptions\InvalidSelectorException
     */
    public function prepareDomainCheckData(int $id)
    {
        $domain = $this->repository->getDomain($id);
        try {
            $response = Http::get($domain->name);
        } catch (\Exception $e) {
            flash('Адрес не существует')->error()->important();
            return redirect()->route('domain.show', $id);
        }

        $elements = new Document($response->body());
        $h1 = $elements->has('h1')
            ? $elements->first('h1')->text()
            : null;
        $keywords = $elements->has('meta[name="keywords"]')
            ? $elements->first('meta[name="keywords"]')->getAttribute('content')
            : null;
        $description = $elements->has('meta[name="description"]')
            ? $elements->first('meta[name="description"]')->getAttribute('content')
            : null;
        $domainCheck = [
            'url_id' => $id,
            'created_at' => $this->time,
            'updated_at' => $this->time,
            'status_code' => $response->status(),
            'h1' => $h1,
            'keywords' => $keywords,
            'description' => $description
        ];

        $this->repository->saveDomainCheck($domainCheck);
        $this->repository->updateDomainParam($id, 'updated_at', $this->time->toDateTimeString());
    }

    private static function normalize(string $urlName): string
    {
        $parts = parse_url($urlName);
        return strtolower("{$parts['scheme']}://{$parts['host']}");
    }
}
