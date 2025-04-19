@extends('layouts.app')

@section('title', 'Detail Pengguna')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Detail Pengguna</h1>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Kembali
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-3 font-weight-bold">Nama</div>
            <div class="col-md-9">{{ $user->name }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 font-weight-bold">Email</div>
            <div class="col-md-9">{{ $user->email }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 font-weight-bold">Role</div>
            <div class="col-md-9">{{ ucfirst($user->role) }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 font-weight-bold">Dibuat Pada</div>
            <div class="col-md-9">{{ $user->created_at->translatedFormat('d F Y H:i') }}</div>
        </div>
        <div class="row">
            <div class="col-md-3 font-weight-bold">Terakhir Diubah</div>
            <div class="col-md-9">{{ $user->updated_at->translatedFormat('d F Y H:i') }}</div>
        </div>
    </div>
</div>
@endsection
