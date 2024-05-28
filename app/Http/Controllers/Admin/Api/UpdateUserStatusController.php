<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Requests\IsBannedRequest;
use App\Models\User;
use App\Services\Users\UserService;
use Illuminate\Http\JsonResponse;

class UpdateUserStatusController extends BaseController
{
    public function __construct(private readonly UserService $userService) {}

    public function __invoke(IsBannedRequest $request, User $user): JsonResponse
    {
        $result = $this->userService->update($request, $user);

        if (!$result) {
            return response()->json(['error' => 'Ошибка сохранения.'])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

        return response()->json(['success' => 'Успешно сохранено!!'])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

}
