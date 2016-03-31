<?php

namespace App\Http\Requests;

class ArticlesRequest extends Request
{
    /**
     * The input keys that should not be flashed on redirect.
     *
     * @var array
     */
    protected $dontFlash = ['files'];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $mimes = implode(',', config('project.mimes'));

        return [
            'title' => ['required'],
            'content' => ['required', 'min:10'],
            'tags' => ['required', 'array'],
            'files' => ['array'],
            'files.*' => ['sometimes', "mimes:{$mimes}", 'max:30000'],
        ];
    }
}
