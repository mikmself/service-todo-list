<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Services\TodoService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;

class TodoController extends Controller
{
    private $todoService;
    private $logger;

    public function __construct(TodoService $todoService, LoggerInterface $logger)
    {
        $this->todoService = $todoService;
        $this->logger = $logger;
    }

    public function index(): JsonResponse
    {
        try {
            $todos = $this->todoService->getAllTodos();
            return $this->response(Response::HTTP_OK, 'Todos retrieved successfully', $todos);
        } catch (\Exception $e) {
            return $this->response(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $todo = $this->todoService->getTodoById($id);
            return $this->response(Response::HTTP_OK, 'Todo retrieved successfully', $todo);
        } catch (\Exception $e) {
            return $this->response(Response::HTTP_NOT_FOUND, $e->getMessage());
        }
    }

    public function store(CreateTodoRequest $request): JsonResponse
    {
        try {
            $todo = $this->todoService->createTodo($request->validated());
            return $this->response(Response::HTTP_CREATED, 'Todo created successfully', $todo);
        } catch (\Exception $e) {
            return $this->response(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    public function update(UpdateTodoRequest $request, int $id): JsonResponse
    {
        try {
            $todo = $this->todoService->updateTodo($id, $request->validated());
            return $this->response(Response::HTTP_OK, 'Todo updated successfully', $todo);
        } catch (\Exception $e) {
            return $this->response(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->todoService->deleteTodo($id);
            return $this->response(Response::HTTP_NO_CONTENT, 'Todo deleted successfully');
        } catch (\Exception $e) {
            return $this->response(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }
}
