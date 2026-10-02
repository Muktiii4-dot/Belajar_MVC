@php($isEdit = $mode === 'edit')
<form action="{{ $isEdit ? route('siswa.update', $siswa->id) : route('siswa.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">NISN (10 digit)</label>
            <input type="text" name="nisn" value="{{ old('nisn', $siswa->nisn ?? '') }}" class="form-control @error('nisn') is-invalid @enderror" maxlength="10" inputmode="numeric" required>
            @error('nisn')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Nama Lengkap</label>
            <input type="text" name="nama" value="{{ old('nama', $siswa->nama ?? '') }}" class="form-control @error('nama') is-invalid @enderror" maxlength="100" required>
            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Tempat Lahir</label>
            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $siswa->tempat_lahir ?? '') }}" class="form-control @error('tempat_lahir') is-invalid @enderror" required>
            @error('tempat_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', optional($siswa->tanggal_lahir ?? null)->format('Y-m-d')) }}" class="form-control @error('tanggal_lahir') is-invalid @enderror" required>
            @error('tanggal_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Jenis Kelamin</label>
            <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                <option value="">-- Pilih --</option>
                <option value="L" @selected(old('jenis_kelamin', $siswa->jenis_kelamin ?? '') === 'L')>Laki-laki</option>
                <option value="P" @selected(old('jenis_kelamin', $siswa->jenis_kelamin ?? '') === 'P')>Perempuan</option>
            </select>
            @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Jurusan</label>
            <input type="text" name="jurusan" value="{{ old('jurusan', $siswa->jurusan ?? 'RPL') }}" class="form-control @error('jurusan') is-invalid @enderror">
            @error('jurusan')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Nomor HP</label>
            <input type="text" name="no_hp" value="{{ old('no_hp', $siswa->no_hp ?? '') }}" class="form-control @error('no_hp') is-invalid @enderror" inputmode="numeric" required>
            @error('no_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold">Email</label>
            <input type="email" name="email" value="{{ old('email', $siswa->email ?? '') }}" class="form-control @error('email') is-invalid @enderror" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold">Alamat</label>
            <textarea name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror" required>{{ old('alamat', $siswa->alamat ?? '') }}</textarea>
            @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold">Foto Profil</label>
            <input type="file" name="foto" accept=".jpg,.jpeg,.png" class="form-control @error('foto') is-invalid @enderror">
            <div class="form-text">JPG/JPEG/PNG, maksimal 2 MB.</div>
            @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
            @if($isEdit && $siswa->foto)
                <div class="mt-2"><img src="{{ asset('storage/' . $siswa->foto) }}" alt="Foto saat ini" class="rounded avatar"></div>
            @endif
        </div>
    </div>

    <div class="d-flex gap-2 justify-content-end mt-4">
        <a href="{{ route('siswa.index') }}" class="btn btn-outline-secondary">Kembali</a>
        <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Data' }}</button>
    </div>
</form>
