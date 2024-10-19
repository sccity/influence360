<?php

namespace Webkul\BillFiles\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\BillFiles\Contracts\BillFile;

class BillFileRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return BillFile::class;
    }

    /**
     * Get latest bill files.
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getLatestBillFiles($limit = 5)
    {
        return $this->model->newQuery()
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Toggle tracked status of a bill file.
     *
     * @param int $id
     * @return BillFile
     */
    public function toggleTracked($id)
    {
        $billFile = $this->findOrFail($id);
        $billFile->is_tracked = !$billFile->is_tracked;
        $billFile->save();

        return $billFile;
    }
}
