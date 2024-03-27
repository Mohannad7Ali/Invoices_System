<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::all();
        $products = Product::all();
        return view('products.products', compact('products', 'sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|max:255|unique:products,product_name,',
            'description' => 'required',
            'section_id' => 'required',
        ], [

            'product_name.required' => 'يرجي ادخال اسم المنتج',
            'product_name.unique' => 'اسم المنتج مسجل مسبقا',
            'description.required' => 'يرجي ادخال البيان',

        ]);
        Product::create([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'section_id' => $request->section_id,
        ]);
        session()->flash('ADD', 'تمت اضافة المنتج بنجاح');
        return redirect(route('products.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

        $request->validate([
            'product_name' => 'required|max:255|unique:products,product_name,',
            'description' => 'required',
            'section_id' => 'required',
        ], [

            'product_name.required' => 'يرجي ادخال اسم المنتج',
            'product_name.unique' => 'اسم المنتج مسجل مسبقا',
            'description.required' => 'يرجي ادخال البيان',

        ]);
        $product = Product::findorFail($request->id);
        $product->update([
            'product_name'=>$request->product_name ,
            'description'=>$request->description ,
        ]);
        session()->flash('edit','تم تعديل المنتح بنجاج');
        return redirect('/products');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Product::findorFail($request->id)->delete();
        session()->flash('delete' , 'product deleted successfully') ;
        return redirect()->route('products.index');

    }
}
