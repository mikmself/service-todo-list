<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Psr\Log\LoggerInterface;

class UserRepository
{
    private $user;
    private $logger;

    public function __construct(User $user, LoggerInterface $logger)
    {
        $this->user = $user;
        $this->logger = $logger;
    }

    public function getAll()
    {
        try {
            return $this->user->all();
        } catch (QueryException $e) {
            $this->logger->error('Error getting all users: ' . $e->getMessage());
            throw new \Exception('Failed to retrieve users from database.');
        }
    }

    public function getById(int $id)
    {
        try {
            return $this->user->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            $this->logger->warning('User not found: ' . $e->getMessage());
            throw new \Exception('User not found.');
        } catch (QueryException $e) {
            $this->logger->error('Error getting user by ID: ' . $e->getMessage());
            throw new \Exception('Failed to retrieve user from database.');
        }
    }

    public function create(array $userData)
    {
        try {
            $userData['password'] = Hash::make($userData['password']);
            return $this->user->create($userData);
        } catch (QueryException $e) {
            $this->logger->error('Error creating user: ' . $e->getMessage());
            throw new \Exception('Failed to create user.');
        }
    }

    public function update(int $id, array $userData)
    {
        try {
            $user = $this->user->findOrFail($id);
            if (isset($userData['password'])) {
                $userData['password'] = Hash::make($userData['password']);
            }
            $user->update($userData);
            return $user;
        } catch (ModelNotFoundException $e) {
            $this->logger->warning('User not found: ' . $e->getMessage());
            throw new \Exception('User not found.');
        } catch (QueryException $e) {
            $this->logger->error('Error updating user: ' . $e->getMessage());
            throw new \Exception('Failed to update user.');
        }
    }

    public function delete(int $id)
    {
        try {
            $user = $this->user->findOrFail($id);
            $user->delete();
        } catch (ModelNotFoundException $e) {
            $this->logger->warning('User not found: ' . $e->getMessage());
            throw new \Exception('User not found.');
        } catch (QueryException $e) {
            $this->logger->error('Error deleting user: ' . $e->getMessage());
            throw new \Exception('Failed to delete user.');
        }
    }
}
