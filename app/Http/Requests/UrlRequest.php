<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UrlRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'string|url|max:255|unique:urls'
        ];
    }

    public function messages(): array
    {
        return [
            'url' => 'Incorrect url',
            'max' => 'Максимальная допустимая длина адреса 255 символов',
            'unique' => 'Такой адрес уже есть в базе, воспользуйтесь поиском.'
        ];
    }

    protected function prepareForValidation(): void
    {
        $url = $this->input('name');


        $prepared = !Str::startsWith($url, ['http://', 'https://']) ? implode(['https://', $url]) : $url;

        $parts = parse_url($prepared);
        if ($parts) {
            $prepared = self::toLower($parts);
        }
        $this->merge(['name' => $prepared]);
    }

    protected function failedValidation(Validator $validator)
    {
        if ($validator->fails()) {
            flash($validator->errors()->first('name'))->error()->important();
        }
        parent::failedValidation($validator);
    }

    private static function toLower(array $parts): string
    {
        $query = array_key_exists('query', $parts)
            ? "?{$parts['query']}"
            : '';

        $lowerCaseUrl = strtolower(implode([$parts['scheme'], '://', $parts['host'], $parts['path'] ?? '']));
        return "{$lowerCaseUrl}{$query}";
    }
}
