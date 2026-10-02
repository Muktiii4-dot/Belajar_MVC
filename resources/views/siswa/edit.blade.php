@extends('siswa.layout')

@section('title', 'Edit Siswa')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-warning p-4">
                <h1 class="h5 mb-1">Edit Data Siswa</h1>
                <p class="mb-0 text-dark-emphasis">Perbarui data yang diperlukan.</p>
            </div>
            <div class="card-body p-4">
                @include('siswa.partials.form', ['mode' => 'edit', 'siswa' => $siswa])
            </div>
        </div>
    </div>
</div>
@endsection
