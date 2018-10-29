<?php

namespace LaravelEnso\Companies\app\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\Companies\app\Models\Company;
use LaravelEnso\Companies\app\Models\Contact;
use LaravelEnso\Companies\app\Forms\Builders\ContactForm;
use LaravelEnso\Companies\app\Contracts\ValidatesContactRequest;
use LaravelEnso\Companies\app\Http\Resources\Contact as Resource;

class ContactController extends Controller
{
    public function index(Company $company)
    {
        return Resource::collection(
            $company
            ->contacts()
            ->with('person')
            ->get()
        );
    }

    public function create(Company $company, ContactForm $form)
    {
        return ['form' => $form->create($company)];
    }

    public function store(ValidatesContactRequest $request)
    {
        Contact::create($request->all());

        return [
            'message' => __('The contact was successfully created'),
        ];
    }

    public function edit(Contact $contact, ContactForm $form)
    {
        return ['form' => $form->edit($contact)];
    }

    public function update(ValidatesContactRequest $request, Contact $contact)
    {
        $contact->update($request->all());

        return [
            'message' => __('The contact have been successfully updated'),
        ];
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
    }
}
