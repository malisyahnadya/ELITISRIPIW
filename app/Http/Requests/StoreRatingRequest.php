<?php

namespace App\Http\Requests;

use App\Models\Rating;
use Illuminate\Foundation\Http\FormRequest;

class StoreRatingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'score' => ['required', 'integer', 'between:' . Rating::MIN_SCORE . ',' . Rating::MAX_SCORE],
        ];
    }
}
