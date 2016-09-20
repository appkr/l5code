<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticlesRequest extends FormRequest
{
    /**
     * The input keys that should not be flashed on redirect.
     *
     * @var array
     */
    protected $dontFlash = [
        'files',
    ];

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
            'tags' => ['required', 'array'],
            'content' => ['required', 'min:10'],
            'files' => ['array'],
            'files.*' => ['sometimes', "mimes:{$mimes}", 'max:30000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages() {
        return [
            'required' => '필수 입력 항목입니다.',
            'min' => '최소 :min 글자 이상이 필요합니다.',
            'array' => '배열만 허용합니다.',
            'mimes' => ':values 형식만 허용합니다.',
            'max' => ':max 킬로바이트까지만 허용합니다.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'title' => '제목',
            'content' => '본문',
            'tags' => '태그',
            'files' => '파일',
            'files.*' => '파일',
        ];
    }
}
