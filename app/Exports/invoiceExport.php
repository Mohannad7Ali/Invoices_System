<?php

namespace App\Exports;

use App\Models\invoices;
use Maatwebsite\Excel\Concerns\FromCollection;

class invoiceExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return invoices::all();
        return invoices::select('invoice_number', 'invoice_date', 'due_date', 'product', 'amount_collection','amount_commission','total', 'status', 'payment_date','note')->get();
    }
}
