<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentApproval;
use Illuminate\Http\Request;

class ContentApprovalController extends Controller
{
    /**
     * Display a listing of content approvals
     */
    public function index(Request $request)
    {
        $approvals = ContentApproval::with(['submitter', 'model'])
            ->latest('created_at')
            ->paginate(20);

        return view('admin.approvals.index', compact('approvals'));
    }

    /**
     * Display the specified approval
     */
    public function show(ContentApproval $approval)
    {
        $approval->load(['submitter', 'approver', 'rejector', 'model']);
        return view('admin.approvals.show', compact('approval'));
    }

    /**
     * Approve content
     */
    public function approve(Request $request, ContentApproval $approval)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000'
        ]);

        if (!$approval->isPending()) {
            return redirect()->back()->with('error', 'Konten sudah diproses sebelumnya.');
        }

        $approval->approve($request->notes);

        return redirect()->route('admin.approvals.index')
            ->with('success', 'Konten berhasil disetujui.');
    }

    /**
     * Reject content
     */
    public function reject(Request $request, ContentApproval $approval)
    {
        $request->validate([
            'notes' => 'required|string|max:1000'
        ]);

        if (!$approval->isPending()) {
            return redirect()->back()->with('error', 'Konten sudah diproses sebelumnya.');
        }

        $approval->reject($request->notes);

        return redirect()->route('admin.approvals.index')
            ->with('success', 'Konten berhasil ditolak.');
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'approvals' => 'required|array',
            'approvals.*' => 'exists:content_approvals,id',
            'notes' => 'nullable|string|max:1000'
        ]);

        $approvals = ContentApproval::whereIn('id', $request->approvals)
            ->where('status', 'pending')
            ->get();

        $processed = 0;
        
        foreach ($approvals as $approval) {
            if ($request->action === 'approve') {
                $approval->approve($request->notes);
            } else {
                $approval->reject($request->notes);
            }
            $processed++;
        }

        $actionText = $request->action === 'approve' ? 'disetujui' : 'ditolak';
        
        return redirect()->route('admin.approvals.index')
            ->with('success', "{$processed} konten berhasil {$actionText}.");
    }

    /**
     * Get statistics
     */
    public function stats()
    {
        $stats = [
            'total_pending' => ContentApproval::where('status', 'pending')->count(),
            'total_approved' => ContentApproval::where('status', 'approved')->count(),
            'total_rejected' => ContentApproval::where('status', 'rejected')->count(),
            'pending_by_type' => ContentApproval::where('status', 'pending')
                ->select('model_type')
                ->selectRaw('COUNT(*) as count')
                ->groupBy('model_type')
                ->get(),
            'approval_rate' => ContentApproval::selectRaw('
                ROUND(
                    (COUNT(CASE WHEN status = "approved" THEN 1 END) * 100.0 / COUNT(*)), 
                    2
                ) as rate
            ')->first()->rate ?? 0,
            'avg_processing_time' => ContentApproval::whereNotNull('approved_at')
                ->orWhereNotNull('rejected_at')
                ->selectRaw('
                    AVG(
                        CASE 
                            WHEN approved_at IS NOT NULL 
                            THEN TIMESTAMPDIFF(HOUR, submitted_at, approved_at)
                            ELSE TIMESTAMPDIFF(HOUR, submitted_at, rejected_at)
                        END
                    ) as avg_hours
                ')
                ->first()->avg_hours ?? 0
        ];

        return response()->json($stats);
    }
}
