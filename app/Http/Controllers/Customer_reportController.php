<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;
use App\Models\Section;
use App\Models\Invoices;

class Customer_reportController extends Controller
{
    public function index()
    {
        $sections = Section::all();
        return view('reports.customers', compact('sections'));
    }

    public function search(Request $request)
    {
        if ($request->Section && $request->product && $request->start_at && $request->end_at) {
            $sections = Section::all();
            $start_at = date($request->start_at);
            $end_at = date($request->end_at);
            $details = Invoices::select('*')->whereBetween('invoice_date', [$start_at, $end_at])->where('section_id', $request->Section)->where('product', $request->product)->get();
            $Section = Section::select('*')->where('id' , $request->Section)->first()->section_name ;
            $product = $request->input('product') ;
            return view('reports.customers', compact('start_at', 'end_at', 'details', 'sections' , 'Section' , 'product'));
        }
        elseif (!($request->start_at) && !($request->end_at)) {
            $sections = Section::all();
            $details = Invoices::select('*')->where('section_id', $request->Section)->where('product', $request->product)->get();
            $Section = Section::select('*')->where('id' , $request->Section)->first()->section_name ;
            $product = $request->input('product') ;
            return view('reports.customers', compact('details', 'sections', 'Section' , 'product'));
        }
    }
}
