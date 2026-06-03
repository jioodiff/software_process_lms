<h1>Daftar mahasiswa</h1>
<ul>
    @foreach ($daftar_mhs as $mhs)
    <li>{{ $mhs->nim }} - {{ $mhs->nama }}</li>
    @endforeach
</ul>