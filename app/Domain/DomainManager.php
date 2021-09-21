<?php

declare(strict_types=1);

namespace App\Domain;

use App\Repository\DBDomainRepository;
use App\Repository\DBDomainRepositoryInterface;
use Carbon\Carbon;
use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use stdClass;

class DomainManager
{
    private DBDomainRepositoryInterface $repository;
    private Carbon $time;

    public function __construct(DBDomainRepositoryInterface $repository)
    {
        $this->repository = $repository;
        $this->time = Carbon::now();
    }

    public function getDomainsList(): View
    {
        return $this->repository->getList();
    }

    /**
     * @param int $id
     * @return View
     */
    public function getDomainPersonalPage(int $id): View
    {
        return $this->repository->getPage($id);
    }

    /**
     * @param string $name
     * @return stdClass
     */
    public function getDomainInfo(string $name): stdClass
    {
        $normalizeUrl = self::normalize($name);

        if ($this->repository->isDomainExist($normalizeUrl)) {
            return $this->repository->getDomainByName($normalizeUrl);
        }
        return new stdClass();
    }

    /**
     * @param string $domainName
     */
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
            'created_at' => $this->time,
            'updated_at' => $this->time,
            'status_code' => $response->status(),
            'h1' => $h1,
            'keywords' => $keywords,
            'description' => $description
        ];

        $this->repository->saveDomainCheck($domainCheck);
        $this->repository->updateDomainParam($id, 'updated_at', $this->time->toDateTimeString());
        flash('Проверка прошла успешно')->success()->important();
    }

    private static function normalize(string $urlName): string
    {
        $parts = parse_url($urlName);
        return strtolower("{$parts['scheme']}://{$parts['host']}");
    }
}
