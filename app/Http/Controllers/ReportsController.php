<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use App\Models\sections;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index()
    {
        $request=request();
        $invoices=Invoices::with('section')->filter($request)->paginate();
        return view('reports.index',compact('invoices'));
    }
    public function customer()
    {
        $request=request();
        $sections=Sections::all();
        $invoices=Invoices::with('section')->search($request)->paginate();
        return view('reports.customer',compact('invoices','sections'));
    }
}
