<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Support\SimplePdf;
use App\Support\SimpleXlsx;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim((string) $request->input('keyword', ''));

        $query = Siswa::query();

        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', '%' . $keyword . '%')
                    ->orWhere('nisn', 'like', '%' . $keyword . '%')
                    ->orWhere('alamat', 'like', '%' . $keyword . '%')
                    ->orWhere('email', 'like', '%' . $keyword . '%');
            });
        }

        $daftarSiswa = $query->latest()->paginate(5)->withQueryString();

        return view('siswa.index', compact('daftarSiswa', 'keyword'));
    }

    public function create()
    {
        return view('siswa.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validatedData($request);

        $validated['jurusan'] = $request->input('jurusan', 'RPL');
        $validated['foto'] = $this->storePhoto($request);

        Siswa::create($validated);

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa baru berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $siswa = Siswa::findOrFail($id);

        return view('siswa.edit', compact('siswa'));
    }

    public function update(Request $request, int $id)
    {
        $siswa = Siswa::findOrFail($id);
        $validated = $this->validatedData($request, $siswa->id);
        $validated['jurusan'] = $request->input('jurusan', $siswa->jurusan ?: 'RPL');

        if ($request->hasFile('foto')) {
            $this->deletePhoto($siswa->foto);
            $validated['foto'] = $this->storePhoto($request);
        }

        $siswa->update($validated);

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $siswa = Siswa::findOrFail($id);
        $this->deletePhoto($siswa->foto);
        $siswa->delete();

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }

    public function exportExcel()
    {
        $rows = Siswa::latest()->get();
        $filename = 'Laporan_Data_Siswa_' . now()->format('Ymd_His') . '.xlsx';

        return SimpleXlsx::download($rows, $filename);
    }

    public function exportPdf()
    {
        $rows = Siswa::latest()->get();
        $filename = 'Laporan_Data_Siswa_' . now()->format('Ymd_His') . '.pdf';

        return SimplePdf::download($rows, $filename);
    }

    private function validatedData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'nisn' => [
                'required',
                'digits:10',
                Rule::unique('siswas', 'nisn')->ignore($ignoreId),
            ],
            'nama' => ['required', 'string', 'max:100'],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'jurusan' => ['nullable', 'string', 'max:100'],
            'alamat' => ['required', 'string'],
            'no_hp' => ['required', 'digits_between:10,15'],
            'email' => [
                'required',
                'email',
                Rule::unique('siswas', 'email')->ignore($ignoreId),
            ],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.digits' => 'NISN harus terdiri dari 10 digit.',
            'nisn.unique' => 'NISN sudah terdaftar.',
            'nama.required' => 'Nama lengkap wajib diisi.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'alamat.required' => 'Alamat wajib diisi.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.digits_between' => 'Nomor HP harus 10 sampai 15 digit.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'foto.image' => 'File foto harus berupa gambar.',
            'foto.mimes' => 'Foto hanya boleh JPG, JPEG, atau PNG.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ]);
    }

    private function storePhoto(Request $request): ?string
    {
        if (!$request->hasFile('foto')) {
            return null;
        }

        return $request->file('foto')->store('foto_siswa', 'public');
    }

    private function deletePhoto(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
