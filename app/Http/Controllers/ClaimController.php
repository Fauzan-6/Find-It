<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Item;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    // Ajukan klaim / verifikasi kepemilikan
    public function store(Request $request, Item $item)
    {
        $user = $request->user();

        abort_unless($item->moderation_status === 'approved' && $item->item_status === 'active', 404);

        if ($item->isOwnedBy($user)) {
            return back()->with('error', 'Anda tidak dapat mengklaim laporan milik sendiri.');
        }

        if (Claim::where('item_id', $item->id)->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'Anda sudah mengajukan klaim untuk barang ini.');
        }

        $data = $request->validate([
            'proof_description' => ['required', 'string', 'min:20', 'max:2000'],
        ], [
            'proof_description.min' => 'Jelaskan bukti kepemilikan minimal 20 karakter agar mudah diverifikasi.',
        ]);

        $item->claims()->create([
            'user_id'           => $user->id,
            'proof_description' => $data['proof_description'],
        ]);

        return back()->with('success', 'Klaim terkirim. Kontak pelapor akan terbuka setelah klaim disetujui.');
    }

    public function approve(Request $request, Claim $claim)
    {
        return $this->decide($request, $claim, 'approved', 'Klaim disetujui. Kontak Anda kini terlihat oleh pengaju klaim.');
    }

    public function reject(Request $request, Claim $claim)
    {
        return $this->decide($request, $claim, 'rejected', 'Klaim ditolak.');
    }

    private function decide(Request $request, Claim $claim, string $status, string $message)
    {
        // Hanya pelapor asli barang yang boleh memutuskan klaim
        abort_unless($claim->item->isOwnedBy($request->user()), 403);

        if ($claim->status !== 'pending') {
            return back()->with('error', 'Klaim ini sudah diputuskan.');
        }

        $claim->update(['status' => $status]);

        return back()->with('success', $message);
    }
}
