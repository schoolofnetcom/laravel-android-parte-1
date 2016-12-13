<?php

namespace SON\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use SON\Presenters\BillPayPresenter;
use SON\Models\BillPay;

/**
 * Class BillPayRepositoryEloquent
 * @package namespace SON\Repositories;
 */
class BillPayRepositoryEloquent extends BaseRepository implements BillPayRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return BillPay::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function presenter()
    {
        return BillPayPresenter::class;
    }

    public function applyMultitenancy()
    {
        BillPay::clearBootedModels();
    }

    public function calculateTotal()
    {
        $result = [
            'count' => 0,
            'count_paid' => 0,
            'total_be_paid' => 0
        ];
        /** @var Collection $billPays */
        $billPays = $this->skipPresenter()->all();
        $result['count'] = $billPays->count();
        foreach ($billPays as $billPay) {
            $done = (bool)$billPay->done;
            if ($done) {
                $result['count_paid']++;
            } else {
                $value = (float)$billPay->value;
                $result['total_be_paid'] += $value;
            }
        }
        return $result;
    }
}
