<?php

declare(strict_types=1);

namespace App\Repository;

use Illuminate\View\View;

interface DBDomainRepositoryInterface
{
    public function getList(): View;

    /**
     * @param int $id
     * @return View
     */
    public function getPage(int $id): View;

    /**
     * @param int $id
     * @return mixed
     */
    public function getDomainById(int $id): mixed;

    /**
     * @param string $name
     * @return mixed
     */
    public function getDomainByName(string $name): mixed;

    /**
     * @param array $domain
     * @return string|void
     */
    public function saveDomain(array $domain);

    /**
     * @param int $id
     * @param string $column
     * @param int|string $value
     * @return string|void
     */
    public function updateDomainParam(int $id, string $column, int|string $value);

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