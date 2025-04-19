<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Psr\Log\LoggerInterface;

class CategoryRepository
{
    private $category;
    private $logger;

    public function __construct(Category $category, LoggerInterface $logger)
    {
        $this->category = $category;
        $this->logger = $logger;
    }

    public function getAll()
    {
        try {
            return $this->category->all();
        } catch (QueryException $e) {
            $this->logger->error('Error getting all categories: ' . $e->getMessage());
            throw new \Exception('Failed to retrieve categories.');
        }
    }

    public function getById(int $id)
    {
        try {
            return $this->category->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            $this->logger->warning('Category not found: ' . $e->getMessage());
            throw new \Exception('Category not found.');
        } catch (QueryException $e) {
            $this->logger->error('Error getting category by ID: ' . $e->getMessage());
            throw new \Exception('Failed to retrieve category.');
        }
    }

    public function create(array $categoryData)
    {
        try {
            return $this->category->create($categoryData);
        } catch (QueryException $e) {
            $this->logger->error('Error creating category: ' . $e->getMessage());
            throw new \Exception('Failed to create category.');
        }
    }

    public function update(int $id, array $categoryData)
    {
        try {
            $category = $this->category->findOrFail($id);
            $category->update($categoryData);
            return $category;
        } catch (ModelNotFoundException $e) {
            $this->logger->warning('Category not found: ' . $e->getMessage());
            throw new \Exception('Category not found.');
        } catch (QueryException $e) {
            $this->logger->error('Error updating category: ' . $e->getMessage());
            throw new \Exception('Failed to update category.');
        }
    }

    public function delete(int $id)
    {
        try {
            $category = $this->category->findOrFail($id);
            $category->delete();
        } catch (ModelNotFoundException $e) {
            $this->logger->warning('Category not found: ' . $e->getMessage());
            throw new \Exception('Category not found.');
        } catch (QueryException $e) {
            $this->logger->error('Error deleting category: ' . $e->getMessage());
            throw new \Exception('Failed to delete category.');
        }
    }
}
