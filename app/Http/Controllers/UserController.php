<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request; // Pastikan ini ada
use Illuminate\Support\Facades\DB; // Import DB Facade
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.users.index', [
            'active' => 'users',
            'users' => User::latest()
                ->filter(request(['search']))
                ->paginate(10)
                ->withQueryString(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.users.create', [
            'active' => 'data',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|max:255',
            'username' => 'required|unique:users|min:3|max:255',
            'role' => 'required|in:ADMIN,KASIR',
            'password' => 'required|min:6',
        ]);

        User::create($validatedData);

        return redirect('/dashboard/users')->with('success', 'Pengguna baru telah ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        // Biasanya tidak digunakan untuk resource controller seperti ini
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('dashboard.users.edit', [
            'user' => $user,
            'active' => 'data',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $rules = [
            'nama' => 'required|max:255',
            'role' => 'required|in:ADMIN,KASIR',
        ];

        // Validasi username hanya jika berbeda dari yang lama
        if($request->username != $user->username) {
            $rules['username'] = 'required|unique:users|min:3|max:255';
        }

        // Validasi password hanya jika diisi
        if ($request->filled('password')) {
            $rules['password'] = 'min:6';
        }

        $validatedData = $request->validate($rules);

        // Jika password tidak diisi, jangan update password
        if (!$request->filled('password')) {
            unset($validatedData['password']);
        }

        $user->update($validatedData);

        return redirect('/dashboard/users')->with('success', 'Data pengguna telah diubah.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // [PERBAIKAN]
        // Menggunakan transaction agar proses aman. Jika salah satu gagal, semua akan dibatalkan.
        DB::transaction(function () use ($user) {
            // 1. Hapus semua data 'return barang' yang terkait dengan user ini.
            $user->returns()->delete();

            // 2. Hapus semua data 'transaksi' yang terkait dengan user ini.
            $user->transactions()->delete();

            // 3. Setelah semua data terkait aman untuk dihapus, baru hapus user-nya.
            $user->delete();
        });

        return redirect('/dashboard/users')->with('success', 'Pengguna beserta data terkait telah dihapus.');
    }
}
