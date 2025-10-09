<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait ValidationHandler
{
    /**
     * Validation rules for the model
     *
     * @var array
     */
    protected $validationRules = [];

    /**
     * Custom validation messages
     *
     * @var array
     */
    protected $validationMessages = [];

    /**
     * Validate request
     *
     * @param Request $request
     * @param array|null $rules
     * @param array|null $messages
     * @return array|JsonResponse
     */
    protected function validateRequest(
        Request $request,
        ?array $rules = null,
        ?array $messages = null
    ) {
        $rules = $rules ?? $this->validationRules;
        $messages = $messages ?? $this->validationMessages;

        try {
            $validated = $request->validate($rules, $messages);
            return $validated;
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Validation error', 422, $e->errors());
        }
    }
}
