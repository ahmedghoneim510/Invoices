<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use App\Models\invoices_attachments;
use App\Models\invoices_details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoicesDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware(['permission:قائمة الفواتير'], ['only' => ['index']]);

    }
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $request = request();
        if($request->has('notification_id')){
            $notification_id = $request->notification_id;
            auth()->user()->notifications()->where('id', $notification_id)->first()->markAsRead();
        }
        $invoices_details = invoices_details::where('invoice_id', $id)->get();
        //dd($invoices_details);
        $invoice = invoices::find($id);
        $attachments = invoices_attachments::where('invoice_id', $id)->get();
        return view('invoices.invoices_details',
            [
                'invoices_details' => $invoices_details,
                'invoice' => $invoice,
                'attachments' => $attachments,
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(invoices_details $invoices_details)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, invoices_details $invoices_details)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(invoices_details $invoices_details)
    {
        //
    }



}
