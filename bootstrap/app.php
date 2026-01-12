<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use App\Http\Responses\ApiResponse;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectTo(
            guests: fn(\Illuminate\Http\Request $request) => $request->is('api/*') ? null : route('login')
        );
        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(fn($request) => $request->is('api/*'));

        $exceptions->render(function (\Throwable $e, $request) {
            if (!$request->is('api/*')) {
                return null;
            }

            $statusCode = match (true) {
                $e instanceof HttpExceptionInterface => $e->getStatusCode(),
                $e instanceof ValidationException => ResponseAlias::HTTP_UNPROCESSABLE_ENTITY, // 422
                $e instanceof AuthenticationException => ResponseAlias::HTTP_UNAUTHORIZED,      // 401
                $e instanceof AuthorizationException => ResponseAlias::HTTP_FORBIDDEN,         // 403
                $e instanceof ModelNotFoundException => ResponseAlias::HTTP_NOT_FOUND,         // 404
                default => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,                         // 500
            };

            $statusMessages = [
                400 => 'Некорректный запрос',
                401 => 'Не авторизован',
                403 => 'Доступ запрещён',
                404 => 'Ресурс не найден',
                405 => 'Метод не разрешён',
                422 => 'Ошибка валидации',
                429 => 'Слишком много запросов',
                500 => 'Внутренняя ошибка сервера',
                503 => 'Сервис недоступен',
            ];

            $httpStatusText = $statusMessages[$statusCode] ?? 'Ошибка сервера';

            return ApiResponse::error(
                statusCode: $statusCode,
                message: match (true) {
                    $e instanceof ValidationException => 'Ошибка валидации входных данных',
                    $e instanceof AuthenticationException => 'Вы не авторизованы',
                    $e instanceof AuthorizationException => 'У вас недостаточно прав для выполнения этого действия',
                    $e instanceof ModelNotFoundException => 'Запрашиваемый ресурс не найден в базе данных',
                    default => $e->getMessage() ?: $httpStatusText
                },
                description: $httpStatusText,
                data: $e instanceof ValidationException ? $e->errors() : []
            );
        });
    })->create();
