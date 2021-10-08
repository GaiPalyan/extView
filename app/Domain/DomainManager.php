<?php

declare(strict_types=1);

namespace App\Domain;

use App\Models\Urls;
use App\Repository\DBDomainRepositoryInterface;
use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;

class DomainManager
{
    private DBDomainRepositoryInterface $repository;

    public function __construct(DBDomainRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getDomainsList(): array
    {
        return $this->repository->getList();
    }

    /**
     * @param int $id
     * @return array
     */
    public function getDomainPersonalPage(int $id): array
    {
        return $this->repository->getPage($id);
    }

    /**
     * @param string $name
     * @return Urls
     */
    public function getDomainInfo(string $name): Urls
    {
        $normalizeUrl = self::normalize($name);
        return $this->repository->getDomainByName($normalizeUrl);
    }

    /**
     * @param string $domainName
     */
    public function prepareBasicDomainData(string $domainName): void
    {
        $normalizeName = self::normalize($domainName);

        $domain = [
            'name' => $normalizeName,
        ];

        $this->repository->saveDomain($domain);
    }

    /**
     * @param int $id
     * @return RedirectResponse|void
     * @throws InvalidSelectorException
     */
    public function prepareDomainCheckData(int $id)
    {
        $domain = $this->repository->getDomainById($id);

        try {
            $response = Http::get($domain->name);
        } catch (\Exception $e) {
            flash('Адрес не существует')->error()->important();
            return redirect()->route('domain_personal_page.show', $id);
        }

        $elements = new Document($response->body());
        $h1 = optional($elements->first('h1'))->text();
        $keywords = optional($elements->first('meta[name=keywords]'))->getAttribute('content');
        $description = optional($elements->first('meta[name=description]'))->getAttribute('content');

        $domainCheck = [
            'url_id' => $id,
            'status_code' => $response->status(),
            'h1' => $h1,
            'keywords' => $keywords,
            'description' => $description
        ];

        $this->repository->saveDomainCheck($domainCheck);
        flash('Проверка прошла успешно')->success()->important();
    }

    private static function normalize(string $urlName): string
    {
        $parts = parse_url($urlName);
        return strtolower("{$parts['scheme']}://{$parts['host']}");
    }
}
