<?php

namespace Tests\Unit\Http\Responses;

use App\Http\Responses\ApiResponse;
use PHPUnit\Framework\TestCase;

class ApiResponseTest extends TestCase
{
    public function test_success_structure(): void
    {
        $data = ['id' => 1, 'name' => 'Test'];
        $meta = ['total' => 1];
        $message = 'Успешно завершено';

        $response = ApiResponse::success($data, $meta, $message);
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($data, $content['data']);
        $this->assertEquals($meta, $content['meta']);
        $this->assertEquals(200, $content['status']['code']);
        $this->assertEquals($message, $content['status']['message']);
    }

    public function test_error_structure(): void
    {
        $statusCode = 403;
        $message = 'Ошибка доступа';
        $description = 'У вас нет прав';

        $response = ApiResponse::error($statusCode, $message, $description);
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals($statusCode, $content['status']['code']);
        $this->assertEquals($message, $content['status']['message']);
        $this->assertEquals($description, $content['status']['description']);
    }

    public function test_error_default_description(): void
    {
        $response = ApiResponse::error(400, 'Bad Request');
        $content = json_decode($response->getContent(), true);

        $this->assertEquals('Bad Request', $content['status']['description']);
    }
}
