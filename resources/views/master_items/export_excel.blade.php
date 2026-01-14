<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        .table {
            border-collapse: collapse;
            width: 100%;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Nama Items</th>
                <th>Nama Supplier</th>
                <th>Harga Beli</th>
                <th>Laba (%)</th>
                <th>Harga Jual</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $index => $item)
            @php
                $hargaJual = $item->harga_beli + ($item->harga_beli * $item->laba / 100);
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->categories->pluck('nama')->join(', ') }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->supplier }}</td>
                <td>{{ $item->harga_beli }}</td>
                <td>{{ $item->laba }}</td>
                <td>{{ $hargaJual }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
