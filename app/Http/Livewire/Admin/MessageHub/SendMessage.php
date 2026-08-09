<?php

namespace App\Http\Livewire\Admin\MessageHub;

use App\Jobs\SendAdminEmailJob;
use App\Jobs\SendBrevoSmsJob;
use Livewire\Component;

use App\Helpers\BrevoHelper;
use App\Helpers\SmsHelper;
use App\Helpers\WhatsappHelper;
use App\Jobs\SendEmailJob;
use App\Models\Contact;
use App\Models\Message;
use App\Models\MessageGateway;
use App\Models\MessageHistory;
use App\Models\Template;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;


class SendMessage extends Component
{


    public $showMessageForm = false;
    public $messageType = '';
    public $message = '';

    public $selectedCriteria = 'email';
    public $selectedAudience = 'single';
    public $contactSearch = '';
    public $errorMessage = '';
    public $recipient_email;
    public $schedule_at;
    public $subject;
    public $content;
    public $tempId = null;
    public $selectedTemplate;
    public $isSelectTemp = false;
    public $selectedRecipients = [];
    public $filteredContacts = [];
    public $userTypeFilter = '';
    public $userTypes = [];
    public $templates;

    protected $listeners = ['contentUpdated'];

    public function contentUpdated($value)
    {
        $this->content = $value;
    }

    public function mount()
    {
        $this->resetInputFields();
        $this->templates = Template::query()->where('is_default', '=', 0)->get(['id', 'name']);
    }

    public function onChangeSearchField()
    {
        $this->errorMessage = null;
        if ($this->selectedCriteria == 'email') {
            if (filter_var($this->contactSearch, FILTER_VALIDATE_EMAIL)) {
                if ($this->selectedAudience == 'single') {
                    $this->selectedRecipients = [];
                    $this->selectedRecipients[$this->contactSearch] = [
                        'email' => $this->contactSearch,
                    ];
                    $this->errorMessage = null;
                } else {
                    if (!isset($this->selectedRecipients[$this->contactSearch])) {
                        $this->selectedRecipients[$this->contactSearch] = [
                            'email' => $this->contactSearch,
                        ];
                    }
                }
            } else {
                $this->errorMessage = "Invalid email address. Please enter a valid email.";
            }
        } else if ($this->selectedCriteria == 'sms' || $this->selectedCriteria == 'whatsapp') {
            if (strlen($this->contactSearch) > 0 && strlen($this->contactSearch) <= 20) {
                if ($this->selectedAudience == 'single') {
                    $this->selectedRecipients = [];
                    $this->selectedRecipients[$this->contactSearch] = [
                        'phone' => $this->contactSearch,
                    ];
                    $this->errorMessage = null;
                } else {
                    if (!isset($this->selectedRecipients[$this->contactSearch])) {
                        $this->selectedRecipients[$this->contactSearch] = [
                            'phone' => $this->contactSearch,
                        ];
                    }
                }
            } else {
                $this->errorMessage = "Invalid phone number. It must be up to 20 digits.";
            }
        }




        $this->contactSearch = '';
        $this->filteredContacts = [];

        $this->emit('flatpickr-reinitialize');

        $this->emit('initTooltip');

        $this->templates = Template::query()->where('is_default', '=', 0)->get(['id', 'name']);
    }



    public function selectAudience($type)
    {
        $this->resetInputFields();
        $this->selectedAudience = $type;
        $this->emit('flatpickr-reinitialize');

        $this->emit('initTooltip');
        $this->templates = Template::query()->where('is_default', '=', 0)->get(['id', 'name']);
    }



    public function selectCriteria($criteria)
    {
        $this->resetInputFields();
        $this->selectedAudience = 'single';
        $this->selectedCriteria = $criteria;
        $this->emit('flatpickr-reinitialize');
        if ($this->selectedCriteria == 'email') {
            $this->emit('initCKEditor');
        }
        $this->emit('initTooltip');
        $this->templates = Template::query()->where('is_default', '=', 0)->get(['id', 'name']);
    }

