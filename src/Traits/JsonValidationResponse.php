<?php

namespace KFoobar\Restful\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

trait JsonValidationResponse
{
    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = [];

        foreach ($validator->errors()->toArray() as $error => $messages) {
            $errors[$error] = isset($messages[0]) ? $messages[0] : $messages;
        }

        throw new HttpResponseException(
            response()->error($errors, 'Input validation failed', JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
