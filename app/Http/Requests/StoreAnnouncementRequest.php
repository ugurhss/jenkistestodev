<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnnouncementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'group_id' => 'required|exists:groups,id',
            'title'    => 'required|string|max:255',
            'content'  => 'required|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:20480|mimes:jpeg,jpg,png,gif,webp,pdf,doc,docx,xls,xlsx,csv',
        ];
    }

    public function messages(): array
    {
        return [
            'group_id.required' => 'Grup seçimi zorunludur.',
            'group_id.exists'   => 'Seçilen grup bulunamadı.',
            'title.required'    => 'Başlık zorunludur.',
            'title.max'         => 'Başlık en fazla 255 karakter olabilir.',
            'content.required'  => 'İçerik zorunludur.',
            'attachments.*.file' => 'Yüklenen dosya geçerli değil.',
            'attachments.*.mimes' => 'Desteklenmeyen dosya tipi. (jpg, png, gif, webp, pdf, doc, docx, xls, xlsx, csv)',
            'attachments.*.max' => 'Dosya boyutu 20MB\'ı aşamaz.',
        ];
    }
}
