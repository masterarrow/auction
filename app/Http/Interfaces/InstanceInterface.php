<?php


namespace App\Http\Interfaces;


interface InstanceInterface
{
    public function getAll($page, $perPage);
    public function getById($id);
    public function create(array $instanceDetails);
    public function update($id, array $instanceDetails);
    public function delete($id);
}
