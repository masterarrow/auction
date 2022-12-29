<?php


namespace App\Http\Repositories;


use App\Http\Interfaces\InstanceInterface;
use App\Models\Category;

class CategoryRepository implements InstanceInterface
{
    /**
     * Get all categories per page
     *
     * @param int $page
     * @param int $perPage
     * @return mixed
     */
    public function getAll($page = 1, $perPage = 10)
    {
        return Category::paginate($perPage, '*', 'page', $page);
    }

    /**
     * Get category by id
     *
     * @param int $id
     * @return mixed
     */
    public function getById($id)
    {
        return Category::where('id', $id)->first();
    }

    /**
     * Create a new category
     *
     * @param array $instanceDetails
     * @return mixed
     */
    public function create(array $instanceDetails)
    {
        return Category::create($instanceDetails);
    }

    /**
     * Update category by id
     *
     * @param int $id
     * @param array $instanceDetails
     * @return mixed
     */
    public function update($id, array $instanceDetails)
    {
        return Category::where('id', $id)->update($instanceDetails);
    }

    /**
     * Delete category by id
     *
     * @param int $id
     * @return mixed
     */
    public function delete($id)
    {
        return Category::where('id', $id)->delete();
    }
}
