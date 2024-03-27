<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'invoice_number'=>'required|max:20',
            'invoice_Date'=>'required|date',
            'Due_date'=>'required|date',
            'Section'=>'required',
            'product'=>'required',
            'Amount_collection'=>'required|numeric',
            'Amount_Commission'=>'required|numeric',
            'Discount'=>'numeric',
            'Value_VAT'=>'numeric|required',
            'Rate_VAT'=>'required',
            'Total'=>'',
            'note'=>'max:255',
            'pic'=>'mimes:png,jpg,jpeg,csv,txt,pdf',
        ];
    }
    // 'section_id'=>'required',

    public function messages()
    {
        return [
            'invoice_number.required' => 'يجب ادخال رقم الفاتورة',

            'product.required' => 'يجب تحديد المنتج',
            'Section.required' => 'يجب تحديد القسم',
            'Amount_collection.required' => 'يجب ادخال مبلغ التحصيل',
            'Amount_Commission.required' => 'يجب ادخال مبلغ العمولة',
            'pic.mimes' => 'نوع الملف يجب أن يكون : png , jpg , jpeg , csv , txt , pdf',
        ];
    }
}
