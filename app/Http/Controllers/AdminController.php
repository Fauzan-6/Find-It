<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Moderasi: daftar laporan (default menampilkan yang pending)
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');
        abort_unless(in_array($status, ['pending', 'approved', 'rejected']), 404);

        $items = Item::with('user:id,name,email')
            ->where('moderation_status', $status)
            ->oldest()
            ->paginate(10)
            ->withQueryString();

        $counts = Item::selectRaw('moderation_status, count(*) as total')
            ->groupBy('moderation_status')->pluck('total', 'moderation_status');

        return view('admin.index', compact('items', 'status', 'counts'));
    }

    public function approve(Item $item)
    {
        $item->update(['moderation_status' => 'approved']);

        return back()->with('success', "Laporan \"{$item->title}\" disetujui dan tampil di katalog.");
    }

    public function reject(Item $item)
    {
        $item->update(['moderation_status' => 'rejected']);

        return back()->with('success', "Laporan \"{$item->title}\" ditolak.");
    }

    // Arsip: barang resolved atau ditolak
    public function archive(Request $request)
    {
        $filter = $request->query('filter', 'all'); // all | resolved | rejected

        $items = Item::with('user:id,name,email')
            ->where(function ($q) use ($filter) {
                if ($filter === 'resolved') {
                    $q->where('item_status', 'resolved');
                } elseif ($filter === 'rejected') {
                    $q->where('moderation_status', 'rejected');
                } else {
                    $q->where('item_status', 'resolved')->orWhere('moderation_status', 'rejected');
                }
            })
            ->latest('updated_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.archive', compact('items', 'filter'));
    }
}
