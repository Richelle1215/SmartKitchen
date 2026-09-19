<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AIService
{
    public function isLiveEnabled(): bool
    {
        return !empty(config('services.openai.api_key', env('OPENAI_API_KEY')));
    }

    public function generateResponse(string $message, bool $isRegisteredUser = false): array
    {
        $prompt = $this->buildPrompt($message, $isRegisteredUser);

        $apiKey = config('services.openai.api_key', env('OPENAI_API_KEY'));
        if (empty($apiKey)) {
            return [
                'success' => false,
                'mode' => $isRegisteredUser ? 'registered' : 'guest',
                'response' => $this->fallbackResponse($message, $isRegisteredUser),
                'error' => 'AI API key is not configured.',
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => $this->systemPrompt($isRegisteredUser)],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.7,
                'max_tokens' => 400,
            ]);

            if ($response->failed()) {
                return [
                    'success' => false,
                    'mode' => $isRegisteredUser ? 'registered' : 'guest',
                    'response' => $this->fallbackResponse($message, $isRegisteredUser),
                    'error' => 'AI service unavailable: ' . $response->json('error.message', 'Unknown error'),
                ];
            }

            $reply = $response->json('choices.0.message.content');

            if (!is_string($reply) || trim($reply) === '') {
                throw new \RuntimeException('Empty AI response received.');
            }

            return [
                'success' => true,
                'mode' => $isRegisteredUser ? 'registered' : 'guest',
                'response' => trim($reply),
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'mode' => $isRegisteredUser ? 'registered' : 'guest',
                'response' => $this->fallbackResponse($message, $isRegisteredUser),
                'error' => 'Unable to process your request right now: ' . $e->getMessage(),
            ];
        }
    }

    protected function buildPrompt(string $message, bool $isRegisteredUser): string
    {
        $normalized = trim($message);

        if ($isRegisteredUser) {
            return "You are a cooking assistant for SmartKitchen. Help with recipe suggestions, ingredient substitutions, cooking techniques, nutrition, meal planning, and troubleshooting. User request: {$normalized}";
        }

        return "Provide a concise, helpful cooking answer for a guest user. Keep it brief and practical. User request: {$normalized}";
    }

    protected function systemPrompt(bool $isRegisteredUser): string
    {
        if ($isRegisteredUser) {
            return 'You are an expert culinary assistant. Answer in clear, friendly, practical cooking guidance. Cover recipe suggestions, substitutions, technique explanations, meal-planning help, troubleshooting, nutrition questions, and ingredient-based cooking ideas. Do not mention that you are an AI model. Keep responses concise but useful.';
        }

        return 'You are a culinary assistant for a guest user. Provide brief but helpful cooking advice. Focus on practical, easy suggestions and safe cooking guidance. Keep answers short and accessible.';
    }

    protected function fallbackResponse(string $message, bool $isRegisteredUser): string
    {
        $text = strtolower(trim($message));

        if (Str::contains($text, ['eggs', 'rice', 'garlic'])) {
            return 'Try garlic fried rice with scrambled eggs, or a simple egg-and-rice skillet with garlic and a little seasoning.';
        }

        if (Str::contains($text, ['soy sauce', 'soy', 'sauce'])) {
            return 'Use coconut aminos, tamari, salt + a splash of vinegar, or Worcestershire depending on the dish. For stir-fries, tamari is the closest substitute.';
        }

        if (Str::contains($text, ['substitut', 'replacement', 'instead'])) {
            return 'Start by matching the ingredient’s role: savory depth, sweetness, acid, or thickening. Common swaps are tamari for soy sauce, yogurt for sour cream, and stock for water in sauces.';
        }

        if (Str::contains($text, ['nutrition', 'calories', 'healthy'])) {
            return 'Balance the meal with a lean protein, a fiber-rich carb, and vegetables. For a quick check, aim for protein, fiber, and color in each plate.';
        }

        if ($isRegisteredUser) {
            return 'I can help with recipe ideas, substitutions, techniques, nutrition, and quick troubleshooting. Tell me what ingredients you have or what problem you are having.';
        }

        return 'I can suggest recipes, substitutions, and basic cooking tips. Share the ingredients you have or what you are trying to make.';
    }
}
