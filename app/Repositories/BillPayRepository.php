<?php

namespace SON\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface BillPayRepository
 * @package namespace SON\Repositories;
 */
interface BillPayRepository extends RepositoryInterface, RepositoryMultitenancyInterface
{
    public function calculateTotal();
}
