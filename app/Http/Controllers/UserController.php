<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;

class UserController extends Controller
{
    private $userService;
    private $logger;

    public function __construct(UserService $userService, LoggerInterface $logger)
    {
        $this->userService = $userService;
        $this->logger = $logger;
    }

    public function index(): JsonResponse
    {
        try {
            $users = $this->userService->getAllUsers();
            return $this->response(Response::HTTP_OK, 'Users retrieved successfully', $users);
        } catch (\Exception $e) {
            return $this->response(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $user = $this->userService->getUserById($id);
            return $this->response(Response::HTTP_OK, 'User retrieved successfully', $user);
        } catch (\Exception $e) {
            return $this->response(Response::HTTP_NOT_FOUND, $e->getMessage());
        }
    }

    public function store(CreateUserRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->createUser($request->validated());
            return $this->response(Response::HTTP_CREATED, 'User created successfully', $user);
        } catch (\Exception $e) {
            return $this->response(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        try {
            $user = $this->userService->updateUser($id, $request->validated());
            return $this->response(Response::HTTP_OK, 'User updated successfully', $user);
        } catch (\Exception $e) {
            return $this->response(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->userService->deleteUser($id);
            return $this->response(Response::HTTP_NO_CONTENT, 'User deleted successfully');
        } catch (\Exception $e) {
            return $this->response(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }
}
