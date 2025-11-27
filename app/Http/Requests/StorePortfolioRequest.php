<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StorePortfolioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // User boleh membuat portfolio jika dia adalah siswa atau admin
        return auth()->check() && 
               (auth()->user()->role === 'student' || auth()->user()->role === 'admin');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'student_name' => ['required', 'string', 'max:255', 'min:3'],
            'student_class' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:255', 'min:3'],
            'description' => ['required', 'string', 'min:10', 'max:5000'],
            'type' => ['required', 'in:prestasi,karya,sertifikat'],
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'], // max 5MB
        ];
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'student_name.required' => 'Nama siswa wajib diisi',
            'student_name.min' => 'Nama minimal 3 karakter',
            'student_name.max' => 'Nama maksimal 255 karakter',
            'student_class.required' => 'Kelas wajib diisi',
            'student_class.max' => 'Kelas maksimal 50 karakter',
            'title.required' => 'Judul portfolio wajib diisi',
            'title.min' => 'Judul minimal 3 karakter',
            'title.max' => 'Judul maksimal 255 karakter',
            'description.required' => 'Deskripsi wajib diisi',
            'description.min' => 'Deskripsi minimal 10 karakter',
            'description.max' => 'Deskripsi maksimal 5000 karakter',
            'type.required' => 'Jenis portfolio wajib dipilih',
            'type.in' => 'Jenis portfolio harus: prestasi, karya, atau sertifikat',
            'file.required' => 'File wajib diunggah',
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
