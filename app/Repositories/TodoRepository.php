<?php

namespace App\Repositories;

use App\Models\Todo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Psr\Log\LoggerInterface;

class TodoRepository
{
    private $todo;
    private $logger;

    public function __construct(Todo $todo, LoggerInterface $logger)
    {
        $this->todo = $todo;
        $this->logger = $logger;
    }

    public function getAll()
    {
        try {
            return $this->todo->all();
        } catch (QueryException $e) {
            $this->logger->error('Error getting all todos: ' . $e->getMessage());
            throw new \Exception('Failed to retrieve todos.');
        }
    }

    public function getById(int $id)
    {
        try {
            return $this->todo->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            $this->logger->warning('Todo not found: ' . $e->getMessage());
            throw new \Exception('Todo not found.');
        } catch (QueryException $e) {
            $this->logger->error('Error getting todo by ID: ' . $e->getMessage());
            throw new \Exception('Failed to retrieve todo.');
        }
    }

    public function create(array $todoData)
    {
        try {
            return $this->todo->create($todoData);
        } catch (QueryException $e) {
            $this->logger->error('Error creating todo: ' . $e->getMessage());
            throw new \Exception('Failed to create todo.');
        }
    }

    public function update(int $id, array $todoData)
    {
        try {
            $todo = $this->todo->findOrFail($id);
            $todo->update($todoData);
            return $todo;
        } catch (ModelNotFoundException $e) {
            $this->logger->warning('Todo not found: ' . $e->getMessage());
            throw new \Exception('Todo not found.');
        } catch (QueryException $e) {
            $this->logger->error('Error updating todo: ' . $e->getMessage());
            throw new \Exception('Failed to update todo.');
        }
    }

    public function delete(int $id)
    {
        try {
            $todo = $this->todo->findOrFail($id);
            $todo->delete();
        } catch (ModelNotFoundException $e) {

            $this->logger->warning('Todo not found: ' . $e->getMessage());
            throw new \Exception('Todo not found.');
        } catch (QueryException $e) {
            $this->logger->error('Error deleting todo: ' . $e->getMessage());
            throw new \Exception('Failed to delete todo.');
        }
    }
}
