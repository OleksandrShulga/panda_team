<?php

namespace App\Http\Requests\Admin\Subscribe;

use App\Models\Email;
use App\Models\Url;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreSubscribe extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.subscribe.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'url_from_user' => ['required', 'string'],
            'email_from_user' => ['required', 'string'],
        ];
    }

    /**
    * Modify input data
    *
    * @return array
    */
    public function getSanitized(): array
    {
        $sanitized = $this->validated();

        //Add your code for manipulation with request data here

        return $sanitized;
    }

    public static function prepareUrlForTrack($urlFromUser): int
    {
        $url = [
            'url' => $urlFromUser,
            'actual_price' => null,
        ];
        return Url::firstOrCreate($url)->id;
    }

    public static function prepareEmailForTrack($emailFromUser, $urlId): int
    {
        $url = [
            'email' => $emailFromUser,
            'url_id' => $urlId,
        ];
        return Email::create($url)->id;
    }
}
