<?php

namespace App\Http\Livewire\Admin;

use App\Jobs\FetchContactsJob;
use App\Models\Contact;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Throwable;

class Contacts extends Component
{
    use WithPagination;

    public $search = '';
    public $source = '';
    public $userType = '';
    public $perPage = 10;

    public $editingId;
    public $name;
    public $email;
    public $phone;
    public $contactSource;
    public $contactUserType;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $contacts = Contact::query()
            ->when($this->search !== '', function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->source !== '', function ($query) {
                $query->where('source', $this->source);
            })
            ->when($this->userType !== '', function ($query) {
                $query->where('user_type', $this->userType);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        $sources = Contact::select('source')
            ->whereNotNull('source')
            ->where('source', '!=', '')
            ->distinct()
            ->orderBy('source')
            ->pluck('source');

        $userTypes = Contact::select('user_type')
            ->whereNotNull('user_type')
            ->where('user_type', '!=', '')
            ->distinct()
            ->orderBy('user_type')
            ->pluck('user_type');

        return view('livewire.admin.contacts', [
            'contacts' => $contacts,
            'sources' => $sources,
            'userTypes' => $userTypes,
            'syncInProgress' => (bool) Cache::get('contacts_fetch_in_progress'),
            'lastSync' => Cache::get('contacts_fetch_result'),
        ]);
    }

    public function fetchContacts()
    {
        if (Cache::get('contacts_fetch_in_progress')) {
            $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'message' => 'A contact sync is already running. Please wait.']);

            return;
        }

        Cache::put('contacts_fetch_in_progress', true, now()->addMinutes(30));

        try {
            FetchContactsJob::dispatch();
        } catch (Throwable $e) {
            Cache::forget('contacts_fetch_in_progress');

            Log::error('Contact sync dispatch failed: ' . $e->getMessage());

            $this->dispatchBrowserEvent('alert', ['type' => 'error', 'message' => 'Could not start the sync. Check the queue worker and logs.']);

            return;
        }

        $this->dispatchBrowserEvent('alert', ['type' => 'info', 'message' => 'Contact sync started in the background.']);
    }

    public function pollFetchResult()
    {
        if (Cache::get('contacts_fetch_in_progress') || Cache::get('contacts_fetch_notified') || !Cache::has('contacts_fetch_result')) {
            return;
        }

        Cache::put('contacts_fetch_notified', true, now()->addHours(24));

        $result = Cache::get('contacts_fetch_result');

        if (!empty($result['success'])) {
            $summary = [];

            foreach ($result['summary'] as $source => $stats) {
                $summary[] = "{$source}: +{$stats['inserted']} / {$stats['skipped']} skipped";
            }

            $this->dispatchBrowserEvent('showGlobalModal', [
                'type' => 'success',
                'title' => 'Contact sync completed',
                'message' => count($summary) > 0 ? 'Your contacts have been synced successfully.' : 'No contacts were synced.',
                'summary' => $summary,
            ]);
        } else {
            $this->dispatchBrowserEvent('showGlobalModal', [
                'type' => 'error',
                'title' => 'Contact sync failed',
                'message' => $result['error'] ?? 'Unknown error',
                'summary' => [],
            ]);
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSource()
    {
        $this->resetPage();
    }

    public function updatedUserType()
    {
        $this->resetPage();
    }

    public function openAdd()
    {
        $this->resetFields();
    }

    public function openEdit($id)
    {
        $contact = Contact::findOrFail($id);

        $this->editingId = $contact->id;
        $this->name = $contact->name;
        $this->email = $contact->email;
        $this->phone = $contact->phone;
        $this->contactSource = $contact->source;
        $this->contactUserType = $contact->user_type;
    }

    public function save()
    {
        $this->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:contacts,email,' . $this->editingId,
            'phone' => 'nullable|string|max:50',
            'contactSource' => 'nullable|string|max:100',
            'contactUserType' => 'nullable|string|max:50',
        ]);

        if (empty($this->email) && empty($this->phone)) {
            $this->addError('email', 'Please provide at least an email or a phone number.');
            $this->addError('phone', 'Please provide at least an email or a phone number.');
            return;
        }

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'source' => $this->contactSource,
            'user_type' => $this->contactUserType,
        ];

        if ($this->editingId) {
            $contact = Contact::findOrFail($this->editingId);
            $contact->update($data);

            $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => 'Contact updated successfully.']);
        } else {
            Contact::create($data);

            $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => 'Contact added successfully.']);
        }

        $this->resetFields();
        $this->emit('contactModalClose');
    }

    public function delete($id)
    {
        Contact::findOrFail($id)->delete();

        $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => 'Contact deleted successfully.']);
    }

    public function resetFields()
    {
        $this->editingId = null;
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->contactSource = '';
        $this->contactUserType = '';
    }
}
