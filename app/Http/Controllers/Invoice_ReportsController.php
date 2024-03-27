<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Invoices;

class Invoice_ReportsController extends Controller
{

    public function index(): View
    {
        return view('reports.invoices');
    }

    public function search(Request $request) {
        $radio = $request->radio ;
        // search with invoice type (status)

        if($radio == 1) {
            if(isset($request->type) && !isset($request->start_at) && !isset($request->end_at)){
                $details = Invoices::select('*')->where('value_status' , $request->type)->get();
                $type ="" ;
                if($request->type ==1){
                    $type = 'الفواتير المدفوعة';
                }elseif($request->type == 2){
                    $type = 'الفواتير الغير مدفوعة';
                }else{
                    $type = 'الفواتير المدفوعة جزئيا';
                }
                return view('reports.invoices' , compact('type' , 'details'));
            }
            else{
                $start_at = date($request->start_at) ;
                $end_at = date($request->end_at) ;
                $type ="" ;
                if($request->type ==1){
                    $type = 'الفواتير المدفوعة';
                }elseif($request->type == 2){
                    $type = 'الفواتير الغير مدفوعة';
                }else{
                    $type = 'الفواتير المدفوعة جزئيا';
                }
                $details = Invoices::select('*')->whereBetween('invoice_date' , [$start_at , $end_at])->where('value_status' , $request->type)->get();
                return view('reports.invoices' , compact('type' , 'details' ,'start_at' ,'end_at'));

            }
        }
        // search with invoice number

        elseif ($radio == 2) {
            $invoice_number = $request->invoice_number;
            $details = Invoices::select('*')->where('invoice_number' ,$invoice_number)->get() ;
            return view('reports.invoices' , compact('invoice_number' , 'details'));

        }
    }
}
