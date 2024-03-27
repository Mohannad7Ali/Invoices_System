<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::all();
        return view('sections.section' ,compact('sections'));
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
        $this->validate($request, [

            'section_name' => 'required|max:255|unique:sections,section_name,',
            'description' => 'required',
        ],[

            'section_name.required' =>'يرجي ادخال اسم القسم',
            'section_name.unique' =>'اسم القسم مسجل مسبقا',
            'description.required' =>'يرجي ادخال البيان',

        ]);
        $input = $request->all();
        $if_exist = Section::where('section_name' ,$input['section_name'])->exists();
        if($if_exist){
            session()->flash('Error' , 'هذا القسم مسجل مسبقاً');
            return redirect(route('sections.index'));
        }
        else{
            Section::create([
                'section_name'=>$input['section_name'],
                'description'=>$input['description'],
                'created_by'=>Auth::user()->name
            ]);
        }
        session()->flash('ADD' ,'تمت اضافة القسم بنجاح');
        return redirect(route('sections.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Section $section)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->id;

        $this->validate($request, [

            'section_name' => 'required|max:255|unique:sections,section_name,'.$id,
            'description' => 'required',
        ],[

            'section_name.required' =>'يرجي ادخال اسم القسم',
            'section_name.unique' =>'اسم القسم مسجل مسبقا',
            'description.required' =>'يرجي ادخال البيان',

        ]);

        $section = Section::findorFail($id);
        $section->update([
            'section_name' => $request->section_name,
            'description' => $request->description,
        ]);

        session()->flash('edit','تم تعديل القسم بنجاج');
        return redirect('/sections');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $section = Section::find($request->id);
        $section->delete();
        session()->flash('delete','تم حذف القسم بنجاح');
        return redirect('/sections');
    }

    public function getProducts($id) {
        $products = DB::table('products')->where('section_id' ,$id )->pluck('product_name' ,'id') ;

        return json_encode($products);
    }
}
