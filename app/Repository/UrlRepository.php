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
        $list = Url::join('url_checks as checks', 'urls.id', '=', 'checks.url_id')
            ->selectRaw(
                'urls.id,
                urls.name as name,
                max(checks.updated_at) as last_check,
                checks.status_code'
            )->groupBy('urls.id', 'status_code')
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
}
