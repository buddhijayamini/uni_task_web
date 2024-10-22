<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class Handler extends QueryException
{


protected function invalidJson($request, $exception)
{
    return response()->json([
        'message' => 'Invalid JSON data.',
        'error' => $exception->getMessage(),
    ], 422);
}

protected function renderHttpException(HttpExceptionInterface $exception)
{
    return response()->json([
        'message' => 'HTTP Exception occurred.',
        'error' => $exception->getMessage(),
    ], $exception->getStatusCode());
}
}
