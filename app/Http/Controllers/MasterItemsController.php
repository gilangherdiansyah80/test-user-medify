<?php

namespace App\Http\Controllers;

use App\Models\MasterItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterItemsController extends Controller
{
    public function index()
    {
        $data['categories'] = \App\Models\Category::all();
        return view('master_items.index.index', $data);
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;
        $hargamin = $request->hargamin;
        $hargamax = $request->hargamax;

        $data_search = MasterItem::query();

        if ($request->filled('kode')) $data_search->where('kode', $kode);
        if ($request->filled('nama')) $data_search->where('nama', 'LIKE', '%' . $nama . '%');
        
        if ($request->filled('category_id')) {
            $data_search->whereHas('categories', function($query) use ($request) {
                $query->where('categories.id', $request->category_id);
            });
        }
        
        if ($request->filled('hargamin')) {
            $hargamin = str_replace(['.', ','], '', $hargamin);
            $data_search->where('harga_beli', '>=', $hargamin);
        }
        if ($request->filled('hargamax')) {
            $hargamax = str_replace(['.', ','], '', $hargamax);
            $data_search->where('harga_beli', '<=', $hargamax);
        }
        
        $data_search = $data_search->with(['categories'])->orderBy('id', 'desc')->get();

        return response()->json(['data' => $data_search]);
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $item = new MasterItem();
        } else {
            $item = MasterItem::with('categories')->find($id);
            if (!$item) abort(404);
        }
        
        $data['item'] = $item;
        $data['method'] = $method;
        $data['categories'] = \App\Models\Category::all();

        return view('master_items.form.index', $data);
    }

    public function singleView($kode)
    {
        $data['data'] = MasterItem::where('kode', $kode)->with(['categories'])->firstOrFail();
        return view('master_items.single.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga_beli' => 'required|numeric|min:1',
            'laba' => 'required|numeric|max:100',
            'supplier' => 'nullable|string',
            'jenis' => 'required|string',
            'foto' => 'nullable|image|max:2048',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        DB::transaction(function () use ($request, $method, $id) {
            if ($method == 'new') {
                $data_item = new MasterItem;
                $lastId = MasterItem::lockForUpdate()->max('id');
                $nextId = $lastId + 1;
                $kode = str_pad($nextId, 5, '0', STR_PAD_LEFT);
                $data_item->kode = $kode;
            } else {
                $data_item = MasterItem::findOrFail($id);
            }

            $data_item->nama = $request->nama;
            $data_item->harga_beli = $request->harga_beli;
            $data_item->laba = $request->laba;
            $data_item->supplier = $request->supplier;
            $data_item->jenis = $request->jenis;

            // Handle Photo Upload
            if ($request->hasFile('foto')) {
                $path = $request->file('foto')->store('public/master_items');
                $data_item->foto = str_replace('public/', 'storage/', $path);
            }

            $data_item->save();

            // Sync Categories
            if ($request->has('categories')) {
                $data_item->categories()->sync($request->categories);
            } else {
                $data_item->categories()->detach();
            }
        });

        return redirect('master-items')->with('success', 'Data saved successfully');
    }

    public function delete($id)
    {
        MasterItem::findOrFail($id)->delete();
        return redirect('master-items');
    }

    public function updateRandomData()
    {
        $data = MasterItem::all();
        foreach($data as $item)
        {
            $item->harga_beli = rand(100,1000000);
            $item->laba = rand(10,99);
            if(empty($item->kode)) {
                $item->kode = str_pad($item->id, 5, '0', STR_PAD_LEFT);
            }
            $item->save();
        }
    }

    public function downloadExcel()
    {
        $items = MasterItem::with(['categories'])->get();
        
        $filename = "master_items_" . date('Ymd_His') . ".xls";
        
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        return view('master_items.export_excel', compact('items'));
    }
}