    public function updatedScheduleAt($value)
    {
        $this->emit('flatpickr-reinitialize');

        $this->emit('initTooltip');
        $this->templates = Template::query()->where('is_default', '=', 0)->get(['id', 'name']);
    }



    public function updatedContactSearch($query)
    {
        if (strlen($query) > 2) {
            $this->recipient_email = null;

            if ($this->selectedCriteria == 'email') {

                $contacts = Contact::where('email', 'like', '%' . $query . '%')
                    ->when($this->userTypeFilter !== '', function ($q) {
                        $q->where('user_type', $this->userTypeFilter);
                    })
                    ->limit(5)
                    ->get()
                    ->toArray();

                $users = User::filterByRole()->where('email', 'like', "%{$query}%")
                    ->limit(5)
                    ->get()
                    ->toArray();


                $mergedResults = collect(array_merge($contacts, $users))
                    ->unique('email')
                    ->take(5)
                    ->values()
                    ->toArray();

                $this->filteredContacts = $mergedResults;
            } else {
                $contacts = Contact::where('phone', 'like', "%{$query}%")
                    ->when($this->userTypeFilter !== '', function ($q) {
                        $q->where('user_type', $this->userTypeFilter);
                    })
                    ->limit(5)
                    ->get()
                    ->toArray();

                $this->filteredContacts = $contacts;
            }
        } else {
            $this->filteredContacts = [];
        }
    }

    public function updatedUserTypeFilter()
    {
        $this->filteredContacts = [];
        $this->contactSearch = '';
    }


    public function addGroupContact($email)
    {
        if (!$this->errorMessage) {
            $contact = User::filterByRole()->where('email', $email)->first();
            if (!$contact) {
                $contact = Contact::where('email', $email)->first();
            }

            if ($contact && !isset($this->selectedRecipients[$email])) {
                $this->selectedRecipients[$email] = [
                    'id' => $contact->id,
                    'name' => $contact->name,
                    'email' => $contact->email,
                    'phone' => $contact->phone,
                ];

                $this->contactSearch = '';
                $this->filteredContacts = [];
            }
        }
    }


    public function addSingleContact($email)
    {
        if (!$this->errorMessage) {
            $contact = User::filterByRole()->where('email', $email)->first();
            if (!$contact) {
                $contact = Contact::where('email', $email)->first();
            }

            $this->selectedRecipients = [];
            if ($contact && !isset($this->selectedRecipients[$email])) {
                $this->selectedRecipients[$email] = [
                    'id' => $contact->id,
                    'name' => $contact->name,
                    'email' => $contact->email,
                    'phone' => $contact->phone,
                ];

                $this->contactSearch = '';
                $this->filteredContacts = [];
            }
        }
    }


    public function removeContact($criteriaType)
    {
        unset($this->selectedRecipients[$criteriaType]);
    }


    public function setRecipientEmail($email)
    {
        $this->recipient_email = $email;
    }


    public function loadTemplateContent()
    {
        $template = Template::query()->find($this->selectedTemplate);

        if ($template) {
            if (!empty($template->content)) {
                $this->content = $template->content;
                $this->tempId = $template->id;
                $this->isSelectTemp = true;
            } else {
                $this->dispatchBrowserEvent(
                    'alert',
                    ['type' => 'error', 'message' => 'The selected template does not contain any content.']
                );
                $this->content = ''; // Ensure no empty content is sent
                $this->isSelectTemp = false;
            }
        } else {
            $this->dispatchBrowserEvent(
                'alert',
                ['type' => 'error', 'message' => 'Please choose a valid template.']
            );
            $this->content = '';
            $this->isSelectTemp = false;
        }
    }


    public function resetInputFields()
    {
        $this->contactSearch = '';
        $this->recipient_email = null;
        $this->schedule_at = null;
        $this->subject = '';
        $this->content = '';
        $this->tempId = '';
        $this->errorMessage = '';
        $this->selectedTemplate = null;
        $this->isSelectTemp = false;
        $this->selectedRecipients = [];
        $this->filteredContacts = [];
        $this->templates = [];

        $this->resetErrorBag();
    }


