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
    public function getList($inputText = ''): array
    {
        $list = Url::selectRaw(
            'urls.id,
                urls.name as name,
                max(checks.updated_at) as last_check,
                checks.status_code'
        )->leftJoin('url_checks as checks', function ($join) {
            $join->on('urls.id', '=', 'checks.url_id');
        })->orWhere('name', 'like', "%{$inputText}%")
          ->groupBy('urls.id', 'status_code')
          ->orderByDesc('last_check')
          ->paginate(5);

        return compact('list');
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
        try {
            $url = Url::create(['name' => $url]);
        } catch (\Exception $e) {
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

    public function getUrlLustCheck(Url $url): UrlCheck
    {
        return UrlCheck::where('url_id', $url->getAttribute('id'))->latest()->first();
    }
}
