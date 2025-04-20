@extends('layouts.app')

@section('title', 'Detail Pengguna')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Detail Pengguna</h1>
    <a href="{{ route('user.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left fa-sm text-gray-500 mr-1"></i> Kembali
    </a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Informasi Pengguna</h5>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-3 font-weight-bold text-dark">Nama</div>
            <div class="col-md-9">{{ $user->name }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 font-weight-bold text-dark">Email</div>
            <div class="col-md-9">{{ $user->email }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 font-weight-bold text-dark">Role</div>
            <div class="col-md-9 text-capitalize">{{ $user->role }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 font-weight-bold text-dark">Dibuat Pada</div>
            <div class="col-md-9">{{ $user->created_at->translatedFormat('d F Y H:i') }}</div>
        </div>
        <div class="row">
            <div class="col-md-3 font-weight-bold text-dark">Terakhir Diubah</div>
            <div class="col-md-9">{{ $user->updated_at->translatedFormat('d F Y H:i') }}</div>
        </div>
    </div>
</div>

@endsection