    public function sendEmailMessage()
    {


        if ($this->content == '') {
            $this->dispatchBrowserEvent(
                'alert',
                ['type' => 'error',  'message' => 'Please write the message content before sending the email.']
            );
            return;
        }

        if ($this->subject == '') {
            $this->dispatchBrowserEvent(
                'alert',
                ['type' => 'error',  'message' => 'Please provide a subject for the email.']
            );
            return;
        }



        if (!$this->selectedRecipients && !$this->recipient_email) {
            $this->dispatchBrowserEvent(
                'alert',
                ['type' => 'error', 'message' => 'Please select at least one recipient before sending.']
            );
            return;
        }

        $isFoundGateway = MessageGateway::where('type', 'email')->first();

        if (!$isFoundGateway) {
            $this->dispatchBrowserEvent(
                'alert',
                ['type' => 'error',  'message' => 'No email gateway is configured. Please set one up in the gateway settings.']
            );
            return;
        }

        $defaultGateway = MessageGateway::where('status', 'default')->where('type', 'email')->first();

        if (!$defaultGateway) {
            $this->dispatchBrowserEvent(
                'alert',
                ['type' => 'error',  'message' => 'No default email gateway is set. Please select a default gateway in the settings.']
            );
            return;
        }

        try {
            foreach ($this->selectedRecipients as $recipient) {
                $originalContent = $this->content;

                $template = new Template();
                $html = $template->getPreviewContent($this->content);
                $domain = URL::to('/');
                $content = preg_replace('/(href|src)="\/admin\//', '$1="' . $domain . '/admin/', $html);

                if ($this->schedule_at) {
                    $userTimezone = auth()->user()->timezone ?? 'UTC';
                    $scheduleAt = Carbon::parse($this->schedule_at, $userTimezone)->setTimezone('UTC');

                    $message = Message::create([
                        'user_id' => null,
                        'gateway_id' => $defaultGateway->id,
                        'receiver_email' => $recipient['email'],
                        'content' => $content,
                        'subject' => $this->subject,
                        'schedule_at' => $scheduleAt,
                        'type' => 'email',
                    ]);

                    MessageHistory::create([
                        'message_id' => $message->id,
                        'status' => 'schedule',
                    ]);
                } else {
                    $message = Message::create([
                        'user_id' => null,
                        'gateway_id' => $defaultGateway->id,
                        'receiver_email' => $recipient['email'],
                        'content' => $content,
                        'subject' => $this->subject,
                        'type' => 'email',
                    ]);

                    MessageHistory::create([
                        'message_id' => $message->id,
                        'status' => 'pending',
                    ]);

                    $role = auth()->user()->role;
                    $companyId = 0;

                    if ($defaultGateway->is_gateway_type == 'brevo') {
                        BrevoHelper::sendEmail($recipient['email'], $this->subject, $content, $message->id, $defaultGateway, $role);
                    } else {
                        dispatch(new SendAdminEmailJob($recipient['email'], $this->subject, $content, $message->id));
                    }
                }
                $this->content = $originalContent;
            }

            $this->resetInputFields();
            $this->emit('flatpickr-reinitialize');
            if ($this->selectedCriteria == 'email') {
                $this->emit('initCKEditor');
            }
            $this->emit('initTooltip');
            $this->templates = Template::query()->where('is_default', '=', 0)->get(['id', 'name']);

            $this->dispatchBrowserEvent(
                'alert',
                ['type' => 'success', 'message' => $this->schedule_at ? 'Email scheduled successfully!' : 'Email sent successfully!']
            );
            $this->dispatchBrowserEvent('isEmailSent');
        } catch (\Exception $e) {
            $message = Message::create([
                'user_id' => null,
                'gateway_id' => $defaultGateway->id,
                'receiver_email' => $recipient['email'] ?? '',
                'content' => $content,
                'subject' => $this->subject,
                'type' => 'email',
            ]);

            MessageHistory::create([
                'message_id' => $message->id,
                'status' => 'failed',
            ]);

            $this->dispatchBrowserEvent(
                'alert',
                ['type' => 'error', 'message' => 'We were unable to send the email. Please try again later.']
            );
        }
    }



