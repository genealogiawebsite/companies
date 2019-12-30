<?php

namespace LaravelEnso\Companies\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use LaravelEnso\Companies\App\Enums\CompanyStatuses;

class ValidateCompanyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'mandatary' => 'nullable|exists:people,id',
            'name' => ['required', 'string', $this->unique('name')],
            'status' => 'required|numeric|in:'.CompanyStatuses::keys()->implode(','),
            'fiscal_code' => ['string', 'nullable', $this->unique('fiscal_code')],
            'reg_com_nr' => ['string', 'nullable', $this->unique('reg_com_nr')],
            'email' => 'email|nullable',
            'phone' => 'nullable',
            'fax' => 'nullable',
            'bank' => 'string|nullable',
            'bank_account' => 'string|nullable',
            'obs' => 'string|nullable',
            'pays_vat' => 'required|boolean',
            'is_tenant' => 'required|boolean',
        ];
    }

    public function withValidator($validator)
    {
        if ($this->filled('mandatary') && $this->mandataryIsNotAssociated()) {
            $validator->after(fn ($validator) => $validator->errors()
                ->add('mandatary', __('The selected person is not associated to this company'))
            );
        }
    }

    protected function mandataryIsNotAssociated()
    {
        return ! $this->route('company')->people()
            ->pluck('id')->contains($this->get('mandatary'));
    }

    protected function unique(string $attribute)
    {
        return Rule::unique('companies', $attribute)
            ->ignore(optional($this->route('company'))->id);
    }
}
