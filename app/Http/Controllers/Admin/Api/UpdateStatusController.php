<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Requests\UpdateStatusRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class UpdateStatusController extends BaseController
{
    public function __invoke(UpdateStatusRequest $request, string $tableName, int $id): JsonResponse
    {
        $statusName = $request->input('status_name');

        DB::table($tableName)
            ->where('id', $id)
            ->update([$statusName => $request->input($statusName)]);

        return response()->json(['success' => 'Успешно сохранено!'], Response::HTTP_OK);
    }

}
