<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

trait ResponseHandler
{
    /**
     * Success response
     *
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    protected function successResponse(
        $data = null,
        string $message = 'Operation successful',
        int $code = 200
    ): JsonResponse {
        return response()->json(
            [
                'success' => true,
                'message' => $message,
                'data' => $data
            ],
            $code
        );
    }

    /**
     * Error response
     *
     * @param string $message
     * @param int $code
     * @param mixed $errors
     * @return JsonResponse
     */
    protected function errorResponse(
        string $message = 'An error has occurred',
        int $code = 400,
        $errors = null
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => $message
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Render view with data
     *
     * @param string|null $view
     * @param array $data
     * @return View
     */
    protected function renderView(?string $view = null, array $data = []): View
    {
        $viewToRender = $view ?? $this->view ?? $this->guessViewName();
        return view($viewToRender, $data);
    }
}
