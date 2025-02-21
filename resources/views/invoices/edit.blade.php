@extends('layouts.master')
@section('css')
    <!--- Internal Select2 css-->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <!---Internal Fileupload css-->
    <link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
    <!---Internal Fancy uploader css-->
    <link href="{{ URL::asset('assets/plugins/fancyuploder/fancy_fileupload.css') }}" rel="stylesheet" />
    <!--Internal Sumoselect css-->
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/sumoselect/sumoselect-rtl.css') }}">
    <!--Internal  TelephoneInput css-->
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/telephoneinput/telephoneinput-rtl.css') }}">
@endsection
@section('title')
    تعديل بيانات فاتورة
@stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    تعديل بيانات فاتورة</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
@if (session()->has('edit'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>{{ session()->get('edit') }}</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif
    @if (session()->has('Add'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('Add') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session()->has('Error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button class="close" type="button" data-dismiss="alert" aria-label="close">
                <span aria-hidden="">&timesb;</span>
            </button><br>
            <strong>{{ session()->get('Error') }}</strong>

        </div>
    @endif
        <!-- row -->
        <div class="row">

            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('invoices.update' , 'any') }}" method="post" enctype="multipart/form-data"
                            autocomplete="off">
                            {{ csrf_field() }}
                            @method('PUT')
                            {{-- 1 --}}
                            <input type="hidden" name="id" , value={{$invoice->id}}>
                            <div class="row">
                                <div class="col">
                                    <label for="inputName" class="control-label" >رقم الفاتورة</label>
                                    <input type="text" class="form-control" id="inputName" name="invoice_number"
                                        title="يرجي ادخال رقم الفاتورة" value ={{$invoice->invoice_number}} required>
                                </div>

                                <div class="col">
                                    <label>تاريخ الفاتورة</label>
                                    <input class="form-control fc-datepicker" name="invoice_Date" placeholder="YYYY-MM-DD"
                                        type="text" value ={{$invoice->invoice_date}} required> {{--the date formatting important --}}
                                </div>

                                <div class="col">
                                    <label>تاريخ الاستحقاق</label>
                                    <input class="form-control fc-datepicker" name="Due_date" placeholder="YYYY-MM-DD"
                                        type="text" value ={{$invoice->due_date}} required>
                                </div>

                            </div>

                            {{-- 2 --}}
                            <div class="row">
                                <div class="col">
                                    <label for="inputName" class="control-label">القسم</label>
                                    <select name="Section" class="form-control SlectBox"
                                        onclick="console.log($(this).val())" onchange="console.log('change is firing')">
                                        <!--placeholder-->
                                        <option value="{{$invoice->section->id}}" selected >{{$invoice->section->section_name}}</option>
                                        @foreach ($sections as $section)
                                            @if ($section->id != $invoice->section->id)
                                            <option value="{{ $section->id }}"> {{ $section->section_name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col">
                                    <label for="inputName" class="control-label">المنتج</label>
                                    <select id="product" name="product" class="form-control">
                                        <option value="{{$invoice->product}}" selected >{{$invoice->product}}</option>
                                    </select>
                                </div>

                                <div class="col">
                                    <label for="inputName" class="control-label">مبلغ التحصيل</label>
                                    <input type="text" class="form-control" id="inputName" name="Amount_collection" value="{{$invoice->amount_collection}}"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                </div>
                            </div>


                            {{-- 3 --}}

                            <div class="row">

                                <div class="col">
                                    <label for="inputName" class="control-label">مبلغ العمولة</label>
                                    <input type="text" class="form-control form-control-lg" id="Amount_Commission"
                                        name="Amount_Commission" value="{{$invoice->amount_commission}}"title="يرجي ادخال مبلغ العمولة "
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                        required>
                                </div>

                                <div class="col">
                                    <label for="inputName" class="control-label">الخصم</label>
                                    <input type="text" class="form-control form-control-lg" id="Discount"
                                        name="Discount" title="يرجي ادخال مبلغ الخصم " value="{{$invoice->discount}}"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                        value=0 required>
                                </div>

                                <div class="col">
                                    <label for="inputName" class="control-label">نسبة ضريبة القيمة المضافة</label>
                                    <select name="Rate_VAT" id="Rate_VAT" class="form-control" onchange="myFunction()">
                                        <!--placeholder-->
                                        <option value="{{$invoice->rate_vate}}" selected >{{$invoice->rate_vate}}</option>
                                        <option value=" 5%">5%</option>
                                        <option value="10%">10%</option>
                                    </select>
                                </div>

                            </div>

                            {{-- 4 --}}

                            <div class="row">
                                <div class="col">
                                    <label for="inputName" class="control-label">قيمة ضريبة القيمة المضافة</label>
                                    <input type="text" class="form-control" id="Value_VAT" name="Value_VAT" value="{{$invoice->value_vate}}" readonly>
                                </div>

                                <div class="col">
                                    <label for="inputName" class="control-label">الاجمالي شامل الضريبة</label>
                                    <input type="text" class="form-control" id="Total" name="Total"value="{{$invoice->total}}"readonly>
                                </div>
                            </div>

                            {{-- 5 --}}
                            <div class="row">
                                <div class="col">
                                    <label for="exampleTextarea">ملاحظات</label>
                                    <textarea class="form-control" id="exampleTextarea" name="note" value="{{$invoice->note}}" rows="3"></textarea>
                                </div>
                            </div><br>



                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary">تعديل البيانات</button>
                            </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>

        </div>

        <!-- row closed -->
        </div>
        <!-- Container closed -->
        </div>
        <!-- main-content closed -->
    @endsection
    @section('js')
        <!-- Internal Select2 js-->
        <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
        <!--Internal Fileuploads js-->
        <script src="{{ URL::asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
        <script src="{{ URL::asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
        <!--Internal Fancy uploader js-->
        <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.ui.widget.js') }}"></script>
        <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.fileupload.js') }}"></script>
        <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.iframe-transport.js') }}"></script>
        <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.fancy-fileupload.js') }}"></script>
        <script src="{{ URL::asset('assets/plugins/fancyuploder/fancy-uploader.js') }}"></script>
        <!--Internal  Form-elements js-->
        <script src="{{ URL::asset('assets/js/advanced-form-elements.js') }}"></script>
        <script src="{{ URL::asset('assets/js/select2.js') }}"></script>
        <!--Internal Sumoselect js-->
        <script src="{{ URL::asset('assets/plugins/sumoselect/jquery.sumoselect.js') }}"></script>
        <!--Internal  Datepicker js -->
        <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
        <!--Internal  jquery.maskedinput js -->
        <script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
        <!--Internal  spectrum-colorpicker js -->
        <script src="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
        <!-- Internal form-elements js -->
        <script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>

        <script>
            var date = $('.fc-datepicker').datepicker({
                dateFormat: 'yy-mm-dd'
            }).val();
        </script>

        <script>
            $(document).ready(function() {
                $('select[name="Section"]').on('change', function() {
                    var SectionId = $(this).val();
                    if (SectionId) {
                        $.ajax({
                            url: "{{ URL::to('products_for_sections') }}/" + SectionId,
                            type: "GET",
                            dataType: "json",
                            success: function(data) {
                                $('select[name="product"]').empty();
                                $.each(data, function(key, value) {
                                    $('select[name="product"]').append('<option value="' +
                                        value + '">' + value + '</option>');
                                });
                            },
                        });

                    } else {
                        console.log('AJAX load did not work');
                    }
                });

            });
        </script>
        {{-- <script>
    $(document).ready(function(){
        $('select[name="Section"]').on('change',function(){
            var sectionId = $(this).val();
            if(sectionId){
                $.ajax({
                    url: "{{URL::to('section')}}/"+SectionId ,
                    type:"GET",
                    dataType:"json"
                    success:fuction(data){
                        $('select[name:"product"]').empty() ;
                        $.each(data , function(key , value){
                            $('select[name="product"]').append('<option value="'+value +'">'+value+
                                '</option>')
                        });
                    },
                });
            }else {
                alert('AJAX load did not work');

            }
        })
    })
    </script> --}}


        <script>
            function myFunction() {

                var Amount_Commission = parseFloat(document.getElementById("Amount_Commission").value);
                var Discount = parseFloat(document.getElementById("Discount").value);
                var Rate_VAT = parseFloat(document.getElementById("Rate_VAT").value);
                var Value_VAT = parseFloat(document.getElementById("Value_VAT").value);

                var Amount_Commission2 = Amount_Commission - Discount;


                if (typeof Amount_Commission === 'undefined' || !Amount_Commission) {

                    alert('يرجي ادخال مبلغ العمولة ');

                } else {
                    var intResults = Amount_Commission2 * Rate_VAT / 100;

                    var intResults2 = parseFloat(intResults + Amount_Commission2);

                    sumq = parseFloat(intResults).toFixed(2);

                    sumt = parseFloat(intResults2).toFixed(2);

                    document.getElementById("Value_VAT").value = sumq;

                    document.getElementById("Total").value = sumt;

                }

            }
        </script>
        {{-- <script>
        function myFunction() {
        var amount_commission = parseFloat(document.getElementById("Amount_Commission").value) ;
        var discount = parseFloat(document.getElementById('Discount').value);
        var  Rate_VAT = parseFloat(document.getElementById('Rate_VAT').value);
        var Value_VAT = parseFloat(document.getElementById('Value_VAT').value);

        var newAmountCommission = amount_commission - discount ;
        if(typeof newAmountCommission == 'undefine' || !AmountCommission) {
            alert('أدخل مبلغ العمولة ') ;

        }else{
            var result1 = newAmountCommission * Rate_VAT /100 ;
            var result2 = parseFloat(result1+newAmountCommission);
            var vatValue = parseFloat(result1).toFixed(2);
            var Total = parseFloat(result2).toFixed(2);
            document.getElementById("Value_VAT").value = vatValue;
            document.getElementById("Total").value = Total;
        }
        }
    </script> --}}


    @endsection
