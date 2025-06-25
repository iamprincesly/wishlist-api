<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exceptions\HttpResponseException;

if (! function_exists('api_success')) {
    /**
     * Return a successful JSON response.
     *
     * @param  string  $message  = 'Request processed succesfully.'
     * @param  mixed  $data  = null
     * @param  int  $statusCode
     */
    function api_success(string $message = 'Request processed succesfully.', mixed $data = null, $statusCode = 200): JsonResponse
    {
        $res_data = ['status' => 'success', 'message' => $message];

        if (! is_null($data)) {
            $res_data['data'] = $data;
        }

        $res_data['api_version'] = api_version();

        return response()->json($res_data, $statusCode);
    }
}

if (! function_exists('api_failed_response')) {
    /**
     * Return a failed JSON response.
     *
     * @param  string  $message  = 'Request was not succesfully.'
     * @param  int  $statusCode
     * @param  mixed  $data  = null
     */
    function api_failed_response(string $message = 'Request was not succesful.', $statusCode = 400, mixed $data = null): JsonResponse
    {
        $res_data = ['status' => 'failed', 'message' => $message];

        if (! is_null($data)) {
            $res_data['data'] = $data;
        }

        $res_data['api_version'] = api_version();

        return response()->json($res_data, $statusCode);
    }
}

if (! function_exists('api_failed')) {
    /**
     * Return a failed JSON response.
     *
     * @param  string  $message  = 'Request was not succesfully.'
     * @param  int  $statusCode
     * @param  mixed  $data  = null
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    function api_failed(string $message = 'Request was not succesful.', $statusCode = 400, mixed $data = null): JsonResponse
    {
        throw new HttpResponseException(api_failed_response($message, $statusCode, $data));
    }
}

if (! function_exists('api_version')) {
    /**
     * Get the version of the api in use
     */
    function api_version(): ?string
    {
        return request()->get('api_version');
    }
}

if (! function_exists('ensureArrayOfType')) {
    /**
     * Ensure all element in array are all instance of a given class
     *
     * @param  array<int, mixed>  $items
     * @param  class-string  $classString
     *
     * @throws \InvalidArgumentException
     */
    function ensureArrayOfType(array $items, string $classString): void
    {
        foreach ($items as $item) {
            if (! $item instanceof $classString) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'Expected type %s but found %s',
                        $classString,
                        is_object($item) ? $item::class : gettype($item),
                    ),
                );
            }
        }
    }
}
