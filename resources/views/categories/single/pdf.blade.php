<!DOCTYPE html>
<html>
<head>
    <title>Category Detail</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 12px; color: #777; }
    </style>
</head>
<body>
    <h2>Detail Kategori</h2>
    <p><strong>Kode Kategori:</strong> {{ $category->kode }}</p>
    <p><strong>Nama Kategori:</strong> {{ $category->nama }}</p>

    <h3>Daftar Item</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Item</th>
                <th>Nama Item</th>
            </tr>
        </thead>
        <tbody>
            @foreach($category->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->kode }}</td>
                <td>{{ $item->nama }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ date('Y-m-d H:i:s') }}
    </div>
</body>
</html>
