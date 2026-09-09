<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Recipe;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user() || auth()->user()->role !== 'admin') {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }

    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_recipes' => Recipe::count(),
            'pending_reports' => Report::where('status', 'pending')->count(),
            'total_reports' => Report::count(),
            'active_users_today' => User::where('last_seen_at', '>=', now()->subDay())->count(),
        ];

        $recentUsers = User::latest()->take(5)->get();
        $recentRecipes = Recipe::latest()->take(5)->get();
        $pendingReports = Report::where('status', 'pending')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentRecipes', 'pendingReports'));
    }

    /**
     * Display users management
     */
    public function users()
    {
        $users = User::withCount('recipes')
                    ->withCount('comments')
                    ->latest()
                    ->paginate(20);

        return view('admin.users', compact('users'));
    }

    /**
     * Show user details
     */
    public function showUser(User $user)
    {
        $user->load(['recipes', 'comments', 'ratings']);

        return view('admin.user-details', compact('user'));
    }

    /**
     * Suspend/Ban user
     */
    public function suspendUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
            'days' => ['required', 'integer', 'min:1', 'max:365'],
        ]);

        $user->update([
            'is_suspended' => true,
            'suspended_reason' => $validated['reason'],
            'suspended_until' => now()->addDays($validated['days']),
        ]);

        return back()->with('success', "User {$user->name} suspended for {$validated['days']} days");
    }

    /**
     * Unsuspend user
     */
    public function unsuspendUser(User $user)
    {
        $user->update([
            'is_suspended' => false,
            'suspended_reason' => null,
            'suspended_until' => null,
        ]);

        return back()->with('success', "User {$user->name} unsuspended");
    }

    /**
     * Delete user
     */
    public function deleteUser(User $user)
    {
        $userName = $user->name;
        $user->recipes()->delete();
        $user->delete();

        return redirect()->route('admin.users')->with('success', "User {$userName} deleted");
    }

    /**
     * Display recipes management
     */
    public function recipes()
    {
        $recipes = Recipe::with('user')
                        ->withCount('ratings', 'comments')
                        ->latest()
                        ->paginate(20);

        return view('admin.recipes', compact('recipes'));
    }

    /**
     * Show recipe details
     */
    public function showRecipe(Recipe $recipe)
    {
        $recipe->load(['user', 'ingredients', 'instructions', 'ratings', 'comments']);

        return view('admin.recipe-details', compact('recipe'));
    }

    /**
     * Publish/Unpublish recipe
     */
    public function toggleRecipeStatus(Recipe $recipe)
    {
        $recipe->update(['is_published' => !$recipe->is_published]);

        return back()->with('success', 'Recipe status updated');
    }

    /**
     * Delete recipe
     */
    public function deleteRecipe(Recipe $recipe)
    {
        $title = $recipe->title;
        $recipe->delete();

        return redirect()->route('admin.recipes')->with('success', "Recipe '{$title}' deleted");
    }

    /**
     * Display reports
     */
    public function reports()
    {
        $reports = Report::with('reporter', 'reportable')
                        ->latest()
                        ->paginate(20);

        return view('admin.reports', compact('reports'));
    }

    /**
     * Show report details
     */
    public function showReport(Report $report)
    {
        return view('admin.report-details', compact('report'));
    }

    /**
     * Resolve report
     */
    public function resolveReport(Request $request, Report $report)
    {
        $validated = $request->validate([
            'action' => ['required', 'in:dismiss,remove_content,suspend_user'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $report->update([
            'status' => 'resolved',
            'admin_notes' => $validated['notes'],
            'resolved_at' => now(),
        ]);

        // Take action based on admin's decision
        if ($validated['action'] === 'remove_content') {
            if ($report->reportable_type === 'App\\Models\\Recipe') {
                $report->reportable->delete();
            } else if ($report->reportable_type === 'App\\Models\\Comment') {
                $report->reportable->delete();
            }
        } elseif ($validated['action'] === 'suspend_user') {
            $report->reporter->update([
                'is_suspended' => true,
                'suspended_until' => now()->addDays(7),
            ]);
        }

        return back()->with('success', 'Report resolved');
    }

    /**
     * Display statistics
     */
    public function statistics()
    {
        $stats = [
            'total_users' => User::count(),
            'total_recipes' => Recipe::count(),
            'total_ratings' => \App\Models\Rating::count(),
            'total_comments' => \App\Models\Comment::count(),
            'total_reports' => Report::count(),
            'avg_recipe_rating' => Recipe::avg('average_rating'),
        ];

        $userGrowth = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                         ->where('created_at', '>=', now()->subDays(30))
                         ->groupBy('date')
                         ->orderBy('date')
                         ->get();

        $recipeGrowth = Recipe::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                             ->where('created_at', '>=', now()->subDays(30))
                             ->groupBy('date')
                             ->orderBy('date')
                             ->get();

        return view('admin.statistics', compact('stats', 'userGrowth', 'recipeGrowth'));
    }

    /**
     * Send notification to user
     */
    public function sendNotification(Request $request, User $user)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $user->notifications()->create([
            'title' => $validated['title'],
            'message' => $validated['message'],
            'type' => 'admin',
        ]);

        return back()->with('success', 'Notification sent to user');
    }

    /**
     * Display activity logs
     */
    public function activityLogs()
    {
        $logs = \App\Models\ActivityLog::orderByDesc('created_at')->paginate(50);
        return view('admin.activity-logs', compact('logs'));
    }
}
