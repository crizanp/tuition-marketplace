<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TutorKyc;
use App\Models\Tutor;

class AdminKycController extends Controller
{
    public function index(Request $request)
    {
        $query = TutorKyc::with('tutor');
        
        // Filter by status if provided
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        // Search by tutor name or email
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $kycApplications = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.kyc.index', compact('kycApplications'));
    }
    
    public function show($id)
    {
        $kyc = TutorKyc::with('tutor')->findOrFail($id);
        return view('admin.kyc.show', compact('kyc'));
    }
    
    public function approve(Request $request, $id)
    {
        $kyc = TutorKyc::findOrFail($id);
        // Approve (allow re-approving after rejection)
        $kyc->update([
            'status' => 'approved',
            'reviewed_at' => now(),
            'rejection_reason' => null
        ]);

        // Update tutor status to active
        if ($kyc->tutor) {
            $kyc->tutor->update(['status' => 'active']);
        }

        return redirect()->route('admin.kyc.show', $id)->with('success', 'KYC application approved successfully.');
    }
    
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);
        
        $kyc = TutorKyc::findOrFail($id);
        // Reject (allow rejecting even after approval)
        $kyc->update([
            'status' => 'rejected',
            'reviewed_at' => now(),
            'rejection_reason' => $request->rejection_reason
        ]);

        // Optionally set tutor to inactive when rejected
        if ($kyc->tutor) {
            $kyc->tutor->update(['status' => 'inactive']);
        }

        return redirect()->route('admin.kyc.show', $id)->with('success', 'KYC application rejected.');
    }
}
