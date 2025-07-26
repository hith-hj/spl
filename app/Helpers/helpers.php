<?php

declare(strict_types=1);

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

if (! function_exists('Success')) {
    function Success(
        string $msg = 'Success',
        array $payload = [],
        int $code = 200
    ): JsonResponse {
        $response = [
            'success' => true,
            'message' => $msg,
        ];
        if ($payload !== []) {
            $response['payload'] = $payload;
        }

        return response()->json($response, $code);
    }
}

if (! function_exists('Error')) {
    function Error(
        string $msg = 'Error',
        array $payload = [],
        int $code = 400
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => $msg,
        ];
        if ($payload !== []) {
            $response['payload'] = $payload;
        }

        return response()->json($response, $code);
    }
}

if (! function_exists('Exists')) {
    /**
     * check if argument exists
     * if true throw an exception
     *
     * @param  mixed  $argument
     * @param  mixed  $name
     */
    function Exists($argument, string $name = ''): void
    {
        if ($argument) {
            throw new Exception($name.' '.__('main.exists'), 400);
        }
    }
}

if (! function_exists('NotFound')) {
    /**
     * check if argument is empty
     * if true throw not found exception
     *
     * @param  mixed  $argument
     * @param  mixed  $name
     */
    function NotFound($argument, $name = '')
    {
        // return $this->empty($argument, $name, 'not found');
        if (
            ! $argument ||
            $argument === null ||
            empty($argument) ||
            (is_countable($argument) && count($argument) === 0)
        ) {
            throw new NotFoundHttpException(sprintf('%s %s', __("main.$name"), __('main.not found')));
        }
    }
}

if (! function_exists('Required')) {
    /**
     * check if argument is empty
     * if true throw required exception
     *
     * @param  mixed  $argument
     * @param  mixed  $name
     */
    function Required($argument, $name = '')
    {
        // return $this->empty($argument, $name, 'is required');
        if (
            ! $argument ||
            $argument === null ||
            empty($argument) ||
            (is_countable($argument) && count($argument) === 0)
        ) {
            throw new Exception(sprintf('%s %s', __("main.$name"), __('main.is required')));
        }
    }
}

if (! function_exists('Truthy')) {
    /**
     * throw exception if the condition is true
     *
     * @param  bool  $condition
     * @param  string  $message
     * @param  mixed  $name
     *
     * @throws Exception
     */
    function Truthy($condition, $message, ...$parameters): bool
    {
        if ($condition) {
            throw new Exception(__("main.$message"), ...$parameters);
        }

        return $condition;
    }
}

if (! function_exists('Falsy')) {
    /**
     * throw exception if the condition is false
     *
     * @param  bool  $condition
     * @param  string  $message
     * @param  mixed  $name
     *
     * @throws Exception
     */
    function Falsy($condition, $message, ...$parameters): bool
    {
        if (! $condition) {
            throw new Exception(__("main.$message"), ...$parameters);
        }

        return $condition;
    }
}
