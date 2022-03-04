<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Controllers\Api\BaseApiController;

class BookRequest extends FormRequest
{

    protected $stopOnFirstFailure = true;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    
    public function rules()
    {
        $method = $this->method();
        if (null !== $this->get('_method', null)) {
            $method = $this->get('_method');
        }
        $this->offsetUnset('_method');

        switch ($method) {
            case 'GET':
                $this->rules = [
                    'name' => ['nullable', 'string'],
                    'country' => ['nullable', 'string'],
                    'publisher' => ['nullable', 'string'],
                    'release_date' => ['nullable', 'integer'],
                ];
                break;
            case 'POST':
                $this->rules = [
                    'name' => ['required', 'string', 'unique:books,name'],
                    'isbn' => ['required', 'string'],
                    'authors' => ['required', 'array'],
                    'country' => ['required', 'string'],
                    'number_of_pages' => ['required', 'integer'],
                    'publisher' => ['required', 'string'],
                    'release_date' => ['required', 'string'],
                ];
                break;
            case 'PATCH':
                $this->rules = [
                    'name' => ['nullable', 'string', 'unique:books,name'],
                    'isbn' => ['nullable', 'string'],
                    'authors' => ['nullable', 'array'],
                    'country' => ['nullable', 'string'],
                    'number_of_pages' => ['nullable', 'integer'],
                    'publisher' => ['nullable', 'string'],
                    'release_date' => ['nullable', 'string'],
                ];
                break;
            default:
                $this->rules = [];
                break;
        }

        return $this->rules;
    }

    public function messages()
    {
        $method = $this->method();
        if (null !== $this->get('_method', null)) {
            $method = $this->get('_method');
        }
        $this->offsetUnset('_method');

        switch ($method) {
            case 'GET':
                $this->messages = [];
                break;
            case 'POST':
                $this->messages = [];
                break;
            case 'PUT':
                $this->messages = [];
                break;
            default:
                $this->messages = [];
                break;
        }

        return $this->messages;
    }

    protected function failedValidation(Validator $validator) {

        $validationError = $validator->errors();
        $errors = $validationError->all();
        $message = $errors[0];

        $apiResponse = new BaseApiController;
        throw new HttpResponseException($apiResponse->returnResponse('failed', 422, null, $message));
    }
}
