<?php

namespace Webkul\BillFiles\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\BillFiles\Contracts\BillFile;

class BillFileRepository extends Repository
{
    public function model()
    {
        return BillFile::class;
    }
}