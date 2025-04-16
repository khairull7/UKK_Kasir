@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 text-primary mb-0">Edit User</h1>
    </div>

    {{-- Tampilkan error validasi --}}
    @if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    {{-- Form --}}
    <div class="card border-0 shadow-sm p-4">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">Password (Kosongkan jika tidak ingin mengganti)</label>
                    <input type="password" class="form-control" name="password">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" name="password_confirmation">
                </div>

                <div class="col-md-6 mb-4">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" name="role" required>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="petugas" {{ $user->role == 'petugas' ? 'selected' : '' }}>Petugas</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-warning">
                <i class="fas fa-edit"></i> Update User
            </button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Batal
            </a>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://kit.fontawesome.com/your-font-awesome-kit.js"></script>
<style>
    body {
        background-color: #f8f9fa;
    }
    .card {
        border-radius: 15px;
        overflow: hidden;
    }
    .btn {
        border-radius: 5px;
        padding: .375rem .75rem;
    }
    .form-control {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        padding: 0.75rem 1rem;
    }
    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
    .alert {
        border-radius: 10px;
    }
</style>
@endsection
