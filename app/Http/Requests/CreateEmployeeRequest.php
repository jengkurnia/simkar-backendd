<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'nik' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'gender' => 'required|string|in:MALE,FEMALE',
            'age' => 'required|integer',
            'address' => 'required|string|max:255',
            'education' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'date_entry' => 'required|string|max:255',
            'year_service' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            'team_id' => 'required|integer|exists:teams,id',
            'violation_id' => 'required|integer|exists:violations,id',
        ];
    }
}
