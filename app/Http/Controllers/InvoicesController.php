<?php

namespace App\Http\Controllers;

use App\Events\InvoicesCreated;
use App\Exports\InvoicesExport;
use App\Models\invoices;
use App\Models\invoices_attachments;
use App\Models\invoices_details;
use App\Models\products;
use App\Models\sections;
use App\Notifications\AddInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(){
        $this->middleware(['permission:قائمة الفواتير|عرض الفواتير'], ['only' => ['index']]);
        $this->middleware(['permission:اضافة فاتورة|تعديل فاتورة|حذف فاتورة|عرض فاتورة'], ['only' => ['create','store','edit','update','destroy','show']]);
    }
    public function index()
    {
        $invoices=invoices::with('section')->get();
        return  view('invoices.invoices',[
        'invoices'=>$invoices,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections=sections::all();
        return view('invoices.create',compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required|unique:invoices',
            'invoice_date' => 'required',
            'due_date' => 'required',
            'section_id' => 'required',
            'product' => 'required',
            'amount_collection' => 'required',
            'amount_commission' => 'required',
            'discount' => 'required',
            'value_vat' => 'required',
            'rate_vat' => 'required',
            'total' => 'required',
        ],[
            'invoice_number.required' => 'يرجي ادخال رقم الفاتورة',
            'invoice_number.unique' => 'رقم الفاتورة موجود مسبقا',
            'invoice_date.required' => 'يرجي ادخال تاريخ الفاتورة',
            'due_date.required' => 'يرجي ادخال تاريخ الاستحقاق',
            'section_id.required' => 'يرجي ادخال القسم',
            'product.required' => 'يرجي ادخال الصنف',
            'amount_collection.required' => 'يرجي ادخال مبلغ التحصيل',
            'amount_commission.required' => 'يرجي ادخال مبلغ العمولة',
            'discount.required' => 'يرجي ادخال الخصم',
            'value_vat.required' => 'يرجي ادخال قيمة الضريبة',
            'rate_vat.required' => 'يرجي ادخال نسبة الضريبة',
            'total.required' => 'يرجي ادخال الاجمالي',

        ]);
      //  dd($request->all());

        DB::beginTransaction();
        try{
            $invoice=invoices::create([
                'invoice_number' => $request->invoice_number,
                'invoice_date' => $request->invoice_date,
                'due_date' => $request->due_date,
                'section_id' => $request->section_id,
                'product' => $request->product,
                'amount_collection' => $request->amount_collection,
                'amount_commission' => $request->amount_commission,
                'discount' => $request->discount,
                'value_vat' => $request->value_vat,
                'rate_vat' => $request->rate_vat,
                'total' => $request->total,
                'status' => 'غير مدفوعة',
                'value_status' => 2,
                'note' => $request->note,
                'user_id' => auth()->user()->id,
            ]);
            invoices_details::create([
                'invoice_id' => $invoice->id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'section' => $request->section_id,
                'status' => 'غير مدفوعة',
                'value' => 2,
                'note' => $request->note,
                'user_id' => auth()->user()->id,

            ]);
            if ($request->hasFile('pic')) {
                $image = $request->file('pic');
                $file_name = $image->getClientOriginalName();
                $invoice_number = $request->invoice_number;

                $attachments = new invoices_attachments();
                $attachments->file_name = $file_name;
                $attachments->invoice_number = $invoice_number;
                $attachments->user_id = Auth::user()->id;
                $attachments->invoice_id = $invoice->id;
                $attachments->save();

                // move pic
                $imageName = $request->pic->getClientOriginalName();
                $request->pic->move(public_path('Attachments/' . $invoice_number), $imageName);
            }
             DB::commit();

            //event(new InvoicesCreated($invoice->id));
        }catch(\Exception $e){
            DB::rollback();
            return redirect()->back()->with('error', "هناك خطأ حاول مرة اخري");
        }
        return to_route('invoices.index')->with('success', 'تمت الاضافة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $invoices=invoices::find($id);
        return view('invoices.status_update',compact('invoices'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $invoices=invoices::find($id);
        $sections=sections::all();
        return view('invoices.edit',compact('sections','invoices'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
       // dd($invoices);
        $invoices=invoices::find($id);
        $request->validate([
            'invoice_number' => ['required', Rule::in($invoices->pluck('invoice_number')->toArray())],
            'invoice_date' => 'required',
            'due_date' => 'required',
            'section_id' => 'required',
            'product' => 'required',
            'amount_collection' => 'required',
            'amount_commission' => 'required',
            'discount' => 'required',
            'value_vat' => 'required',
            'rate_vat' => 'required',
            'total' => 'required',
        ],[
            'invoice_number.required' => 'يرجي ادخال رقم الفاتورة',
            'invoice_number.in' => 'رقم الفاتورة لايجب ان يتغير',
            'invoice_date.required' => 'يرجي ادخال تاريخ الفاتورة',
            'due_date.required' => 'يرجي ادخال تاريخ الاستحقاق',
            'section_id.required' => 'يرجي ادخال القسم',
            'product.required' => 'يرجي ادخال الصنف',
            'amount_collection.required' => 'يرجي ادخال مبلغ التحصيل',
            'amount_commission.required' => 'يرجي ادخال مبلغ العمولة',
            'discount.required' => 'يرجي ادخال الخصم',
            'value_vat.required' => 'يرجي ادخال قيمة الضريبة',
            'rate_vat.required' => 'يرجي ادخال نسبة الضريبة',
            'total.required' => 'يرجي ادخال الاجمالي',

        ]);
        $invoices->update([
            'invoice_number' => $request->invoice_number,
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'section_id' => $request->section_id,
            'product' => $request->product,
            'amount_collection' => $request->amount_collection,
            'amount_commission' => $request->amount_commission,
            'discount' => $request->discount,
            'value_vat' => $request->value_vat,
            'rate_vat' => $request->rate_vat,
            'total' => $request->total,
            'status' => 'غير مدفوعة',
            'value_status' => 2,
            'note' => $request->note,
            'user_id' => auth()->user()->id,
        ]);
        //dd($invoices);
        return redirect()->route('invoices.index')->with('success', 'تم التعديل بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id=$request->invoice_id;
        $invoices=invoices::find($id);
        $attachment=invoices_attachments::where('invoice_number',$invoices->invoice_number)->first();
        if($attachment){
            Storage::disk('public_uploads')->deleteDirectory($invoices->invoice_number);
        }
        $invoices->forceDelete();
        session()->flash('delete_invoice');
        return to_route('invoices.index');
    }
    public function getproducts($id)
    {
        $products = products::where("section_id", $id)->pluck("Product_name", "id");
        return json_encode($products);
    }
    public function status_update(Request $request, $id)
    {
        $invoices=invoices::find($id);
        if($request->Status==="مدفوعة")
        {
            $invoices->update([
                'status' => $request->Status,
                'value_status' => 1,
                'payment_data'=>$request->Payment_Date,
            ]);
           invoices_details::create([
               'invoice_id' => $invoices->id,
                'invoice_number' => $invoices->invoice_number,
                'product' => $invoices->product,
                'section' => $invoices->section_id,
                'status' => $request->Status,
                'value' => 1,
                'note' => $request->note,
                'user_id' => auth()->user()->id,
               'payment_date'=>$request->Payment_Date,
           ]);
        }
        else{
            $invoices->update([
                'status' => $request->Status,
                'value_status' => 3,
                'payment_data'=>$request->Payment_Date,
            ]);
            invoices_details::create([
                'invoice_id' => $invoices->id,
                'invoice_number' => $invoices->invoice_number,
                'product' => $invoices->product,
                'section' => $invoices->section_id,
                'status' => $request->Status,
                'value' => 3,
                'note' => $request->note,
                'user_id' => auth()->user()->id,
                'payment_date'=>$request->Payment_Date,
            ]);
        }
        return redirect()->route('invoices.index')->with('success', 'تم تحديث حالة الفاتورة');

    }
    public function invoice_paid()
    {
        $invoices=invoices::where('value_status',1)->get();
        return view('invoices.invoices_paid',[
            'invoices'=>$invoices,
        ]);
    }
    public function invoice_unpaid()
    {
        $invoices=invoices::where('value_status',2)->get();
        return view('invoices.invoices_unpaid',[
            'invoices'=>$invoices,
        ]);
    }
    public function invoice_partial()
    {
        $invoices=invoices::where('value_status',3)->get();
        return view('invoices.invoices_partial',[
            'invoices'=>$invoices,
        ]);
    }
    public function invoice_archive()
    {
        $invoices=invoices::onlyTrashed()->get();
        return view('invoices.archive',[
            'invoices'=>$invoices,
        ]);
    }
    public function softDelete($id)
    {
      //  dd($id);
        $invoices=invoices::find($id);
        $invoices->delete();
        return redirect()->route('invoices.index')->with('success', 'تم نقل الفاتورة الي الارشيف');
    }
    public function restore($id)
    {
        $invoices=invoices::withTrashed()->find($id);
        $invoices->restore();
        return redirect()->route('invoices.index')->with('success', 'تم استعادة الفاتورة');
    }
    public function print_invoice($id)
    {
        $invoice=invoices::find($id)->first();
        return view('invoices.print',compact('invoice'));
    }
    public function export()
    {
        return Excel::download(new InvoicesExport(), 'invocies.xlsx');
    }
}
