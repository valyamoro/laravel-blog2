<?php

namespace App\Services\Users;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

final class UserService
{
    public function __construct(private readonly UserRepository $userRepository) {}

    public function getAllWithPagination(
        Request $request,
        int $perPage,
    ): LengthAwarePaginator
    {
        return $this->userRepository->getAllWithPagination($request, $perPage);
    }

    public function create(UserRequest $request): ?User
    {
        $request->merge(['is_banned' => (bool)$request->input('is_banned')]);

        return $this->userRepository->create($request);
    }

    public function update(
        Request $request,
        User $user,
    ): ?User
    {
        $request->merge(['is_banned' => (bool)$request->input('is_banned')]);
        $request->merge(['password' => $request->filled('password') ? $request->input('password') : $user->password]);

        return $this->userRepository->update($request, $user);
    }

    public function destroy(User $user): ?bool
    {
        return $this->userRepository->destroy($user);
    }

}
