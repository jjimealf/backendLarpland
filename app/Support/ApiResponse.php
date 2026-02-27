<?php

namespace App\Support;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

trait ApiResponse
{
    protected function success(
        mixed $data = null,
        string $message = 'Request successful',
        int $status = 200,
        array $meta = []
    ): JsonResponse {
        $payload = [
            'message' => $message,
            'data' => $this->normalize($data),
        ];

        if ($meta !== []) {
            $payload['meta'] = $meta;
        }

        return response()->json($payload, $status);
    }

    protected function error(
        string $code,
        string $message,
        int $status,
        mixed $details = null
    ): JsonResponse {
        return response()->json([
            'error' => [
                'code' => $code,
                'message' => $message,
                'details' => $details,
            ],
        ], $status);
    }

    protected function paginated(
        LengthAwarePaginator $paginator,
        string $resourceClass,
        string $message = 'Request successful'
    ): JsonResponse {
        return $this->success(
            $resourceClass::collection($paginator->getCollection())->resolve(),
            $message,
            200,
            [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ]
        );
    }

    protected function noContent(): JsonResponse
    {
        return response()->json(null, 204);
    }

    private function normalize(mixed $data): mixed
    {
        if ($data instanceof JsonResource || $data instanceof AnonymousResourceCollection) {
            return $data->resolve();
        }

        return $data;
    }
}
