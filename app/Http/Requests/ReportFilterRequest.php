<?php

namespace App\Http\Requests;

use App\Models\Report;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReportFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d', Rule::when($this->filled('from'), 'after_or_equal:from')],
            'status' => ['nullable', Rule::in(Report::STATUSES)],
        ];
    }

    public function messages(): array
    {
        return [
            'from.date_format' => 'Format tanggal awal tidak valid.',
            'to.date_format' => 'Format tanggal akhir tidak valid.',
            'to.after_or_equal' => 'Tanggal akhir tidak boleh lebih awal dari tanggal awal.',
            'status.in' => 'Status tidak dikenal.',
        ];
    }
}
