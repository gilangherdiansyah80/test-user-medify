@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-group mb-2">
                <a href="{{url('categories')}}" class="btn btn-secondary">Kembali ke Daftar Kategori</a>
            </div>
            <div class="card">
                <div class="card-header">Detail Kategori</div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Kode Kategori</th>
                            <td>{{ $category->kode }}</td>
                        </tr>
                        <tr>
                            <th>Nama Kategori</th>
                            <td>{{ $category->nama }}</td>
                        </tr>
                    </table>

                    <div class="mt-4">
                        <h5>List Item yang memiliki kategori ini:</h5>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Kode Item</th>
                                    <th>Nama Item</th>
                                    <th>Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($category->items as $item)
                                <tr>
                                    <td>{{ $item->kode }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <a href="{{ url('categories/download-pdf/' . $category->id) }}" class="btn btn-danger">Download PDF</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
