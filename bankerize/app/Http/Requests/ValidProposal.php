<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ValidProposal extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regas de validaçãom, exigindo a obrigatóriedade de todos os campos
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cpf' => [
                'required',
                'string',
                'regex:/^[0-9\.\-]+$/',
                function ($attribute, $value, $fail) {
                    $onlyNumbers = preg_replace('/[^0-9]/', '', $value);

                    if (strlen($onlyNumbers) !== 11) {
                        $fail('O CPF deve conter 11 dígitos numéricos.');
                    }
                }
            ],
            'nome' => [
                'required',
                'string',
                'max:160'
            ],
            'data_nasc' => [
                'required',
                'date',
                'date_format:Y-m-d',
                'before:today'
            ],
            'valor_emprest' => [
                'required',
                'numeric',
                'min:0.01'
            ],
            'key_pix' => [
                'required',
                'string',
                'max:150'
            ]
        ];
    }
    /**
     * Mensagens de erro personalizadas para as regras de validação
     * @return array/string
     */
    public function menssages(): array
    {
        return [
            'cpf.required' => 'O campo COF é obrigatório.',
            'cpf.regex' => 'O campo COF deve conter apenas números.',

            'nome.required' => 'O nome é obrigatório.',
            'nome.max' => 'O nome não pode ter mais de 160 caracteres.',

            'data_nasc.required' => 'A data de nascimento é obrigatória.',
            'data_nasc.date' => 'A data de nascimento deve ser válida.',
            'data_nasc.date_format' => 'A data de nascimento deve estar no formato DD/MM/YYYY.',
            'data_nasc.before' => 'A data de nascimento não pode ser uma data futura.',

            'valor_emprest.required' => 'O valor do empréstimo é obrigatório.',
            'valor_emprest.numeric' => 'O valor do empréstimo deve ser um número.',
            'valor_emprest.min' => 'O valor do empréstimo deve ser maior que zero.',

            'key_pix.required' => 'A chave PIX é obrigatória.',
            'key_pix.max' => 'A chave PIX não pode ter mais de 150 caracteres.',
        ];
    }
    /**
     * Garante que a rota não redirecione como GET já que o endpoint só aceita post
     */
    function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422));
    }
}
