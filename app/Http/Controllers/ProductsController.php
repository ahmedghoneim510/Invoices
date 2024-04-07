<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\sections;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware(['permission:اضافة منتج|تعديل منتج|حذف منتج']);

    }
    public function index()
    {
        $sections = sections::all();
        $products = products::with('sections')->get();
        return view('products.index',compact('sections','products'));
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
        //dd($request->all());
        $request->validate([
            'product_name' => 'required',
            'description' => 'required',
            'section_id' => 'required|exists:sections,id',
        ]);
        //dd($request->all());
        products::create($request->all());
        return redirect()->route('products.index')
            ->with('success','Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(products $products)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(products $products)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'product_name' => 'required',
            'description' => 'required',
            'pro_id'=>'required',
            'section_name' => 'required',
        ],[
            'product_name.required' => 'يرجى إدخال اسم المنتج',
            'description.required' => 'يرجى إدخال الوصف',
            'section_name.required' => 'يرجى إدخال اسم القسم',

        ]);
        $section_id = sections::where('section_name',$request->section_name)->first()->id;
        products::find($request->pro_id)->update([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'section_id' => $section_id,

        ]);
        return to_route('products.index')
            ->with('Edit','تم التعديل بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        products::find($request->pro_id)->delete();
        return to_route('products.index')
            ->with('Delete','تم الحذف بنجاح');
    }

}
