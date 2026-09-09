<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Recipe;
use App\Models\Comment;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Show report creation form
     */
    public function create(Request $request)
    {
        $type = $request->query('type'); // 'recipe' or 'comment'
        $id = $request->query('id');

        if ($type === 'recipe') {
            $item = Recipe::findOrFail($id);
        } elseif ($type === 'comment') {
            $item = Comment::findOrFail($id);
        } else {
            abort(404);
        }

        return view('reports.create', compact('item', 'type'));
    }

    /**
     * Store a new report
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reportable_type' => ['required', 'in:recipe,comment'],
            'reportable_id' => ['required', 'integer'],
            'reason' => ['required', 'in:spam,inappropriate,offensive,misleading,other'],
            'details' => ['required', 'string', 'max:500'],
        ]);

        $modelType = $validated['reportable_type'] === 'recipe' 
            ? 'App\\Models\\Recipe' 
            : 'App\\Models\\Comment';

        // Check if already reported
        $existing = Report::where('reportable_type', $modelType)
                         ->where('reportable_id', $validated['reportable_id'])
                         ->where('reporter_id', auth()->id())
                         ->first();

        if ($existing) {
            return back()->with('warning', 'You already reported this');
        }

        Report::create([
            'reporter_id' => auth()->id(),
            'reportable_type' => $modelType,
            'reportable_id' => $validated['reportable_id'],
            'reason' => $validated['reason'],
            'details' => $validated['details'],
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Report submitted. Thank you for helping keep the community safe!');
    }

    /**
     * Get report statistics
     */
    public function statistics()
    {
        $stats = [
            'total_reports' => Report::count(),
            'pending' => Report::where('status', 'pending')->count(),
            'resolved' => Report::where('status', 'resolved')->count(),
            'dismissed' => Report::where('status', 'dismissed')->count(),
        ];

        $byReason = Report::selectRaw('reason, COUNT(*) as count')
                         ->groupBy('reason')
                         ->get();

        return response()->json(compact('stats', 'byReason'));
    }
}
