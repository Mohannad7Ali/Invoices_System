<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use Illuminate\Http\Request;

class invoicesArchive extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices  = Invoices::onlyTrashed()->get();
        return view('invoices.invoices_Archive', compact('invoices'));
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $invoice = Invoices::withTrashed()->where('id' , $request->invoice_id)->first() ;
        $invoice->forceDelete();
        session()->flash('delete_invoice');
        return redirect('/invoiceArchive');
    }

    public function restore(Request $request)
    {
        $invoice = Invoices::withTrashed()->where('id' , $request->invoice_id)->restore() ;
        session()->flash('restore_invoice');
        return redirect('/invoiceArchive');
    }
}
