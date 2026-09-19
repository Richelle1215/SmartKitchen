<?php

namespace App\Http\Controllers;

use App\Services\AIService;
use Illuminate\Http\Request;

class AIAssistantController extends Controller
{
    public function __construct(protected AIService $aiService)
    {
    }

    public function index()
    {
        return view('ai.assistant');
    }

    public function chat(Request $request)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $message = trim($validated['message']);
        $isRegistered = auth()->check();

        $result = $this->aiService->generateResponse($message, $isRegistered);

        return response()->json([
            'success' => (bool) $result['success'],
            'mode' => $result['mode'],
            'response' => $result['response'],
            'error' => $result['error'] ?? null,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    public function getSubstitutions(Request $request)
    {
        $validated = $request->validate([
            'ingredient' => ['required', 'string', 'max:255'],
        ]);

        $ingredient = trim($validated['ingredient']);
        $result = $this->aiService->generateResponse(
            'Suggest substitutions for ' . $ingredient,
            auth()->check()
        );

        return response()->json([
            'ingredient' => $ingredient,
            'success' => (bool) $result['success'],
            'mode' => $result['mode'],
            'response' => $result['response'],
            'error' => $result['error'] ?? null,
        ]);
    }

    public function getTips(Request $request)
    {
        $validated = $request->validate([
            'technique' => ['required', 'string', 'max:255'],
        ]);

        $result = $this->aiService->generateResponse(
            'Give cooking tips for ' . trim($validated['technique']),
            auth()->check()
        );

        return response()->json([
            'success' => (bool) $result['success'],
            'mode' => $result['mode'],
            'response' => $result['response'],
            'error' => $result['error'] ?? null,
        ]);
    }

    public function getNutritionInfo(Request $request)
    {
        $validated = $request->validate([
            'ingredients' => ['required', 'array'],
            'servings' => ['required', 'integer', 'min:1'],
        ]);

        $result = $this->aiService->generateResponse(
            'Explain the nutrition for these ingredients: ' . json_encode($validated['ingredients']) . ' for ' . $validated['servings'] . ' servings.',
            auth()->check()
        );

        return response()->json([
            'success' => (bool) $result['success'],
            'mode' => $result['mode'],
            'response' => $result['response'],
            'error' => $result['error'] ?? null,
        ]);
    }

    public function suggestRecipes()
    {
        $result = $this->aiService->generateResponse(
            'Suggest recipe ideas based on ingredients I have at home.',
            auth()->check()
        );

        return response()->json([
            'success' => (bool) $result['success'],
            'mode' => $result['mode'],
            'response' => $result['response'],
            'error' => $result['error'] ?? null,
        ]);
    }
}
