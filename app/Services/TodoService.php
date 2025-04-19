<?php

namespace App\Services;

use App\Repositories\TodoRepository;
use Illuminate\Support\Facades\DB;
use Psr\Log\LoggerInterface;

class TodoService
{
    private $todoRepository;
    private $logger;

    public function __construct(TodoRepository $todoRepository, LoggerInterface $logger)
    {
        $this->todoRepository = $todoRepository;
        $this->logger = $logger;
    }

    public function getAllTodos()
    {
        try {
            return $this->todoRepository->getAll();
        } catch (\Exception $e) {
            $this->logger->error('Error in getAllTodos service: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getTodoById(int $id)
    {
        try {
            return $this->todoRepository->getById($id);
        } catch (\Exception $e) {
            $this->logger->error('Error in getTodoById service: ' . $e->getMessage());
            throw $e;
        }
    }

    public function createTodo(array $todoData)
    {
        try {
            DB::beginTransaction();
            $todo = $this->todoRepository->create($todoData);
            DB::commit();
            return $todo;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logger->error('Error in createTodo service: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateTodo(int $id, array $todoData)
    {
        try {
            DB::beginTransaction();
            $todo = $this->todoRepository->update($id, $todoData);
            DB::commit();
            return $todo;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logger->error('Error in updateTodo service: ' . $e->getMessage());
            throw $e;
        }
    }

    public function deleteTodo(int $id)
    {
        try {
            DB::beginTransaction();
            $this->todoRepository->delete($id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logger->error('Error in deleteTodo service: ' . $e->getMessage());
            throw $e;
        }
    }
}
