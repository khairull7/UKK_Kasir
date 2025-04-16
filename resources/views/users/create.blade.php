@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 text-primary mb-0">Tambah User Baru</h1>
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

    <div class="card border-0 shadow-sm p-4">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" name="role" required>
                            <option value="">Pilih Role</option>
                            <option value="admin">Admin</option>
                            <option value="petugas">Petugas</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" name="password_confirmation" required>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Batal
                </a>
            </div>
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
