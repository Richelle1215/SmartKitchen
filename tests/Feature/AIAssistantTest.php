<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AIAssistantTest extends TestCase
{
    use RefreshDatabase;

    public function test_ai_assistant_page_loads(): void
    {
        $response = $this->get(route('ai.assistant'));

        $response->assertOk();
        $response->assertSee('AI Cooking Assistant');
        $response->assertSee('Fallback mode');
    }

    public function test_guest_chat_uses_limited_mode_response(): void
    {
        $response = $this->postJson(route('ai.chat'), [
            'message' => 'I only have eggs, rice, and garlic.',
        ]);

        $response->assertOk();
        $response->assertJsonPath('mode', 'guest');
        $response->assertJsonPath('response', fn ($value) => is_string($value) && !empty($value));
    }

    public function test_registered_user_chat_uses_backend_service(): void
    {
        Http::fake([
            'https://api.openai.com/*' => Http::response([
                'choices' => [[
                    'message' => ['content' => 'Try garlic fried rice with scrambled eggs.'],
                ]],
            ], 200),
        ]);

        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson(route('ai.chat'), [
                'message' => 'I only have eggs, rice, and garlic.',
            ]);

        $response->assertOk();
        $response->assertJsonPath('mode', 'registered');
        $response->assertJsonPath('response', 'Try garlic fried rice with scrambled eggs.');
    }

    public function test_ai_service_handles_failed_provider_response(): void
    {
        Http::fake([
            'https://api.openai.com/*' => Http::response(['error' => ['message' => 'Bad gateway']], 502),
        ]);

        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson(route('ai.chat'), [
                'message' => 'I do not have soy sauce.',
            ]);

        $response->assertOk();
        $response->assertJsonPath('success', false);
        $response->assertJsonPath('error', fn ($value) => is_string($value) && !empty($value));
    }
}
