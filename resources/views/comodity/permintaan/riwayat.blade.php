@foreach($data as $p)
<div class="border p-4 mb-4">

    <h3>{{ $p->pic }} - {{ $p->commodity }}</h3>

    <ul>
        @foreach($p->details as $d)
            <li>{{ $d->barang->nama_barang }} - {{ $d->qty }}</li>
        @endforeach
    </ul>

</div>
@endforeach
