<?php


namespace App\Http\Repositories;


use App\Http\Interfaces\InstanceInterface;
use App\Models\Category;
use App\Models\Lot;
use App\Models\LotCategories;

class LotRepository implements InstanceInterface
{
    /**
     * Get all lots per page
     *
     * @param int $page
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAll($page = 1, $perPage = 10)
    {
        return Lot::with('categories')
            ->paginate($perPage, '*', 'page', $page);
    }

    /**
     * Get lots per page filtered by categories
     *
     * @param $categoryIds
     * @param int $page
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllFiltered($categoryIds, $page = 1, $perPage = 10)
    {
        $ids = LotCategories::whereIn('category_id', $categoryIds)
            ->pluck('lot_id')
            ->toArray();

        return Lot::with(['categories'])
            ->whereIn('id', $ids)
            ->paginate($perPage, '*', 'page', $page);
    }

    /**
     * Get lot by id
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getById($id)
    {
        return Lot::with('categories')->where('id', $id)->first();
    }

    /**
     * Create a new lot
     *
     * @param array $instanceDetails
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function create(array $instanceDetails)
    {
        $lot = Lot::create([
            'name' => $instanceDetails['name'],
            'description' => $instanceDetails['description']
        ]);

        Lot::associateCategories($lot->id, $instanceDetails['category_ids']);

        return $this->getById($lot->id);
    }

    /**
     * Update lot by id
     *
     * @param int $id
     * @param array $instanceDetails
     * @return mixed
     */
    public function update($id, array $instanceDetails)
    {
        $category_ids = $instanceDetails['category_ids'];
        unset($instanceDetails['category_ids']);

        $lot = Lot::where('id', $id)
            ->update($instanceDetails);

        LotCategories::where('lot_id', $id)
            ->whereNotIn('category_id', $category_ids)
            ->delete();

        $new_ids = collect($category_ids)->diff(
            LotCategories::query()->where('lot_id', $id)->pluck('category_id')
        )
            ->toArray();

        Lot::associateCategories($id, $new_ids);

        return $lot;
    }

    /**
     * Delete lot by id
     *
     * @param int $id
     * @return mixed
     */
    public function delete($id)
    {
        return Lot::where('id', $id)->delete();
    }
}
