<?php

namespace App\Http\Livewire\Contact;

use Livewire\Component;
use App\Models\Contact;

class ContactUser extends Component
{
    public $contact;
    public $mutualContacts;

    public function mount($id)
    {
        $this->contact = Contact::findOrFail($id);
        $this->mutualContacts = $this->contact->mutualContacts(); // Assumes mutualContacts() returns a collection
    }

    public function render()
    {
        return view('livewire.contact.contact-user');
    }
}
