<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CategoryController extends Controller
{
    public function index()
    {
        return view('categories.index.index');
    }

    public function search(Request $request)
    {
        $nama = $request->nama;
        $kode = $request->kode;

        $query = Category::query();

        if ($request->filled('nama')) {
            $query->where('nama', 'LIKE', '%' . $nama . '%');
        }
        if ($request->filled('kode')) {
            $query->where('kode', $kode);
        }

        $categories = $query->orderBy('id', 'desc')->get();

        return response()->json(['data' => $categories]);
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $category = new Category();
        } else {
            $category = Category::findOrFail($id);
        }

        $data['category'] = $category;
        $data['method'] = $method;

        return view('categories.form.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:255|unique:categories,kode,' . ($id != 0 ? $id : 'NULL') . ',id',
        ]);

        if ($method == 'new') {
            $category = new Category();
        } else {
            $category = Category::findOrFail($id);
        }

        $category->nama = $request->nama;
        $category->kode = $request->kode;
        $category->save();

        return redirect('categories')->with('success', 'Data saved successfully');
    }

    public function singleView($id)
    {
        $category = Category::with('items')->findOrFail($id);
        return view('categories.single.index', compact('category'));
    }

    public function downloadPdf($id)
    {
        $category = Category::with('items')->findOrFail($id);
        $pdf = Pdf::loadView('categories.single.pdf', compact('category'));
        return $pdf->download('category_' . $category->kode . '.pdf');
    }

    public function delete($id)
    {
        Category::findOrFail($id)->delete();
        return redirect('categories');
    }
}