    public function sendSmsMessage()
    {
        if ($this->content == '') {
            $this->dispatchBrowserEvent(
                'alert',
                ['type' => 'error',  'message' => 'Please write the message content before sending.']
            );
            return;
        }



        if (!$this->selectedRecipients) {
            $this->dispatchBrowserEvent(
                'alert',
                ['type' => 'error',  'message' => 'Please select at least one recipient phone number before sending.']
            );
            return;
        }


        $isFoundGateway = MessageGateway::where('type', 'sms')
            ->first();

        if (!$isFoundGateway) {
            $this->dispatchBrowserEvent(
                'alert',
                ['type' => 'error',  'message' => 'No SMS gateway is configured. Please set one up in the gateway settings.']
            );
            return;
        }

        $defaultGateway = MessageGateway::where('status', 'default')->where('type', 'sms')
            ->first();

        if (!$defaultGateway) {
            $this->dispatchBrowserEvent(
                'alert',
                ['type' => 'error',  'message' => 'No default SMS gateway is set. Please select a default gateway in the settings.']
            );

            return;
        }


        try {
            foreach ($this->selectedRecipients as $recipient) {
                $originalContent = $this->content;


                if ($this->schedule_at) {
                    $userTimezone = auth()->user()->timezone ?? 'UTC';
                    $scheduleAt = Carbon::parse($this->schedule_at, $userTimezone)->setTimezone('UTC');

                    $message = Message::create([
                        'user_id' => null,
                        'gateway_id' => $defaultGateway->id,
                        'receiver_phone_no' => $recipient['phone'],
                        'content' => $this->content,
                        'schedule_at' => $scheduleAt,
                        'type' => 'phone',
                    ]);

                    $messageStatus = MessageHistory::create([
                        'message_id' => $message->id,
                        'status' => 'schedule',
                    ]);
                } else {

                    $message = Message::create([
                        'user_id' => null,
                        'gateway_id' => $defaultGateway->id,
                        'receiver_phone_no' => $recipient['phone'],
                        'content' => $this->content,
                        'type' => 'phone',
                    ]);

                    $messageStatus = MessageHistory::create([
                        'message_id' => $message->id,
                        'status' => 'pending',
                    ]);

                    $role = auth()->user()->role;
                    $brandName = getApplicationName() ?? 'Maxpage';

                    if ($defaultGateway == 'twilio') {
                        SmsHelper::sendSms($recipient['phone'], $this->content, $message->id, $defaultGateway, $role);
                    } else {
                        dispatch(new SendBrevoSmsJob($brandName, $recipient['phone'], $this->content, $message->id, $defaultGateway, $role));
                    }
                }

                $this->content = $originalContent;
            }


            $this->resetInputFields();
            $this->emit('flatpickr-reinitialize');
            $this->emit('initTooltip');
            $this->templates = Template::query()->where('is_default', '=', 0)->get(['id', 'name']);

            if ($this->schedule_at) {
                $this->dispatchBrowserEvent(
                    'alert',
                    ['type' => 'success', 'message' => 'SMS scheduled successfully!']
                );
            } else {
                $this->dispatchBrowserEvent(
                    'alert',
                    ['type' => 'success', 'message' => 'SMS sent successfully!']
                );
            }
        } catch (\Exception $e) {
            $message = Message::create([
                'user_id' => null,
                'gateway_id' => $defaultGateway->id,
                'receiver_phone_no' => $recipient['phone'],
                'content' => $this->content,
                'type' => 'phone',
            ]);

            $messageStatus = MessageHistory::create([
                'message_id' => $message->id,
                'status' => 'failed',
            ]);


            $this->dispatchBrowserEvent(
                'alert',
                ['type' => 'error',  'message' => 'Something went wrong while sending the message. Please try again later.']
            );
        }
    }


