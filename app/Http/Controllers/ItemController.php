<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    /* ===================== Katalog publik ===================== */

    // Beranda: semua barang (hilang + ditemukan)
    public function index(Request $request)
    {
        return $this->catalog($request, null, 'Barang hilang dan ditemukan di kampus', route('home'));
    }

    public function lost(Request $request)
    {
        return $this->catalog($request, 'hilang', 'Barang yang sedang dicari', route('items.lost'));
    }

    public function found(Request $request)
    {
        return $this->catalog($request, 'ditemukan', 'Barang yang sudah ditemukan', route('items.found'));
    }

    private function catalog(Request $request, ?string $type, string $title, string $action)
    {
        $filters = $request->only(['q', 'category', 'type', 'location']);
        if ($type) {
            $filters['type'] = $type; // paksa tipe pada halaman khusus
        }

        $items = Item::publicCatalog()
            ->with('user:id,name')
            ->filter($filters)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('items.index', [
            'items'      => $items,
            'type'       => $type,
            'title'      => $title,
            'action'     => $action,
            'categories' => config('findit.categories'),
            'locations'  => config('findit.locations'),
        ]);
    }

    /* ===================== Detail barang ===================== */

    public function show(Request $request, Item $item)
    {
        $user = $request->user();

        // Barang yang belum disetujui hanya boleh dilihat pemilik laporan & admin
        if ($item->moderation_status !== 'approved') {
            abort_unless($user && ($item->isOwnedBy($user) || $user->isAdmin()), 404);
        }

        $item->load('user:id,name');

        $myClaim = $user
            ? Claim::where('item_id', $item->id)->where('user_id', $user->id)->first()
            : null;

        return view('items.show', compact('item', 'myClaim'));
    }

    /* ===================== Lapor barang ===================== */

    public function create()
    {
        return view('items.create', [
            'categories' => config('findit.categories'),
            'locations'  => config('findit.locations'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'           => ['required', 'string', 'max:150'],
            'type'            => ['required', Rule::in(['hilang', 'ditemukan'])],
            'category'        => ['required', Rule::in(config('findit.categories'))],
            'description'     => ['required', 'string', 'max:2000'],
            'campus_location' => ['required', Rule::in(config('findit.locations'))],
            'date_occurred'   => ['required', 'date', 'before_or_equal:today'],
            'image'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $request->user()->items()->create($data); // moderation_status default: pending

        return redirect()->route('dashboard')
            ->with('success', 'Laporan terkirim. Admin akan meninjaunya sebelum tampil di katalog.');
    }

    /* ===================== Dashboard pengguna ===================== */

    public function dashboard(Request $request)
    {
        $user = $request->user();

        $myItems = $user->items()->withCount([
            'claims as pending_claims_count' => fn ($q) => $q->where('status', 'pending'),
        ])->latest()->get();

        $incomingClaims = Claim::with(['item', 'user'])
            ->whereHas('item', fn ($q) => $q->where('user_id', $user->id))
            ->latest()->get();

        $outgoingClaims = Claim::with(['item.user'])
            ->where('user_id', $user->id)
            ->latest()->get();

        return view('dashboard.index', compact('myItems', 'incomingClaims', 'outgoingClaims'));
    }

    // Pelapor menandai barangnya selesai
    public function resolve(Request $request, Item $item)
    {
        abort_unless($item->isOwnedBy($request->user()), 403);
        abort_unless($item->moderation_status === 'approved', 422, 'Hanya laporan yang disetujui yang bisa diselesaikan.');

        $item->update(['item_status' => 'resolved']);

        return back()->with('success', 'Laporan ditandai selesai dan dipindahkan dari katalog.');
    }
}
