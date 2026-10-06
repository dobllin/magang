{{-- Pesan sukses / gagal / daftar error validasi. --}}

@if (session('sukses'))
    <div class="mb-6 px-5 py-4 font-semibold"
         style="background: #7a8f6b; color: #f5f0e1; border: 2px solid #1a2a3a; box-shadow: 4px 4px 0 #1a2a3a;">
        {{ session('sukses') }}
    </div>
@endif

@if (session('gagal'))
    <div class="mb-6 px-5 py-4 font-semibold"
         style="background: #c8562f; color: #f5f0e1; border: 2px solid #1a2a3a; box-shadow: 4px 4px 0 #1a2a3a;">
        {{ session('gagal') }}
    </div>
@endif

@if ($errors->any())
    <div class="mb-6 px-5 py-4"
         style="background: #fff; border: 2px solid #c8562f; box-shadow: 4px 4px 0 #c8562f;">
        <div class="font-bold mb-2" style="color: #c8562f;">
            Ada {{ $errors->count() }} isian yang perlu diperbaiki:
        </div>
        <ul class="list-disc list-inside text-sm space-y-1" style="color: #1a2a3a;">
            @foreach ($errors->all() as $pesan)
                <li>{{ $pesan }}</li>
            @endforeach
        </ul>
    </div>
@endif