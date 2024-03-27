<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice_attachment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Auth;

class InvoiceAttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
    public function addNewAttachments(Request $request)
    {
            $this->validate($request , [
                'file_name'=>'mimes:pdf,jpeg,png,csv,txt,jpg',
            ] ,[
                'file_name.mimes'=>'صيغة المرفق يجب ان تكون   pdf, jpeg , png , jpg' ,
            ]) ;
            $file = $request->file('file_name') ;
            $file_name = $file->getClientOriginalName();
            $attachment = new Invoice_attachment() ;
            $attachment->file_name = $file_name ;
            $attachment->invoice_number = $request->invoice_number ;
            $attachment->invoice_id = $request->invoice_id ;
            $attachment->Created_by = Auth::user()->name ;
            $attachment->save();


            $file->move(public_path('attachments'.'/'.$request->invoice_number), $file_name);
            session()->flash('Add', 'تم اضافة المرفق بنجاح');
            return back();
    }

    /**
     * Display the specified resource.
     */


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice_attachment $invoice_attachment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice_attachment $invoice_attachment)
    {
        //
    }
    public function displayFile($invoice_number , $file_name){
        $file_path =public_path('attachments').'/'.$invoice_number.'/'.$file_name ;
        if (Storage::disk('mydisk')->exists($invoice_number.'/'.$file_name)){
            return response()->file(public_path('attachments').'/'.$invoice_number.'/'.$file_name) ;
        }
        else{
            return dd($file_name);
        }
    }
    public function downloadFile($invoice_number , $file_name){
        $file_path = public_path('attachments').'/'.$invoice_number.'/'.$file_name ;
        if(Storage::disk('mydisk')->exists($invoice_number.'/'.$file_name)){
            return response()->download($file_path);
        }
        else {
            dd("Error");
        }
    }
    public function deleteFile(Request $request){
        $invoice_attachment = Invoice_attachment::findOrFail($request->id_file);
        $invoice_attachment->delete() ;
        if(Storage::disk('mydisk')->exists($request->invoice_number.'/'.$request->file_name)){
            Storage::disk('mydisk')->delete($request->invoice_number);
        }
        session()->flash('delete', 'تم حذف المرفق بنجاح');
        return back();
    }
}