    public function sendWhatsappMessage()
    {

        if ($this->content == '') {
            $this->dispatchBrowserEvent(
                'alert',
                ['type' => 'error',  'message' => 'Please write the message content before sending.']
            );
            return;
        }



        if (!$this->selectedRecipients) {
            $this->dispatchBrowserEvent(
                'alert',
                ['type' => 'error',  'message' => 'Please select at least one recipient WhatsApp number before sending.']
            );
            return;
        }



        $isFoundGateway = MessageGateway::where('type', 'whatsapp')
            ->first();

        if (!$isFoundGateway) {
            $this->dispatchBrowserEvent(
                'alert',
                ['type' => 'error',  'message' => 'No WhatsApp gateway is configured. Please set one up in the gateway settings.']
            );
            return;
        }

        $defaultGateway = MessageGateway::where('status', 'default')->where('type', 'whatsapp')
            ->first();

        if (!$defaultGateway) {
            $this->dispatchBrowserEvent(
                'alert',
                ['type' => 'error',  'message' => 'No default WhatsApp gateway is set. Please select a default gateway in the settings.']
            );

            return;
        }


        try {
            foreach ($this->selectedRecipients as $recipient) {
                $originalContent = $this->content;



                if ($this->schedule_at) {
                    $userTimezone = auth()->user()->timezone ?? 'UTC';
                    $scheduleAt = Carbon::parse($this->schedule_at, $userTimezone)->setTimezone('UTC');

                    $message = Message::create([
                        'user_id' => null,
                        'gateway_id' => $defaultGateway->id,
                        'receiver_phone_no' => $recipient['phone'],
                        'content' => $this->content,
                        'schedule_at' => $scheduleAt,
                        'type' => 'whatsapp',
                    ]);

                    $messageStatus = MessageHistory::create([
                        'message_id' => $message->id,
                        'status' => 'schedule',
                    ]);
                } else {

                    $message = Message::create([
                        'user_id' => null,
                        'gateway_id' => $defaultGateway->id,
                        'receiver_phone_no' => $recipient['phone'],
                        'content' => $this->content,
                        'type' => 'whatsapp',
                    ]);

                    $messageStatus = MessageHistory::create([
                        'message_id' => $message->id,
                        'status' => 'pending',
                    ]);



                    WhatsappHelper::sendWhatsappMessage($recipient['phone'], $this->content, $defaultGateway);
                }

                $this->content = $originalContent;
            }

            $this->resetInputFields();
            $this->emit('flatpickr-reinitialize');
            $this->emit('initTooltip');
            $this->templates = Template::query()->where('is_default', '=', 0)->get(['id', 'name']);

            if ($this->schedule_at) {
                $this->dispatchBrowserEvent(
                    'alert',
                    ['type' => 'success', 'message' => 'Message scheduled successfully!']
                );
            } else {
                $this->dispatchBrowserEvent(
                    'alert',
                    ['type' => 'success', 'message' => 'Message sent successfully!']
                );
            }



            $this->dispatchBrowserEvent('isEmailSent');
        } catch (\Exception $e) {
            $message = Message::create([
                'user_id' => null,
                'gateway_id' => $defaultGateway->id,
                'receiver_phone_no' => $recipient['phone'],
                'content' => $this->content,
                'type' => 'whatsapp',
            ]);

            $messageStatus = MessageHistory::create([
                'message_id' => $message->id,
                'status' => 'failed',
            ]);


            $this->dispatchBrowserEvent(
                'alert',
                ['type' => 'error',  'message' => 'We were unable to send the message. Please try again later.']
            );
            return;
        }
    }


    public function render()
    {
        $this->userTypes = Contact::select('user_type')
            ->whereNotNull('user_type')
            ->where('user_type', '!=', '')
            ->distinct()
            ->orderBy('user_type')
            ->pluck('user_type')
            ->toArray();

        return view('livewire.admin.message-hub.send-message');
    }
}
