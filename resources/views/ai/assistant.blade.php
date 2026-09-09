@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-orange-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-2 flex items-center justify-center">
                <span class="text-5xl mr-3">🤖</span>
                AI Cooking Assistant
            </h1>
            <p class="text-gray-600 text-lg">Get help with recipes, cooking techniques, and ingredient substitutions</p>
        </div>

        <!-- Main Chat Interface -->
        <div class="bg-white rounded-lg shadow-xl overflow-hidden mb-8">
            <!-- Chat History -->
            <div id="chat-history" class="h-96 overflow-y-auto p-6 bg-gray-50 border-b border-gray-200 space-y-4">
                <div class="flex gap-3">
                    <div class="w-8 h-8 rounded-full bg-orange-500 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                        🤖
                    </div>
                    <div class="bg-white rounded-lg p-4 shadow-sm max-w-xs">
                        <p class="text-gray-800 text-sm">👋 Hi! I'm your AI Cooking Assistant. How can I help you today? I can help with:</p>
                        <ul class="text-sm text-gray-700 mt-2 space-y-1">
                            <li>🔄 Ingredient substitutions</li>
                            <li>👨‍🍳 Cooking techniques and tips</li>
                            <li>🥘 Recipe suggestions</li>
                            <li>📊 Nutritional information</li>
                            <li>🥗 Pantry-based recipes</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="p-6 bg-white">
                <form id="chat-form" class="flex gap-3">
                    <input type="text" id="message-input" placeholder="Ask me anything about cooking..." 
                           class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                           autocomplete="off">
                    <button type="submit" class="px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg transition">
                        Send
                    </button>
                </form>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <button onclick="askAssistant('What are some substitutes for butter?')" 
                    class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition text-left">
                <div class="text-3xl mb-2">🔄</div>
                <h3 class="font-bold text-gray-900">Substitutions</h3>
                <p class="text-sm text-gray-600">Find ingredient alternatives</p>
            </button>

            <button onclick="askAssistant('Give me cooking tips for grilling')" 
                    class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition text-left">
                <div class="text-3xl mb-2">👨‍🍳</div>
                <h3 class="font-bold text-gray-900">Cooking Tips</h3>
                <p class="text-sm text-gray-600">Learn techniques & best practices</p>
            </button>

            @auth
                <button onclick="window.location.href='{{ route('pantry.index') }}'" 
                        class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition text-left">
                    <div class="text-3xl mb-2">📦</div>
                    <h3 class="font-bold text-gray-900">My Pantry</h3>
                    <p class="text-sm text-gray-600">Manage your ingredients</p>
                </button>
            @else
                <a href="{{ route('login') }}" 
                   class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition text-left">
                    <div class="text-3xl mb-2">📦</div>
                    <h3 class="font-bold text-gray-900">My Pantry</h3>
                    <p class="text-sm text-gray-600">Login to use pantry</p>
                </a>
            @endauth

            <button onclick="askAssistant('How do I calculate nutrition facts?')" 
                    class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition text-left">
                <div class="text-3xl mb-2">📊</div>
                <h3 class="font-bold text-gray-900">Nutrition</h3>
                <p class="text-sm text-gray-600">Get nutritional information</p>
            </button>
        </div>

        <!-- Features Section -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">How I Can Help</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-bold text-gray-900 mb-2 flex items-center">
                        <span class="text-2xl mr-2">🔄</span>
                        Ingredient Substitutions
                    </h3>
                    <p class="text-gray-600">Can't find an ingredient? I'll suggest alternatives with the right ratios.</p>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 mb-2 flex items-center">
                        <span class="text-2xl mr-2">👨‍🍳</span>
                        Cooking Techniques
                    </h3>
                    <p class="text-gray-600">Learn proper techniques for boiling, frying, baking, grilling, steaming, and more.</p>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 mb-2 flex items-center">
                        <span class="text-2xl mr-2">🥘</span>
                        Recipe Suggestions
                    </h3>
                    <p class="text-gray-600">Tell me what you have, and I'll suggest recipes you can make right now.</p>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 mb-2 flex items-center">
                        <span class="text-2xl mr-2">📊</span>
                        Nutrition Facts
                    </h3>
                    <p class="text-gray-600">Calculate calories, protein, carbs, and fat for any recipe.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function askAssistant(message) {
    document.getElementById('message-input').value = message;
    document.getElementById('chat-form').dispatchEvent(new Event('submit'));
}

document.getElementById('chat-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const message = document.getElementById('message-input').value;
    if (!message.trim()) return;

    // Add user message to chat
    const chatHistory = document.getElementById('chat-history');
    const userMessageDiv = document.createElement('div');
    userMessageDiv.className = 'flex gap-3 justify-end';
    userMessageDiv.innerHTML = `
        <div class="bg-orange-500 text-white rounded-lg p-4 shadow-sm max-w-xs">
            <p class="text-sm">${message}</p>
        </div>
        <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
            👤
        </div>
    `;
    chatHistory.appendChild(userMessageDiv);

    // Clear input
    document.getElementById('message-input').value = '';

    // Send to server
    try {
        const response = await fetch('{{ route("ai.chat") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ message })
        });

        const data = await response.json();

        // Add AI response
        const aiMessageDiv = document.createElement('div');
        aiMessageDiv.className = 'flex gap-3';
        aiMessageDiv.innerHTML = `
            <div class="w-8 h-8 rounded-full bg-orange-500 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                🤖
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm max-w-xs">
                <p class="text-gray-800 text-sm">${data.response}</p>
            </div>
        `;
        chatHistory.appendChild(aiMessageDiv);

        // Scroll to bottom
        chatHistory.scrollTop = chatHistory.scrollHeight;
    } catch (error) {
        console.error('Error:', error);
    }
});
</script>
@endsection
