{{-- Pesan sukses/gagal buat halaman peserta. --}}
@foreach (['sukses' => ['#7a8f6b', '#f5f0e1'], 'success' => ['#7a8f6b', '#f5f0e1'], 'gagal' => ['#c8562f', '#f5f0e1'], 'error' => ['#c8562f', '#f5f0e1']] as $kunci => [$latar, $teks])
    @if (session($kunci))
        <div class="mb-6 px-5 py-3 text-sm font-semibold"
             style="background:{{ $latar }}; color:{{ $teks }}; border:2px solid #1a2a3a;">
            {{ session($kunci) }}
        </div>
    @endif
@endforeach

@if ($errors->any())
    <div class="mb-6 px-5 py-3 text-sm" style="background:#ffe9e2; color:#c8562f; border:2px solid #c8562f;">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif
