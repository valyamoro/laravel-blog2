<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Requests\IsBannedRequest;
use App\Models\AdminUser;
use App\Services\AdminUsers\AdminUserService;
use Illuminate\Http\JsonResponse;

class UpdateAdminUserStatusController extends BaseController
{
    public function __construct(private readonly AdminUserService $adminUserService) {}

    public function __invoke(
        IsBannedRequest $request,
        AdminUser $adminUser,
    ): JsonResponse
    {
        $result = $this->adminUserService->update($request, $adminUser);

        if (!$result) {
            return response()->json(['error' => 'Ошибка сохранения.'])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

        return response()->json(['success' => 'Успешно сохранено!!'])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

}
