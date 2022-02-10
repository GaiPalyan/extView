<?php

declare(strict_types=1);

namespace App\Repository;

use App\Domain\UrlExceptions\FailedUrlSaveException;
use App\Domain\UrlRepositoryInterface;
use App\Models\UrlCheck;
use App\Models\Url;
use Illuminate\Pagination\LengthAwarePaginator;

class UrlRepository implements UrlRepositoryInterface
{
    public function getList(): array
    {
        $urls = Url::select('id', 'name')->orderByDesc('created_at')->paginate(5);
        $lastChecks = $this->getUrlsCheckingData();

        return compact('urls', 'lastChecks');
    }

    public function getUrlCheckingData(Url $url): LengthAwarePaginator
    {
        return UrlCheck::where('url_id', $url->getAttribute('id'))->paginate(10);
    }

    /**
     * @throws FailedUrlSaveException
     */
    public function save(string $url): Url
    {
        $url = Url::create(['name' => $url]);

        if (!$url) {
            throw new FailedUrlSaveException('By some reason your address is not saved');
        }

        return $url;
    }

    public function saveCheck(Url $url, array $parsedBody): void
    {
        $urlCheck = new UrlCheck();
        $urlCheck->fill(array_merge(['url_id' => $url->getAttribute('id')], $parsedBody));
        $urlCheck->save();
    }

    public function getUrlsCheckingData(): array
    {
        return UrlCheck::select('url_id', 'status_code')
                        ->selectRaw('max(updated_at) as last_check')
                        ->groupBy('url_id', 'status_code')
                        ->get()->keyBy('url_id')
                        ->toArray();
    }
}
