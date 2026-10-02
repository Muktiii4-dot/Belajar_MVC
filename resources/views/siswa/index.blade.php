@extends('siswa.layout')

@section('title', 'Data Induk Siswa')

@section('content')
<div class="card border-0 shadow-sm overflow-hidden">
    <div class="card-header bg-primary text-white p-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center">
            <div>
                <h1 class="h4 mb-1">Data Induk Siswa</h1>
                <p class="mb-0 text-white-50">CRUD, pencarian, pagination, upload foto, dan export laporan.</p>
            </div>
            <a href="{{ route('siswa.create') }}" class="btn btn-light">+ Tambah Siswa</a>
        </div>
    </div>

    <div class="card-body p-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-2 mb-4">
            <div class="col-lg-7">
                <form action="{{ route('siswa.index') }}" method="GET">
                    <div class="input-group">
                        <input type="text" name="keyword" class="form-control" value="{{ $keyword }}" placeholder="Cari nama, NISN, alamat, atau email...">
                        <button class="btn btn-primary" type="submit">Cari</button>
                        @if($keyword !== '')
                            <a href="{{ route('siswa.index') }}" class="btn btn-outline-secondary">Reset</a>
                        @endif
                    </div>
                </form>
            </div>
            <div class="col-lg-5 text-lg-end">
                <a href="{{ route('siswa.export.excel') }}" class="btn btn-success">Export Excel</a>
                <a href="{{ route('siswa.export.pdf') }}" class="btn btn-danger" target="_blank">Export PDF</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>Tempat, Tanggal Lahir</th>
                        <th>JK</th>
                        <th>Jurusan</th>
                        <th>No HP</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($daftarSiswa as $siswa)
                    <tr>
                        <td>{{ ($daftarSiswa->currentPage() - 1) * $daftarSiswa->perPage() + $loop->iteration }}</td>
                        <td>
                            @if($siswa->foto)
                                <img src="{{ asset('storage/' . $siswa->foto) }}" alt="Foto {{ $siswa->nama }}" class="rounded-circle avatar">
                            @else
                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center avatar small">N/A</div>
                            @endif
                        </td>
                        <td><span class="badge text-bg-secondary">{{ $siswa->nisn }}</span></td>
                        <td class="fw-semibold">{{ $siswa->nama }}</td>
                        <td>{{ $siswa->tempat_lahir }}, {{ optional($siswa->tanggal_lahir)->format('d-m-Y') }}</td>
                        <td>{{ $siswa->jenis_kelamin }}</td>
                        <td>{{ $siswa->jurusan }}</td>
                        <td>{{ $siswa->no_hp }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('siswa.edit', $siswa->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('siswa.destroy', $siswa->id) }}" method="POST" onsubmit="return confirm('Hapus data siswa ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            @if($keyword !== '')
                                Data siswa dengan kata kunci <strong>{{ $keyword }}</strong> tidak ditemukan.
                            @else
                                Belum ada data siswa.
                            @endif
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mt-3">
            <div class="text-muted small">Total hasil: {{ $daftarSiswa->total() }} siswa.</div>
            <div>{!! $daftarSiswa->links() !!}</div>
        </div>
    </div>
</div>
@endsection
