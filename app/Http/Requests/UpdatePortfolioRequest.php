<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePortfolioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // User boleh update portfolio jika dia adalah pemilik atau admin
        $portfolio = $this->route('portfolio');
        return auth()->check() && 
               (auth()->user()->id === $portfolio->student_id || auth()->user()->role === 'admin');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255', 'min:3'],
            'description' => ['required', 'string', 'min:10', 'max:5000'],
            'type' => ['required', 'in:prestasi,karya,sertifikat'],
            'file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'], // optional untuk update
        ];
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul portfolio wajib diisi',
            'title.min' => 'Judul minimal 3 karakter',
            'title.max' => 'Judul maksimal 255 karakter',
            'description.required' => 'Deskripsi wajib diisi',
            'description.min' => 'Deskripsi minimal 10 karakter',
            'description.max' => 'Deskripsi maksimal 5000 karakter',
            'type.required' => 'Jenis portfolio wajib dipilih',
            'type.in' => 'Jenis portfolio harus: prestasi, karya, atau sertifikat',
            'file.mimes' => 'File harus berformat PDF, JPG, atau PNG',
            'file.max' => 'Ukuran file maksimal 5MB',
        ];
    }

    /**
     * Prepare data untuk disimpan ke database
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        
        // Hapus file dari validated agar tidak disimpan langsung ke database
        if (is_array($validated)) {
            unset($validated['file']);
        }
        
        return $validated;
    }
}
