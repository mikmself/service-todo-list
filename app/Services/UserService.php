<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Psr\Log\LoggerInterface;

class UserService
{
    private $userRepository;
    private $logger;

    public function __construct(UserRepository $userRepository, LoggerInterface $logger)
    {
        $this->userRepository = $userRepository;
        $this->logger = $logger;
    }

    public function getAllUsers()
    {
        try {
            return $this->userRepository->getAll();
        } catch (\Exception $e) {
            $this->logger->error('Error in getAllUsers service: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getUserById(int $id)
    {
        try {
            return $this->userRepository->getById($id);
        } catch (\Exception $e) {
            $this->logger->error('Error in getUserById service: ' . $e->getMessage());
            throw $e;
        }
    }

    public function createUser(array $userData)
    {
        try {
            DB::beginTransaction();
            $user = $this->userRepository->create($userData);
            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logger->error('Error in createUser service: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateUser(int $id, array $userData)
    {
        try {
            DB::beginTransaction();
            $user = $this->userRepository->update($id, $userData);
            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logger->error('Error in updateUser service: ' . $e->getMessage());
            throw $e;
        }
    }

    public function deleteUser(int $id)
    {
        try {
            DB::beginTransaction();
            $this->userRepository->delete($id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logger->error('Error in deleteUser service: ' . $e->getMessage());
            throw $e;
        }
    }
}
