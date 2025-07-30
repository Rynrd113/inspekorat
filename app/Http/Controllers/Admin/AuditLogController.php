<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Display a listing of the audit logs
     */
    public function index(Request $request)
    {
        $query = AuditLog::with('user')
            ->latest();

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('event', $request->action);
        }

        // Filter by model type
        if ($request->filled('model_type')) {
            $query->where('auditable_type', $request->model_type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);
            // Sanitize search input
            $search = preg_replace('/[^a-zA-Z0-9\s\-_.]/', '', $search);
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('event', 'like', "%{$search}%")
                      ->orWhere('auditable_type', 'like', "%{$search}%")
                      ->orWhereHas('user', function($userQuery) use ($search) {
                          $userQuery->where('name', 'like', "%{$search}%");
                      });
                });
            }
        }

        $auditLogs = $query->paginate(20);

        // Get filter options
        $users = User::select('id', 'name')->orderBy('name')->get();
        $actions = AuditLog::select('event')->distinct()->orderBy('event')->pluck('event');
        $modelTypes = AuditLog::select('auditable_type')->distinct()->orderBy('auditable_type')->pluck('auditable_type');

        return view('admin.audit-logs.index', compact('auditLogs', 'users', 'actions', 'modelTypes'));
    }

    /**
     * Display the specified audit log
     */
    public function show(AuditLog $auditLog)
    {
        $auditLog->load('user');
        return view('admin.audit-logs.show', compact('auditLog'));
    }

    /**
     * Get statistics for dashboard
     */
    public function stats()
    {
        $stats = [
            'total_activities' => AuditLog::count(),
            'activities_today' => AuditLog::whereDate('created_at', today())->count(),
            'activities_this_week' => AuditLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'activities_this_month' => AuditLog::whereMonth('created_at', now()->month)->count(),
            'most_active_users' => AuditLog::select('user_id')
                ->with('user:id,name')
                ->selectRaw('COUNT(*) as activity_count')
                ->groupBy('user_id')
                ->orderBy('activity_count', 'desc')
                ->limit(10)
                ->get(),
            'most_common_actions' => AuditLog::select('event')
                ->selectRaw('COUNT(*) as action_count')
                ->groupBy('event')
                ->orderBy('action_count', 'desc')
                ->limit(10)
                ->get()
                ->map(function($item) {
                    return (object)[
                        'event' => $item->event,
                        'action_count' => $item->action_count,
                        'action_label' => ucfirst($item->event) // Manual accessor since this is stdClass
                    ];
                }),
            'recent_activities' => AuditLog::with('user')
                ->latest()
                ->limit(10)
                ->get()
        ];

        return response()->json($stats);
    }

    /**
     * Export audit logs
     */
    public function export(Request $request)
    {
        $query = AuditLog::with('user');

        // Apply same filters as index
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('event', $request->action);
        }

        if ($request->filled('model_type')) {
            $query->where('auditable_type', $request->model_type);
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $auditLogs = $query->latest()->get();

        $filename = 'audit_logs_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($auditLogs) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID',
                'User',
                'Action',
                'Model Type',
                'Model ID',
                'IP Address',
                'User Agent',
                'Created At'
            ]);

            // CSV data
            foreach ($auditLogs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user->name ?? 'Unknown',
                    $log->action_label,
                    $log->model_name,
                    $log->model_id,
                    $log->ip_address,
                    $log->user_agent,
                    $log->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
