<?php

namespace App\Http\Controllers;

use BackedEnum;
use App\Models\User;
use App\Models\Section;
use App\Models\Invoices;
use Illuminate\Http\Request;
use App\Exports\invoiceExport;
use App\Http\Requests\InvoiceRequest;
use App\Models\Invoice_details;
use App\Notifications\AddInvoice;
use App\Models\Invoice_attachment;
use App\Notifications\Add_Invoice;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoices::all();
        return view('invoices.invoices', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = Section::all();
        return view('invoices.add_invoices', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InvoiceRequest $request)
    {
        $request->validated();

        invoices::create([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'value_vate' => $request->Value_VAT,
            'rate_vate' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
        ]);

        $invoice_id = Invoices::latest()->first()->id;
        Invoice_details::create([
            'invoice_id' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'Section' => $request->Section,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);


        // if ($request->hasFile('pic')) {
        //     $invoice_id = Invoices::latest()->first()->id;
        //     $image = $request->file('pic');
        //     $file_name = $image->getClientOriginalName();
        //     $invoice_number = $request->invoice_number;
        //     $attachments = new Invoice_attachment();
        //     $attachments->file_name = $file_name;
        //     $attachments->invoice_number = $invoice_number;
        //     $attachments->Created_by = Auth::user()->name;
        //     $attachments->invoice_id = $invoice_id;
        //     $attachments->save();
        //     // move pic
        //     $imageName = $request->pic->getClientOriginalName();
        //     $request->pic->move(public_path('Attachments/' . $invoice_number), $imageName);
        // }
        if ($request->hasFile('pic')) {
            $invoice_id = Invoices::latest()->first()->id;
            $image = $request->file('pic');
            $fileName = $image->getClientOriginalName();

            $attachments = new Invoice_attachment;
            $attachments->file_name = $fileName;
            $attachments->invoice_number = $request->invoice_number;
            $attachments->created_by = Auth::user()->name;
            $attachments->invoice_id = $invoice_id;
            $attachments->save();

        // we saved data in database we store just the file name and file we store it in server
        $file_name = $request->file('pic')->getClientOriginalName();
        $file_extinsion = $request->file('pic')->extension();
        $Xfile = $request->file('pic');
        $storage_path = public_path('attachments');
        if ($file_extinsion == 'pdf' || $file_extinsion == 'png' || $file_extinsion == 'jpg' || $file_extinsion == 'jpeg') {
            $Xfile->storeAs($request->invoice_number, $file_name, 'mydisk');
        } else {
            session()->flash('Error', 'error in uploading file check your file if true and try again');
        }
    }





        // $user = User::first(); //getthe current user in the system
        // Notification::send($user, new AddInvoice($invoice_id));

        $user = User::where('roles_name', '!=', 'user')->get();
        $invoices = invoices::latest()->first();
        Notification::send($user, new Add_Invoice($invoices));







        // event(new MyEventClass('hello world'));

        session()->flash('Add', 'تم اضافة الفاتورة بنجاح');
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $invoices = Invoices::where('id', $id)->first();
        $details = Invoice_details::where('invoice_id', $id)->get();
        $attachments = Invoice_attachment::where('invoice_id', $id)->get();

        return view('invoices.details', compact('invoices', 'details', 'attachments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $invoice = Invoices::where('id', $id)->first();
        $sections = Section::all();
        return view('invoices.edit', compact('invoice', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InvoiceRequest $data)
    {
        $request = $data->validated();
        $invoice = Invoices::where('id', $request->id);
        $invoice->update([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'value_vate' => $request->Value_VAT,
            'rate_vate' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
        ]);
        session()->flash('edit', 'تم تعديل الفاتورة بنجاح');
        return redirect()->route('invoices.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $invoice = Invoices::where('id', $request->invoice_id)->first();
        if ($request->operation != 2) {
            $attachments = Invoice_attachment::where('invoice_id', $invoice->id)->get();
            Storage::disk('mydisk')->deleteDirectory($invoice->invoice_number);

            foreach ($attachments as $attachment) {
                $attachment->forceDelete();
            }
            $invoice->forceDelete();
            session()->flash('delete_invoice', 'تم حذف الفاتورة بنجاح');
            return redirect('/invoices');
        } else {
            $invoice->delete();
            session()->flash('archive_invoice', 'تم أرشفة الفاتورة بنجاح');
            return redirect('/invoices');
        }
    }
    public function show_status($id)
    {
        $invoices = Invoices::where('id', $id)->first();
        return view('invoices.invoice_status', compact('invoices'));
    }
    public function updateStatus(Request $request, $id)
    {
        $invoice = Invoices::findOrFail($id);
        if ($request->status === 'مدفوعة') {

            $invoice->update([
                'value_status' => 1,
                'status' => $request->status,
                'payment_date' => $request->payment_date,
            ]);

            invoice_Details::create([
                'invoice_id' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'section' => $request->section,
                'status' => $request->status,
                'value_status' => 1,
                'note' => $request->note,
                'payment_date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        } else {
            $invoice->update([
                'value_status' => 3,
                'status' => $request->status,
                'payment_date' => $request->payment_date,
            ]);

            invoice_Details::create([
                'invoice_id' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'section' => $request->section,
                'status' => $request->status,
                'value_status' => 3,
                'note' => $request->note,
                'payment_date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }
        session()->flash('Status_Update');
        return redirect('invoices');
    }

    public function show_invoices_paid()
    {
        $invoices  = Invoices::where('value_status', 1)->get();
        return view('invoices.invoices_paid', compact('invoices'));
    }
    public function show_invoices_notPaid()
    {
        $invoices  = Invoices::where('value_status', 2)->get();
        return view('invoices.invoices_not_paid', compact('invoices'));
    }
    public function show_invoices_partial()
    {
        $invoices  = Invoices::where('value_status', 3)->get();
        return view('invoices.invoices_partial_paid', compact('invoices'));
    }
    public function print_invoice($id)
    {
        $invoices  = Invoices::findOrFail($id);
        return view('invoices.print_invoice', compact('invoices'));
    }

    public function MarkAllAsRead()
    {
        $userUnreadNotification = auth()->user()->unreadNotifications;

        if ($userUnreadNotification) {
            $userUnreadNotification->markAsRead();
            return back();
        }
    }
    public function export()
    {

        return Excel::download(new invoiceExport, 'invoices.xlsx');

    }
}
