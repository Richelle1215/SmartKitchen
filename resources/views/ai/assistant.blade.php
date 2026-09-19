@extends('layouts.app')

@section('content')
@php
    $liveAiEnabled = app(\App\Services\AIService::class)->isLiveEnabled();
@endphp
<div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-6">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-orange-600">SmartKitchen</p>
        <h1 class="mt-2 text-4xl font-bold text-gray-900">AI Cooking Assistant</h1>
        <p class="mt-2 text-gray-600">
            @auth
                Full AI assistance is available for your account.
            @else
                Guest mode is limited to quick cooking guidance and sample suggestions.
            @endauth
        </p>
        <div class="mt-4 flex items-center gap-3">
            <span id="assistant-status" class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $liveAiEnabled ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                {{ $liveAiEnabled ? 'Live AI mode' : 'Fallback mode' }}
            </span>
            <span id="assistant-status-detail" class="text-sm {{ $liveAiEnabled ? 'text-emerald-700' : 'text-amber-700' }}">
                {{ $liveAiEnabled ? 'Connected to OpenAI.' : 'Using built-in cooking guidance.' }}
            </span>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-[320px_1fr]">
        <aside class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-900">Quick prompts</h2>
            <div class="mt-4 space-y-2">
                <button type="button" class="quick-prompt w-full rounded-xl border border-orange-200 bg-orange-50 px-3 py-2 text-left text-sm font-medium text-orange-700 hover:bg-orange-100">I only have eggs, rice, and garlic.</button>
                <button type="button" class="quick-prompt w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">I don't have soy sauce.</button>
                <button type="button" class="quick-prompt w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">What can I cook with tomatoes and pasta?</button>
                <button type="button" class="quick-prompt w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">How do I make rice less sticky?</button>
                <button type="button" class="quick-prompt w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">Give me nutrition tips for a balanced meal.</button>
            </div>
        </aside>

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Chef chat</h2>
                    <span class="rounded-full bg-orange-100 px-2.5 py-1 text-xs font-semibold text-orange-700">
                        {{ auth()->check() ? 'Registered user' : 'Guest' }}
                    </span>
                </div>
            </div>

            <div id="ai-chat" class="h-[470px] space-y-4 overflow-y-auto bg-white p-5">
                <div class="flex justify-start">
                    <div class="max-w-xl rounded-2xl rounded-tl-none bg-gray-100 px-4 py-3 text-sm text-gray-700">
                        Ask for recipe ideas, substitutions, cooking tips, nutrition help, or quick troubleshooting.
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 p-4">
                <form id="ai-form" class="flex gap-3">
                    @csrf
                    <input
                        id="ai-message"
                        type="text"
                        name="message"
                        placeholder="Ask the kitchen assistant..."
                        class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 shadow-sm focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-200"
                    >
                    <button type="submit" class="rounded-xl bg-orange-500 px-5 py-3 font-semibold text-white hover:bg-orange-600 disabled:cursor-not-allowed disabled:bg-orange-300">
                        Send
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const chatBox = document.getElementById('ai-chat');
    const form = document.getElementById('ai-form');
    const input = document.getElementById('ai-message');
    const sendButton = form.querySelector('button[type="submit"]');
    const statusBadge = document.getElementById('assistant-status');
    const statusDetail = document.getElementById('assistant-status-detail');

    function updateStatus(success) {
        const live = success === true;
        statusBadge.textContent = live ? 'Live AI mode' : 'Fallback mode';
        statusBadge.className = 'inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ' + (live ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700');
        statusDetail.textContent = live ? 'Connected to OpenAI.' : 'Using built-in cooking guidance.';
        statusDetail.className = 'text-sm ' + (live ? 'text-emerald-700' : 'text-amber-700');
    }

    function addMessage(role, text) {
        const wrapper = document.createElement('div');
        wrapper.className = role === 'user' ? 'flex justify-end' : 'flex justify-start';

        const bubble = document.createElement('div');
        bubble.className = role === 'user'
            ? 'max-w-xl rounded-2xl rounded-tr-none bg-orange-500 px-4 py-3 text-sm text-white'
            : 'max-w-xl rounded-2xl rounded-tl-none bg-gray-100 px-4 py-3 text-sm text-gray-700';
        bubble.textContent = text;

        wrapper.appendChild(bubble);
        chatBox.appendChild(wrapper);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        const message = input.value.trim();
        if (!message) {
            return;
        }

        addMessage('user', message);
        input.value = '';
        sendButton.disabled = true;

        try {
            const response = await fetch('{{ route('ai.chat') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message })
            });

            const data = await response.json();
            const responseText = data.success ? data.response : (data.error || 'The assistant is unavailable right now.');
            updateStatus(Boolean(data.success));
            addMessage('assistant', responseText);
        } catch (error) {
            updateStatus(false);
            addMessage('assistant', 'The assistant could not be reached. Please try again in a moment.');
        } finally {
            sendButton.disabled = false;
            input.focus();
        }
    });

    document.querySelectorAll('.quick-prompt').forEach((button) => {
        button.addEventListener('click', () => {
            input.value = button.textContent.trim();
            input.focus();
            form.dispatchEvent(new Event('submit'));
        });
    });
</script>
@endsection
