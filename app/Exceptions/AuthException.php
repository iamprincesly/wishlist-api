<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthException extends Exception
{
    public function __construct(protected $message = 'Failed to authenticated...', protected $code = 401)
    {
        parent::__construct($message, $code);
    }

    /**
     * Render authentication errors
     *
     * @throws \Throwable
     */
    public function render(Request $request): JsonResponse
    {
        return api_failed_response($this->message, $this->code);
    }
}
