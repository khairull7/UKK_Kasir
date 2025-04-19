<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $users = User::all();

        if ($users->isEmpty()) {
            throw new \Exception('Tidak ada data pengguna untuk di-export.');
        }

        return $users->map(function ($user) {
            return [
                'Nama' => $user->name,
                'Email' => $user->email,
                'Role' => ucfirst($user->role),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Email',
            'Role',
        ];
    }
}
