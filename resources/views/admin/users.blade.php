@extends('layouts.app')

@section('content')
<div class="container py-12">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">User Management</h1>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Name</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Email</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold">Recipes</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold">Comments</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold">Status</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3">
                                <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                            </td>
                            <td class="px-6 py-3 text-gray-600">{{ $user->email }}</td>
                            <td class="px-6 py-3 text-center">{{ $user->recipes_count }}</td>
                            <td class="px-6 py-3 text-center">{{ $user->comments_count }}</td>
                            <td class="px-6 py-3 text-center">
                                @if ($user->is_suspended)
                                    <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-semibold">
                                        Suspended
                                    </span>
                                @else
                                    <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                                        Active
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-right space-x-2">
                                <a href="{{ route('admin.user-details', $user) }}" class="text-blue-600 hover:text-blue-700 text-sm font-semibold">
                                    View
                                </a>
                                
                                @if (!$user->is_suspended)
                                    <button onclick="openSuspendModal({{ $user->id }}, '{{ $user->name }}')" class="text-yellow-600 hover:text-yellow-700 text-sm font-semibold">
                                        Suspend
                                    </button>
                                @else
                                    <form method="POST" action="{{ route('admin.unsuspend-user', $user) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-700 text-sm font-semibold" onclick="return confirm('Unsuspend this user?')">
                                            Unsuspend
                                        </button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('admin.delete-user', $user) }}" style="display: inline;" 
                                      onsubmit="return confirm('Permanently delete this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700 text-sm font-semibold">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-600">No users found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $users->links() }}
        </div>
    </div>
</div>

<!-- Suspend Modal -->
<div id="suspendModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-md w-full">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Suspend User</h2>
        
        <form id="suspendForm" method="POST" action="" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">User: <span id="suspendUserName"></span></label>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Reason *</label>
                <textarea name="reason" required class="w-full px-4 py-2 border border-gray-300 rounded-lg" rows="3"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Duration (days) *</label>
                <input type="number" name="days" value="7" min="1" max="365" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">
                    Suspend
                </button>
                <button type="button" onclick="closeSuspendModal()" class="flex-1 bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openSuspendModal(userId, userName) {
    document.getElementById('suspendUserName').textContent = userName;
    document.getElementById('suspendForm').action = `/admin/users/${userId}/suspend`;
    document.getElementById('suspendModal').classList.remove('hidden');
}

function closeSuspendModal() {
    document.getElementById('suspendModal').classList.add('hidden');
}
</script>
@endsection
