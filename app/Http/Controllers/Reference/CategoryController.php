<?php

namespace App\Http\Controllers\Reference;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Carbon;

# Models
use App\Models\Collection\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return view('contents.reference.category.index');
    }

    public function data(Request $request)
    {
        $categories = Category::select(DB::raw('categories.*'))->withCount([
            'collections'
        ]);
        
        return DataTables::of($categories)
        ->addIndexColumn()
        ->editColumn('created_at', function($category) {
            return Carbon::parse($category->created_at)->format('d/m/Y');
        })
        ->addColumn('actions', function($category) {
            if ($category->collections()->exists()) return '';

            return '<a href="javascript:;" class="uk-badge uk-badge-warning" onclick="updateCategory('.$category->id.')">Ubah</a>'.'&nbsp;&nbsp;'.'<a href="javascript:;" class="uk-badge uk-badge-danger" onclick="deleteCategory('.$category->id.')">Hapus</a>';
        })
        ->rawColumns([
            'actions'
        ])
        ->make(true);
    }

    public function create(Request $request)
    {
        try {
            $category = new Category($request->all());
            $category->save();

            return redirect()->route('reference.category')->with('success', 'Berhasil menyimpan kategori koleksi!');
        } catch (\Exception $e) {
            return redirect()->route('reference.category')->with('error', 'Terjadi kesalahan saat menyimpan kategori koleksi!');
        }
    }

    public function updateForm(Request $request)
    {
        $category = Category::find($request->id);
        return view('contents.reference.category.update-form', [
            'category' => $category
        ]);
    }

    public function update(Request $request)
    {
        try {
            $category = Category::find($request->id);
            $category->fill($request->all());
            $category->save();

            return redirect()->route('reference.category')->with('success', 'Berhasil menyimpan perubahan kategori koleksi!');
        } catch (\Exception $e) {
            return redirect()->route('reference.category')->with('error', 'Terjadi kesalahan saat menyimpan perubahan kategori koleksi!');
        }
    }

    public function delete(Request $request)
    {
        $category = Category::find($request->id);

        if (empty($category)) return 'Terjadi kesalahan saat menghapus kategori koleksi!';

        $category->delete();
        return 'Berhasil menghapus kategori koleksi!';        
    }
}
