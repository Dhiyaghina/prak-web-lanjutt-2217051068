<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\UserModel; 
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function profile($nama = "", $npm = "", $kelas = "")
    {
        $data = [
            'nama' => $nama, 
            'npm' => $npm,
            'kelas' => $kelas,
        ];
        return view('profile', $data);
    }

    public function create()
    {
        
        return view('create_user', [
            'kelas' => Kelas::all(),
        ]);
    }

    public function store(Request $request) 
    {
      
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'npm' => 'required|string|max:255|unique:user,npm', 
            'kelas_id' => 'required|exists:kelas,id',
        ], [
           
            'nama.required' => 'Nama harus diisi.',
            'npm.required' => 'NPM wajib diisi.',
            'kelas_id.required' => 'Kelas harus dipilih.',
            'kelas_id.exists' => 'Kelas yang dipilih tidak valid.',
        ]);
    
     
        $user = UserModel::create($validatedData);

        $user->load('kelas');
    
    
        return view('profile', [
            'nama' => $user->nama,
            'npm' => $user->npm,
            'kelas' => $user->kelas->nama_kelas ?? 'Kelas tidak ditemukan',
        ]);
    }
    
}
