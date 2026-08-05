<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\Contact;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function create(): View
    {
        return view('contact', [
            'contactEmail' => Setting::getValue('contact_email', ''),
            'telegramUrl' => Setting::getValue('telegram_url', ''),
            'githubUrl' => Setting::getValue('github_url', ''),
        ]);
    }

    public function store(StoreContactRequest $request): RedirectResponse
    {
        Contact::create($request->validated());

        return redirect()->route('contact.create')
            ->with('success', 'Thank you for your message! I\'ll get back to you soon.');
    }
}
