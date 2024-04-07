<div class="row">
    <div class="col">
        <label for="inputName" class="control-label">رقم الفاتورة</label>
        @if(isset($invoices->invoice_number))
            <input type="text" class="form-control" id="inputName" name="invoice_number" title="يرجي ادخال رقم الفاتورة "
                   value="{{old('invoice_number',$invoices->invoice_number??'')}}"  readonly required>
        @else
        <x-form.input name="invoice_number" :value="$invoices->invoice_number??''"
                      title="يرجي ادخال رقم الفاتورة " required ></x-form.input >
        @endif
    </div>

    <div class="col">
        <label>تاريخ الفاتورة</label>
        <input class="form-control fc-datepicker" name="invoice_date" placeholder="YYYY-MM-DD"
               type="text" value="{{ date('Y-m-d') }}" required>
    </div>

    <div class="col">
        <label>تاريخ الاستحقاق</label>
        <input class="form-control fc-datepicker" value="{{ old('due_date',$invoices->due_date??'') }}" name="due_date" placeholder="YYYY-MM-DD"
               type="text" required>
    </div>

</div>

{{-- 2 --}}
<div class="row">
    <div class="col">
        <label for="inputName" class="control-label">القسم</label>
        <select name="section_id" class="form-control SlectBox" onclick="console.log($(this).val())"
                onchange="console.log('change is firing')">
            <!--placeholder-->
            <option value=""  disabled>حدد القسم</option>
            @foreach ($sections as $section)
                <option value="{{ $section->id }}" @selected(old('section_id', $invoices->section_id??'')==$section->id )>{{ $section->section_name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col">
        <label for="inputName" class="control-label">المنتج</label>
        <select id="product" name="product" class="form-control">
            <option value="{{ old('product',$invoices->product??'') }}" selected>{{ old('product',$invoices->product??'') }}</option>
        </select>
    </div>

    <div class="col">
        <label for="inputName" class="control-label">مبلغ التحصيل</label>
        <input type="text" value="{{ old('amount_collection',$invoices->amount_collection??'') }}" class="form-control" id="inputName" name="amount_collection"
               oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
    </div>
</div>


{{-- 3 --}}

<div class="row">

    <div class="col">
        <label for="inputName" class="control-label">مبلغ العمولة</label>
        <input type="text" class="form-control form-control-lg" id="Amount_Commission"
               name="amount_commission" value="{{ old('amount_commission',$invoices->amount_commission??'') }}" title="يرجي ادخال مبلغ العمولة "
               oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
               required>
    </div>

    <div class="col">
        <label for="inputName" class="control-label">الخصم</label>
        <input type="text" class="form-control form-control-lg" value="{{ old('discount',$invoices->discount??'') ?? 0 }}  " id="Discount" name="discount"
               title="يرجي ادخال مبلغ الخصم "
               oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
               required>
    </div>

    <div class="col">
        <label for="inputName" class="control-label">نسبة ضريبة القيمة المضافة</label>
        <select name="rate_vat" id="Rate_VAT" class="form-control" onchange="myFunction()">
            <!--placeholder-->
            <option value=""  @selected(old('rate_vat', $invoices->rate_vat??'')== '') disabled>حدد نسبة الضريبة</option>
            <option value=" 5%" @selected(old('rate_vat', $invoices->rate_vat??'')== '5%')>5%</option>
            <option value="10%" @selected(old('rate_vat', $invoices->rate_vat??'')== '10%')>10%</option>
        </select>
    </div>

</div>
<div class="row">
    <div class="col">
        <label for="inputName" class="control-label">قيمة ضريبة القيمة المضافة</label>
        <input type="text" class="form-control" value="{{$invoices->value_vat??''}}" id="Value_VAT" name="value_vat" readonly>
    </div>

    <div class="col">
        <label for="inputName" class="control-label">الاجمالي شامل الضريبة</label>
        <input type="text" class="form-control" id="Total"  value=" {{$invoices->total??''}}" name="total" readonly>
    </div>
</div>

{{-- 5 --}}
<div class="row">
    <div class="col">
        <label for="exampleTextarea">ملاحظات</label>
        <textarea class="form-control" id="exampleTextarea"  name="note" rows="3">{{$invoices->note??''}}</textarea>
    </div>
</div><br>

@if(!isset($invoices->invoice_number) )
    <p class="text-danger">* صيغة المرفق pdf, jpeg ,.jpg , png </p>
    <h5 class="card-title">المرفقات</h5>

    <div class="col-sm-12 col-md-12">
        <input type="file" name="pic" class="dropify" accept=".pdf,.jpg, .png, image/jpeg, image/png"
               data-height="70" />
    </div><br>
@endif
<br>

<div class="d-flex justify-content-center">
    <button type="submit" class="btn btn-primary">حفظ البيانات</button>
</div>
