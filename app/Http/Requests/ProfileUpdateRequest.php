<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Traits\FormatPhoneTrait;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    use FormatPhoneTrait;
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => [
                'nullable', 
                'string', 
                'max:20',
                // Allow current user's phone, but reject if another user has it
                function ($attribute, $value, $fail) {
                    if ($value) {
                        // Format the phone first
                        $whatsapp = $this->formatWhatsApp($value);
                        // Check if another user has this phone
                        $exists = \App\Models\User::where('phone', $whatsapp)
                            ->where('id', '!=', $this->user()->id)
                            ->exists();
                        if ($exists) {
                            $fail('Nomor WhatsApp ini sudah digunakan oleh user lain.');
                        }
                    }
                }
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];
    }
}
