@extends('layouts.app')

@section('content')
<div class="container py-12">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Reports Management</h1>

        <!-- Filter Tabs -->
        <div class="flex gap-4 mb-8 border-b border-gray-200">
            <a href="{{ route('admin.reports') }}" class="pb-2 border-b-2 border-blue-600 text-blue-600 font-semibold">
                All Reports
            </a>
            <a href="{{ route('admin.reports', ['status' => 'pending']) }}" class="pb-2 text-gray-600 hover:text-gray-900">
                Pending
            </a>
            <a href="{{ route('admin.reports', ['status' => 'resolved']) }}" class="pb-2 text-gray-600 hover:text-gray-900">
                Resolved
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Item</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Reason</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Reported By</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Reported</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($reports as $report)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3">
                                <p class="text-sm text-gray-900 font-semibold">
                                    {{ class_basename($report->reportable_type) }}
                                </p>
                                @if ($report->reportable)
                                    <p class="text-xs text-gray-600 truncate">{{ $report->reportable->title ?? $report->reportable->content ?? 'N/A' }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-3">
                                <span class="inline-block bg-orange-100 text-orange-800 px-2 py-1 rounded text-xs font-semibold">
                                    {{ ucfirst($report->reason) }}
                                </span>
                            </td>
                            <td class="px-6 py-3">
                                <a href="{{ route('admin.user-details', $report->reporter) }}" class="text-blue-600 hover:text-blue-700 text-sm">
                                    {{ $report->reporter->name }}
                                </a>
                            </td>
                            <td class="px-6 py-3 text-center">
                                @if ($report->status === 'pending')
                                    <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-semibold">
                                        Pending
                                    </span>
                                @elseif ($report->status === 'resolved')
                                    <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                                        Resolved
                                    </span>
                                @else
                                    <span class="inline-block bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">
                                        Dismissed
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-sm text-gray-600">
                                {{ $report->created_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-3 text-right space-x-2">
                                <a href="{{ route('admin.report-details', $report) }}" class="text-blue-600 hover:text-blue-700 text-sm font-semibold">
                                    Review
                                </a>
                                
                                @if ($report->status === 'pending')
                                    <form method="POST" action="{{ route('admin.resolve-report', $report) }}" style="display: inline;" 
                                          onsubmit="return confirm('Resolve this report?');">
                                        @csrf
                                        <input type="hidden" name="action" value="dismiss">
                                        <button type="submit" class="text-gray-600 hover:text-gray-700 text-sm font-semibold">
                                            Dismiss
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-600">No reports found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $reports->links() }}
        </div>
    </div>
</div>
@endsection
