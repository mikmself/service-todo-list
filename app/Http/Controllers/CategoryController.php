<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;

class CategoryController extends Controller
{
    private $categoryService;
    private $logger;

    public function __construct(CategoryService $categoryService, LoggerInterface $logger)
    {
        $this->categoryService = $categoryService;
        $this->logger = $logger;
    }

    public function index(): JsonResponse
    {
        try {
            $categories = $this->categoryService->getAllCategories();
            return $this->response(Response::HTTP_OK, 'Categories retrieved successfully', $categories);
        } catch (\Exception $e) {
            return $this->response(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $category = $this->categoryService->getCategoryById($id);
            return $this->response(Response::HTTP_OK, 'Category retrieved successfully', $category);
        } catch (\Exception $e) {
            return $this->response(Response::HTTP_NOT_FOUND, $e->getMessage());
        }
    }

    public function store(CreateCategoryRequest $request): JsonResponse
    {
        try {
            $category = $this->categoryService->createCategory($request->validated());
            return $this->response(Response::HTTP_CREATED, 'Category created successfully', $category);
        } catch (\Exception $e) {
            return $this->response(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    public function update(UpdateCategoryRequest $request, int $id): JsonResponse
    {
        try {
            $category = $this->categoryService->updateCategory($id, $request->validated());
            return $this->response(Response::HTTP_OK, 'Category updated successfully', $category);
        } catch (\Exception $e) {
            return $this->response(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->categoryService->deleteCategory($id);
            return $this->response(Response::HTTP_NO_CONTENT, 'Category deleted successfully');
        } catch (\Exception $e) {
            return $this->response(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }
}
