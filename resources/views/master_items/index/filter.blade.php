<div id="filter-container">
    <h4>Filter</h4>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label>Kode</label>
                <input type="text" class="form-control" id="filter-kode">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Nama</label>
                <input type="text" class="form-control" id="filter-nama">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Kategori</label>
                <select class="form-control" id="filter-category">
                    <option value="">--Semua--</option>
                    @foreach($categories as $category)
                    <option value="{{$category->id}}">{{$category->nama}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label>Harga Min</label>
                        <input type="number" class="form-control" id="filter-harga-min">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>Harga Max</label>
                        <input type="number" class="form-control" id="filter-harga-max">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-2">
        <button class="btn btn-primary btn-get-data">Filter</button>
        <button class="btn btn-secondary btn-reset-filter">Reset</button>
        <span id="loading-filter" style="display: none;" class="ms-2">Loading...</span>
    </div>
</div>