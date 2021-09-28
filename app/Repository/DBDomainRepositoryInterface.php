<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\Urls;
use Illuminate\View\View;

interface DBDomainRepositoryInterface
{
    public function getList(): array;

    /**
     * @param int $id
     * @return View
     */
    public function getPage(int $id): array;

    /**
     * @param int $id
     * @return mixed
     */
    public function getDomainById(int $id): mixed;

    /**
     * @param string $name
     * @return Urls
     */
    public function getDomainByName(string $name): Urls;

    /**
     * @param array $domain
     * @return string|void
     */
    public function saveDomain(array $domain);

    /**
     * @param array $data
     * @return string|void
     */
    public function saveDomainCheck(array $data);

    /**
     * @param string $identity
     * @return bool
     */
    public function isDomainExist(string $identity): bool;

    /**
     * @param int $id
     * @return bool
     */
    public function isDomainExistById(int $id): bool;

    /**
     * @param string $name
     * @return bool
     */
    public function isDomainExistByName(string $name): bool;
}
