<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\DB;
use Psr\Log\LoggerInterface;

class CategoryService
{
    private $categoryRepository;
    private $logger;

    public function __construct(CategoryRepository $categoryRepository, LoggerInterface $logger)
    {
        $this->categoryRepository = $categoryRepository;
        $this->logger = $logger;
    }

    public function getAllCategories()
    {
        try {
            return $this->categoryRepository->getAll();
        } catch (\Exception $e) {
            $this->logger->error('Error in getAllCategories service: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getCategoryById(int $id)
    {
        try {
            return $this->categoryRepository->getById($id);
        } catch (\Exception $e) {
            $this->logger->error('Error in getCategoryById service: ' . $e->getMessage());
            throw $e;
        }
    }

    public function createCategory(array $categoryData)
    {
        try {
            DB::beginTransaction();
            $category = $this->categoryRepository->create($categoryData);
            DB::commit();
            return $category;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logger->error('Error in createCategory service: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateCategory(int $id, array $categoryData)
    {
        try {
            DB::beginTransaction();
            $category = $this->categoryRepository->update($id, $categoryData);
            DB::commit();
            return $category;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logger->error('Error in updateCategory service: ' . $e->getMessage());
            throw $e;
        }
    }

    public function deleteCategory(int $id)
    {
        try {
            DB::beginTransaction();
            $this->categoryRepository->delete($id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logger->error('Error in deleteCategory service: ' . $e->getMessage());
            throw $e;
        }
    }
}
