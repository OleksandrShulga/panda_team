<?php

namespace App\Http\Requests\Admin\Subscribe;

use App\Models\Email;
use App\Models\Subscribe;
use App\Models\Url;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateSubscribe extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.subscribe.edit', $this->subscribe);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'url_from_user' => ['sometimes', 'string'],
            'email_from_user' => ['sometimes', 'string'],

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

    public static function updateUrl($id, $url, $subscribe_id) {
        $findOrNotFindUrl = Url::where('url', $url)->first();
        if ($findOrNotFindUrl->url == $url['url']) {
            Subscribe::where('id', $subscribe_id)->update(['url_id' => $findOrNotFindUrl->id]);
        } else if (DB::table('emails')->where('url_id', $id)->count() == 1) {
            Url::updateOrCreate($id, $url);
        } else {
            Url::create($url);
        }
    }

    public static function updateEmail($id, $email) {
        Email::updateOrCreate($id, $email);
    }
}
