<?php

namespace App\Http\Livewire\Admin;

use App\Models\Contact;
use App\Models\Message;
use App\Models\MessageHistory;
use App\Models\MessageGateway;
use App\Models\Template;
use Livewire\Component;

class Dashboard extends Component
{
    public $totalMessages;
    public $emailCount;
    public $smsCount;
    public $whatsappCount;
    public $pendingCount;
    public $gatewayCount;
    public $templateCount;
    public $contactCount;
    public $recentMessages;
    public $recentContacts;
    public $array;

    /* render the page */
    public function render()
    {
        return view('livewire.admin.dashboard');
    }

    /* process before mount */
    public function mount()
    {
        $this->totalMessages = Message::count();
        $this->emailCount = Message::where('type', 'email')->count();
        $this->smsCount = Message::where('type', 'phone')->count();
        $this->whatsappCount = Message::where('type', 'whatsapp')->count();
        $this->pendingCount = MessageHistory::whereIn('status', ['pending', 'schedule'])->count();
        $this->gatewayCount = MessageGateway::count();
        $this->templateCount = Template::count();
        $this->contactCount = Contact::count();
        $this->recentMessages = Message::with(['user', 'gateway'])
            ->latest()
            ->limit(8)
            ->get();

        $this->recentContacts = Contact::query()
            ->latest()
            ->limit(5)
            ->get();

        $this->array = json_encode(array($this->emailCount, $this->smsCount, $this->whatsappCount, $this->pendingCount));
    }
}
