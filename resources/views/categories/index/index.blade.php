@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-group mb-2">
                <a href="{{url('categories/form/new')}}" class="btn btn-secondary">+ Kategori Baru</a>
            </div>
            <div class="card">
                <div class="card-header">Daftar Kategori</div>

                <div class="card-body">
                    <!-- Filter -->
                    <div id="filter-container" class="mb-3">
                        <h4>Filter</h4>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Kode</label>
                                    <input type="text" class="form-control" id="filter-kode">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Nama</label>
                                    <input type="text" class="form-control" id="filter-nama">
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <button class="btn btn-primary btn-get-data">Filter</button>
                            <button class="btn btn-secondary btn-reset-filter">Reset</button>
                        </div>
                    </div>

                    <!-- Table -->
                    <table id="table" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>View</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#table').DataTable({
            searching: false,
            order: [[0, 'desc']],
        });
        getData();
    });

    $('.btn-get-data').click(function() {
        getData();
    });

    $('.btn-reset-filter').click(function() {
        $('#filter-kode').val('');
        $('#filter-nama').val('');
        getData();
    });

    function getData() {
        var dataTableObj = $('#table').DataTable();
        var filter_kode = $('#filter-kode').val();
        var filter_nama = $('#filter-nama').val();
        
        dataTableObj.clear().draw();

        $.ajax({
            url: '{{url("categories/search")}}',
            dataType: 'json',
            data: {
                kode: filter_kode,
                nama: filter_nama
            },
            success: function(results) {
                var data = results.data;
                $.each(data, function(index, item) {
                    var btnView = `<a href="{{url('categories/view')}}/${item.id}" class="btn btn-primary btn-sm">View</a>`;
                    var btnEdit = `<a href="{{url('categories/form/edit')}}/${item.id}" class="btn btn-warning btn-sm">Edit</a>`;
                    var btnDelete = `<a href="{{url('categories/delete')}}/${item.id}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>`;
                    
                    dataTableObj.row.add([
                        item.kode,
                        item.nama,
                        btnView,
                        btnEdit + ' ' + btnDelete
                    ]).draw(true);
                });
            }
        });
    }
</script>
@endsection
