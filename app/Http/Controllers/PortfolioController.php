<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\User;
use App\Http\Requests\StorePortfolioRequest;
use App\Http\Requests\UpdatePortfolioRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PortfolioController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Inisialisasi query
        $query = Portfolio::query();

        // Filter berdasarkan role user
        if (auth()->user()->role === 'student') {
            // Siswa hanya lihat milik sendiri
            $query->where('student_id', auth()->user()->id);
        }

        // Pencarian berdasarkan judul atau nama siswa
        if ($search = $request->get('search')) {
            // Sanitasi input untuk mencegah wildcard injection
            $search = str_replace(['%', '_'], ['\\%', '\\_'], $search);
            $query->where('title', 'like', "%{$search}%")
                  ->orWhereHas('student', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        // Filter berdasarkan status verifikasi
        if ($status = $request->get('status')) {
            if (in_array($status, ['pending', 'approved', 'rejected'])) {
                $query->where('verified_status', $status);
            }
        }

        // Filter berdasarkan tipe portfolio
        if ($type = $request->get('type')) {
            if (in_array($type, ['prestasi', 'karya', 'sertifikat'])) {
                $query->where('type', $type);
            }
        }

        $portfolios = $query->with('student', 'verifiedByUser')
                            ->latest('created_at')
                            ->paginate(10);

        return view('portfolios.index', compact('portfolios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check authorization
        $this->authorize('create', Portfolio::class);

        // Ambil akun user yang berrole 'student' untuk dipilih (admin/teacher dapat memilih)
        $students = User::where('role', 'student')->get();
        return view('portfolios.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePortfolioRequest $request)
    {
        // Validasi sudah dilakukan di StorePortfolioRequest
        $validated = $request->validated();

        // Upload file
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            
            // Generate nama file yang aman
            $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            
            // Simpan ke storage/app/public/portfolios
            $filePath = $file->storeAs('portfolios', $fileName, 'public');
            
            $validated['file_path'] = $filePath;
        }

        // Set verified_status
        $validated['verified_status'] = 'pending';

        // Buat portfolio baru
        $portfolio = Portfolio::create($validated);

        return redirect()->route('portfolios.show', $portfolio)
                       ->with('success', 'Portfolio berhasil dibuat! Menunggu verifikasi dari guru.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Portfolio $portfolio)
    {
        // Check authorization
        $this->authorize('view', $portfolio);

        return view('portfolios.show', compact('portfolio'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Portfolio $portfolio)
    {
        // Check authorization
        $this->authorize('update', $portfolio);

        return view('portfolios.edit', compact('portfolio'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePortfolioRequest $request, Portfolio $portfolio)
    {
        // Check authorization
        $this->authorize('update', $portfolio);

        $validated = $request->validated();

        // Upload file jika ada yang baru
        if ($request->hasFile('file')) {
            // Hapus file lama
            if ($portfolio->file_path && Storage::disk('public')->exists($portfolio->file_path)) {
                Storage::disk('public')->delete($portfolio->file_path);
            }

            $file = $request->file('file');
            $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $filePath = $file->storeAs('portfolios', $fileName, 'public');
            
            $validated['file_path'] = $filePath;
        }

        // Reset status ke pending jika ada perubahan pada file atau deskripsi
        if ($request->hasFile('file') || $validated['description'] !== $portfolio->description) {
            $validated['verified_status'] = 'pending';
            $validated['verified_by'] = null;
            $validated['verified_at'] = null;
        }

        $portfolio->update($validated);

        return redirect()->route('portfolios.show', $portfolio)
                       ->with('success', 'Portfolio berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Portfolio $portfolio)
    {
        // Check authorization
        $this->authorize('delete', $portfolio);

        // Hapus file dari storage
        if ($portfolio->file_path && Storage::disk('public')->exists($portfolio->file_path)) {
            Storage::disk('public')->delete($portfolio->file_path);
        }

        // Hapus portfolio dari database
        $portfolio->delete();

        return redirect()->route('portfolios.index')
                       ->with('success', 'Portfolio berhasil dihapus!');
    }

    /**
     * Verify portfolio (hanya untuk guru/admin)
     */
    public function verify(Request $request, Portfolio $portfolio)
    {
        // Check authorization
        $this->authorize('verify', $portfolio);

        // Validasi input
        $validated = $request->validate([
            'verified_status' => ['required', 'in:approved,rejected'],
            'verification_notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'verified_status.required' => 'Status verifikasi wajib dipilih',
            'verified_status.in' => 'Status verifikasi harus approved atau rejected',
        ]);

        // Update portfolio
        $portfolio->update([
            'verified_status' => $validated['verified_status'],
            'verified_by' => auth()->user()->id,
            'verified_at' => now(),
        ]);

        $status = $validated['verified_status'] === 'approved' ? 'disetujui' : 'ditolak';

        return redirect()->route('portfolios.show', $portfolio)
                       ->with('success', "Portfolio berhasil {$status}!");
    }
}
