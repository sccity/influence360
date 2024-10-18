<?php

namespace Webkul\Bills\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Bills\Contracts\Bill;

class BillRepository extends Repository
{
    public function model()
    {
        return Bill::class;
    }
}

