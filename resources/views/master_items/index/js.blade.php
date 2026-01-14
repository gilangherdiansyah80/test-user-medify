<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

<script>
    var start_date = '';
    var end_date = '';
    var data_per_fetch = 500;
    var data_fetched = 0;

    function formatRupiah(angka, prefix) {
        var number_string = angka.toString().replace(/[^,\d]/g, ""),
            split = number_string.split(","),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? "." : "";
            rupiah += separator + ribuan.join(".");
        }

        rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
        return prefix == undefined ? rupiah : rupiah ? "Rp " + rupiah : "";
    }

    $(document).ready(function() {
        $('#table').DataTable({
            searching: false,
            order: [[0, 'desc']],
        });
        getData()
    });

    $('.btn-get-data').click(function() {
        getData()
    })

    $('.btn-reset-filter').click(function() {
        $('#filter-kode').val('')
        $('#filter-nama').val('')
        $('#filter-category').val('')
        $('#filter-harga-min').val('')
        $('#filter-harga-max').val('')
        getData()
    })

    function getData(){
        
        $('#loading-filter').show();
        var dataTableObj = $('#table').DataTable();
        var filter_kode = $('#filter-kode').val()
        var filter_nama = $('#filter-nama').val()
        var filter_category = $('#filter-category').val()
        var filter_harga_min = $('#filter-harga-min').val()
        var filter_harga_max = $('#filter-harga-max').val()

        if (filter_harga_min && filter_harga_max && parseInt(filter_harga_min) > parseInt(filter_harga_max)) {
            alert('Harga Min tidak boleh lebih besar dari Harga Max');
            $('#loading-filter').hide();
            return;
        }

        dataTableObj.clear().draw();

        $.ajax({
            url: '{{url("master-items/search")}}',
            dataType: 'json',
            tryCount: 0,
            retryLimit: 3,
            data: {
                kode: filter_kode,
                nama: filter_nama,
                category_id: filter_category,
                hargamin: filter_harga_min,
                hargamax: filter_harga_max
            },
            success: function(results) {
                var data = results.data

                $.each(data, function(index, item) {
                    var array_temp = [];
                    var harga_jual = item.harga_beli + (item.harga_beli * item.laba / 100);
                    harga_jual = Math.round(harga_jual);
                    var kode = item.kode;

                    var btnView = `<a href="{{url('master-items/view/')}}/` + kode + `" class="btn btn-primary">View</a>`;
                    
                    // Foto
                    var img = '-';
                    if(item.foto) {
                        img = `<img src="{{asset('')}}${item.foto}" style="max-width: 50px; max-height: 50px;">`;
                    }

                    // Explicit mapping
                    array_temp.push(item.kode);
                    array_temp.push(img);
                    array_temp.push(item.nama);
                    array_temp.push(item.jenis);
                    array_temp.push(formatRupiah(item.harga_beli, 'Rp '));
                    array_temp.push(formatRupiah(harga_jual, 'Rp '));
                    array_temp.push(item.supplier ?? '-');
                    array_temp.push(btnView);

                    dataTableObj.row.add(array_temp).draw(true);
                });
                $('#loading-filter').hide();
            },
            error: function(xhr, textStatus, errorThrown) {
                this.tryCount++;
                if (this.tryCount <= this.retryLimit) {
                    $.ajax(this);
                    return;
                }
                alert('Terjadi kesalahan server, tidak dapat mengambil data')
                $('#loading-filter').hide();

                return;
            }
        })
    }
</script>