<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class InstrukturController extends Controller
{
    public function index()
    {
        $instruktur = DB::table('instruktur')
            ->join('users', 'instruktur.id_user', '=', 'users.id')
            ->select('instruktur.*', 'users.nama_lengkap', 'users.email')
            ->orderBy('instruktur.created_at', 'desc')
            ->get();

        return view('admin.instruktur.index', compact('instruktur'));
    }

    public function create()
    {
        // Cuma user yang rolenya instruktur & belum ada di tabel instruktur
        $users = DB::table('users')
            ->where('role', 'instruktur')
            ->whereNotIn('id', DB::table('instruktur')->pluck('id_user'))
            ->get(['id', 'nama_lengkap']);

        return view('admin.instruktur.form', ['instruktur' => null, 'users' => $users]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['created_at'] = now();
        $data['updated_at'] = now();

        DB::table('instruktur')->insert($data);

        return redirect()->route('admin.instruktur.index')->with('success', 'Instruktur berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $instruktur = DB::table('instruktur')->where('id', $id)->first();
        if (!$instruktur) {
            abort(404);
        }

        // User yang sekarang + user instruktur lain yang belum punya profil
        $users = DB::table('users')
            ->where('role', 'instruktur')
            ->where(function ($q) use ($instruktur) {
                $q->where('id', $instruktur->id_user)
                  ->orWhereNotIn('id', DB::table('instruktur')->pluck('id_user'));
            })
            ->get(['id', 'nama_lengkap']);

        return view('admin.instruktur.form', compact('instruktur', 'users'));
    }

    public function update(Request $request, $id)
    {
        if (!DB::table('instruktur')->where('id', $id)->exists()) {
            abort(404);
        }

        $data = $this->validateData($request, $id);
        $data['updated_at'] = now();

        DB::table('instruktur')->where('id', $id)->update($data);

        return redirect()->route('admin.instruktur.index')->with('success', 'Instruktur berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $punyaMateri = DB::table('materi')->where('id_instruktur', $id)->exists();
        if ($punyaMateri) {
            return back()->with('error', 'Instruktur ini masih punya materi. Pindahkan atau hapus materinya dulu.');
        }

        DB::table('instruktur')->where('id', $id)->delete();
        return back()->with('success', 'Instruktur berhasil dihapus.');
    }

    private function validateData(Request $request, $abaikanId = null): array
    {
        return $request->validate([
            // Harus user ber-role instruktur, dan satu user cuma boleh
            // punya satu profil instruktur.
            'id_user'       => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where('role', 'instruktur'),
                Rule::unique('instruktur', 'id_user')->ignore($abaikanId),
            ],
            'spesialisasi'  => ['required', 'string', 'max:255'],
            'pengalaman'    => ['required', 'string', 'max:100'],
            'sertifikasi'   => ['nullable', 'string', 'max:255'],
            'jabatan'       => ['required', 'string', 'max:100'],
        ], [
            'id_user.exists' => 'User itu nggak ada atau role-nya bukan instruktur.',
            'id_user.unique' => 'User itu sudah punya profil instruktur.',
        ]);
    }
}