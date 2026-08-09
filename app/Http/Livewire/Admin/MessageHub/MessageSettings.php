<?php

namespace App\Http\Livewire\Admin\MessageHub;

use App\Models\MessageGateway;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\Cursor;

class MessageSettings extends Component
{
    public $selectedGateway = 'email', $search = '';
    public $mail_gateway_name;
    public $mail_gateway_email;
    public $mail_gateway_type = 'smtp';
    public $mail_host;
    public $mail_driver;
    public $mail_port;
    public $mail_api_key;
    public $mail_encryption;
    public $mail_username;
    public $mail_password;
    public $items;
    public $item;
    public $status;
    public $selectedEmailGatewayView;
    public $selectedWhatsappGatewayView;
    protected $currentCursor;


    public $sms_key, $sms_sender_name, $sms_type, $key_ident;

    public $whatsapp_business_name, $whatsapp_access_token, $whatsapp_no_id, $whatsapp_account_id;

    public $serviceProvider = 'whatsapp_business';
    public $twilio_account_sid;
    public $twilio_auth_token;
    public $twilio_phone_number;
    public $brevo_api_key;

    public $editMode = false;
    public $nextCursor;
    public $hasMorePages;

    protected $listeners = ['deleteGateway' => 'delete'];

    public function selectGateway($gateway)
    {
        $this->items = [];
        $this->selectedGateway = $gateway;

        $this->resetInputFields();

        if ($this->selectedGateway == 'email') {
            $this->items = MessageGateway::where('type', 'email')->latest()->get();
        } else if ($this->selectedGateway == 'sms') {
            $this->items = MessageGateway::where('type', 'sms')->latest()->get();
        } else {
            $this->items = MessageGateway::where('type', 'whatsapp')->latest()->get();
        }
    }

    public function mount()
    {

        $this->items = new EloquentCollection();

        $this->loadItems();
    }



    public function render()
    {
        return view('livewire.admin.message-hub.message-settings');
    }

    public function updated($name, $value)
    {
        if ($name == 'search' && $value != '') {
            $this->reloadItems();
        } elseif ($name == 'search' && $value == '') {

            $this->items = new EloquentCollection();
            $this->reloadItems();
        }
    }



    public function refresh()
    {
        if ($this->search == '') {
            $this->items = $this->items->fresh();
        }
    }
    public function loadItems()
    {
        if ($this->hasMorePages !== null && !$this->hasMorePages) {
            return;
        }
        $itemList = $this->filterData();
        $this->items->push(...$itemList->items());
        if ($this->hasMorePages = $itemList->hasMorePages()) {
            $this->nextCursor = $itemList->nextCursor()->encode();
        }
        $this->currentCursor = $itemList->cursor();
    }
    public function filterData()
    {
        if ($this->search || $this->search != '') {
            if ($this->selectedGateway == 'email') {
                $data = MessageGateway::where('mail_gateway_name', 'like', '%' . $this->search . '%')->where('type', 'email')
                    ->latest()
                    ->cursorPaginate(10, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));
            } else if ($this->selectedGateway == 'sms') {
                $data = MessageGateway::where('sms_sender_name', 'like', '%' . $this->search . '%')->where('type', 'sms')
                    ->latest()
                    ->cursorPaginate(10, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));
            } else {
                $data = MessageGateway::where('whatsapp_business_name', 'like', '%' . $this->search . '%')->where('type', 'whatsapp')
                    ->latest()
                    ->cursorPaginate(10, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));
            }

            return $data;
        } else {
            if ($this->selectedGateway == 'email') {
                $data = MessageGateway::where('type', 'email')
                    ->latest()
                    ->cursorPaginate(10, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));
            } else if ($this->selectedGateway == 'sms') {
                $data = MessageGateway::where('type', 'sms')
                    ->latest()
                    ->cursorPaginate(10, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));
            } else {
                $data = MessageGateway::where('type', 'whatsapp')
                    ->latest()
                    ->cursorPaginate(10, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));
            }

            return $data;
        }
    }
    public function reloadItems()
    {
        $this->items = new EloquentCollection();
        $this->nextCursor = null;
        $this->hasMorePages = null;
        if ($this->hasMorePages !== null && !$this->hasMorePages) {
            return;
        }
        $items = $this->filterData();
        $this->items->push(...$items->items());
        if ($this->hasMorePages = $items->hasMorePages()) {
            $this->nextCursor = $items->nextCursor()->encode();
        }
        $this->currentCursor = $items->cursor();
    }


    public function resetInputFields()
    {
        $this->mail_gateway_name = null;
        $this->mail_gateway_email = null;
        $this->mail_gateway_type = 'smtp';
        $this->mail_host = null;
        $this->mail_driver = null;
        $this->mail_port = null;
        $this->mail_api_key = null;
        $this->mail_encryption = null;
        $this->mail_username = null;
        $this->mail_password = null;
        $this->sms_key = '';
        $this->sms_sender_name = '';
        $this->sms_type = '';
        $this->key_ident = '';
        $this->whatsapp_business_name = '';
        $this->whatsapp_access_token = '';
        $this->whatsapp_no_id = '';
        $this->whatsapp_account_id = '';
        $this->item = null;
        $this->selectedEmailGatewayView = null;
        $this->selectedWhatsappGatewayView = null;
        $this->serviceProvider = 'whatsapp_business';
        $this->twilio_account_sid = '';
        $this->twilio_auth_token = '';
        $this->twilio_phone_number = '';
        $this->search = '';
        $this->brevo_api_key = '';


        $this->resetErrorBag();
    }




    public function storeEmailGateway()
    {
        if ($this->serviceProvider === 'brevo') {
            $this->validate([
                'mail_gateway_email' => 'required|email|max:191',
                'mail_api_key' => 'required|string|max:255',
            ]);

            if (auth()->user()->role == 'guest') {
                MessageGateway::create([
                    'mail_gateway_name' => "Brevo",
                    'mail_gateway_email' => $this->mail_gateway_email,
                    'mail_api_key' => $this->mail_api_key,
                    'type' => 'email',
                    'status' => 'inactive',
                    'is_gateway_type' => 'brevo',
                    'is_guest' => true,
                    'company_id' => auth()->user()->id,

                ]);
            } else if (auth()->user()->role == 'company') {
                MessageGateway::create([
                    'mail_gateway_name' => "Brevo",
                    'mail_gateway_email' => $this->mail_gateway_email,
                    'mail_api_key' => $this->mail_api_key,
                    'type' => 'email',
                    'status' => 'inactive',
                    'is_gateway_type' => 'brevo',
                    'company_id' => auth()->user()->id,
                ]);
            } else {
                MessageGateway::create([
                    'mail_gateway_name' => "Brevo",
                    'mail_gateway_email' => $this->mail_gateway_email,
                    'mail_api_key' => $this->mail_api_key,
                    'type' => 'email',
                    'status' => 'inactive',
                    'is_gateway_type' => 'brevo',

                ]);
            }
        } else {
            $this->validate([
                'mail_gateway_name' => 'required|string|max:191',
                'mail_gateway_email' => 'required|email|max:191',
                'mail_gateway_type' => 'required|in:smtp',
                'mail_host' => 'required|string|max:191',
                'mail_driver' => 'nullable|string|max:191',
                'mail_port' => 'required|integer',
                'mail_encryption' => 'required|string|max:191',
                'mail_username' => 'required|string|max:191',
                'mail_password' => 'required|string|max:191',
            ]);




            if (auth()->user()->role == 'guest') {
                MessageGateway::create([
                    'mail_gateway_name' => $this->mail_gateway_name,
                    'mail_gateway_email' => $this->mail_gateway_email,
                    'mail_gateway_type' => $this->mail_gateway_type,
                    'mail_host' => $this->mail_host,
                    'mail_driver' => $this->mail_driver,
                    'mail_port' => $this->mail_port,
                    'mail_encryption' => $this->mail_encryption,
                    'mail_username' => $this->mail_username,
                    'mail_password' => $this->mail_password,
                    'type' => 'email',
                    'status' => 'inactive',
                    'is_gateway_type' => 'other',
                    'is_guest' => true,
                    'company_id' => auth()->user()->id,
                ]);
            } else if (auth()->user()->role == 'company') {
                MessageGateway::create([
                    'mail_gateway_name' => $this->mail_gateway_name,
                    'mail_gateway_email' => $this->mail_gateway_email,
                    'mail_gateway_type' => $this->mail_gateway_type,
                    'mail_host' => $this->mail_host,
                    'mail_driver' => $this->mail_driver,
                    'mail_port' => $this->mail_port,
                    'mail_encryption' => $this->mail_encryption,
                    'mail_username' => $this->mail_username,
                    'mail_password' => $this->mail_password,
                    'type' => 'email',
                    'status' => 'inactive',
                    'is_gateway_type' => 'other',
                    'company_id' => auth()->user()->id,
                ]);
            } else {
                MessageGateway::create([
                    'mail_gateway_name' => $this->mail_gateway_name,
                    'mail_gateway_email' => $this->mail_gateway_email,
                    'mail_gateway_type' => $this->mail_gateway_type,
                    'mail_host' => $this->mail_host,
                    'mail_driver' => $this->mail_driver,
                    'mail_port' => $this->mail_port,
                    'mail_encryption' => $this->mail_encryption,
                    'mail_username' => $this->mail_username,
                    'mail_password' => $this->mail_password,
                    'type' => 'email',
                    'status' => 'inactive',
                    'is_gateway_type' => 'other',
                ]);
            }
        }



        $this->resetInputFields();
        if ($this->selectedGateway == 'email') {
            $this->items = MessageGateway::where('type', 'email')->latest()->get();
        } else if ($this->selectedGateway == 'sms') {
            $this->items = MessageGateway::where('type', 'sms')->latest()->get();
        } else {
            $this->items = MessageGateway::where('type', 'whatsapp')->latest()->get();
        }


        $this->emit('closemodal');
        $this->dispatchBrowserEvent(
            'alert',
            ['type' => 'success', 'message' => 'Email gateway has been created!']
        );
    }


    public function editEmailInfo($id)
    {
        $this->editMode = true;
        $this->item = MessageGateway::findOrFail($id);

        $this->mail_gateway_name = $this->item->mail_gateway_name;
        $this->mail_gateway_email = $this->item->mail_gateway_email;
        $this->mail_host = $this->item->mail_host;
        $this->mail_port = $this->item->mail_port;
        $this->mail_driver = $this->item->mail_driver;
        $this->mail_username = $this->item->mail_username;
        $this->mail_password = $this->item->mail_password;
        $this->mail_encryption = $this->item->mail_encryption;
        $this->mail_encryption = $this->item->mail_encryption;
        $this->mail_api_key = $this->item->mail_api_key;
        $this->status = $this->item->status;
        $this->serviceProvider = $this->item->is_gateway_type;
    }


    /* update email gateway details */
    public function updateEmailGatewayInfo()
    {
        if ($this->serviceProvider === 'other') {
            $this->validate([
                'mail_gateway_name' => 'required|string|max:191',
                'mail_gateway_email' => 'required|email|max:191',
                'mail_gateway_type' => 'required|in:smtp',
                'mail_host' => 'required|string|max:191',
                'mail_driver' => 'nullable|string|max:191',
                'mail_port' => 'required|integer',
                'mail_encryption' => 'required|string|max:191',
                'mail_username' => 'required|string|max:191',
                'mail_password' => 'required|string|max:191',
            ]);


            $this->item->update([
                'mail_gateway_name' => $this->mail_gateway_name,
                'mail_gateway_email' => $this->mail_gateway_email,
                'mail_gateway_type' => $this->mail_gateway_type,
                'mail_host' => $this->mail_host,
                'mail_driver' => $this->mail_driver,
                'mail_port' => $this->mail_port,
                'mail_encryption' => $this->mail_encryption,
                'mail_username' => $this->mail_username,
                'mail_password' => $this->mail_password,
                'is_gateway_type' => 'other',
            ]);

            if (auth()->user()->role !== 'guest' || auth()->user()->role !== 'company') {
                if ($this->item->status == 'default') {
                    $path = base_path('.env');
                    $content = file_get_contents($path);
                    $content = preg_replace('/^MAIL_HOST=.*/m', 'MAIL_HOST=' . $this->mail_host, $content);
                    $content = preg_replace('/^MAIL_PORT=.*/m', 'MAIL_PORT=' . $this->mail_port, $content);
                    $content = preg_replace('/^MAIL_ENCRYPTION=.*/m', 'MAIL_ENCRYPTION=' . $this->mail_encryption, $content);
                    $content = preg_replace('/^MAIL_USERNAME=.*/m', 'MAIL_USERNAME=' . $this->mail_username, $content);
                    $content = preg_replace('/^MAIL_PASSWORD=.*/m', 'MAIL_PASSWORD=' . $this->mail_password, $content);
                    $content = preg_replace('/^MAIL_FROM_ADDRESS=.*/m', 'MAIL_FROM_ADDRESS=' . $this->mail_gateway_email, $content);

                    file_put_contents($path, $content);
                    Artisan::call('config:clear');
                }
            }
        }


        if ($this->serviceProvider === 'brevo') {
            $this->validate([
                'mail_api_key' => 'required|string|max:255',
                'mail_gateway_email' => 'required|email|max:191',
            ]);


            $this->item->update([
                'mail_gateway_name' => "Brevo",
                'mail_api_key' => $this->mail_api_key,
                'mail_gateway_email' => $this->mail_gateway_email,
                'is_gateway_type' => 'brevo',
            ]);



            if (auth()->user()->role !== 'guest' || auth()->user()->role !== 'company') {
                if ($this->item->status == 'default') {
                    $path = base_path('.env');
                    $content = file_get_contents($path);

                    $content = preg_replace('/^MAIL_API_KEY=.*/m', 'MAIL_API_KEY=' . $this->mail_api_key, $content);
                    $content = preg_replace('/^MAIL_FROM_ADDRESS=.*/m', 'MAIL_FROM_ADDRESS=' . $this->mail_gateway_email, $content);

                    file_put_contents($path, $content);
                    Artisan::call('config:clear');
                }
            }
        }


        $this->refresh();
        $this->resetInputFields();

        if ($this->selectedGateway == 'email') {
            $this->items = MessageGateway::where('type', 'email')->latest()->get();
        } else if ($this->selectedGateway == 'sms') {
            $this->items = MessageGateway::where('type', 'sms')->latest()->get();
        } else {
            $this->items = MessageGateway::where('type', 'whatsapp')->latest()->get();
        }

        $this->editMode = false;
        $this->emit('closemodal');
        $this->dispatchBrowserEvent(
            'alert',
            ['type' => 'success', 'message' => 'Email gateway info has been updated!']
        );
    }




    public function quickEmailView($id)
    {
        $this->selectedEmailGatewayView = MessageGateway::find($id);
        if ($this->selectedEmailGatewayView->is_gateway_type == 'brevo') {
            $this->serviceProvider = 'brevo';
        } else {
            $this->serviceProvider = 'other';
        }
    }


    public function quickWhatsappView($id)
    {
        $this->selectedWhatsappGatewayView = MessageGateway::find($id);
        if ($this->selectedWhatsappGatewayView->is_gateway_type == 'twilio') {
            $this->serviceProvider = 'twilio';
        } else {
            $this->serviceProvider = 'whatsapp_business';
        }
    }



    public function storeSmsGateway()
    {

        if ($this->serviceProvider === 'twilio') {

            $this->validate([
                'twilio_account_sid' => 'required|string|max:191',
                'twilio_auth_token' => 'required|string|max:191',
                'twilio_phone_number' => 'required|string|max:15',
                'sms_type' => 'required|string|max:191',
            ]);

            if (auth()->user()->role == 'guest') {
                MessageGateway::create([
                    'twilio_account_sid' => $this->twilio_account_sid,
                    'twilio_auth_token' => $this->twilio_auth_token,
                    'sms_type' => $this->sms_type,
                    'twilio_phone_number' => $this->twilio_phone_number,
                    'type' => 'sms',
                    'status' => 'inactive',
                    'is_gateway_type' => 'twilio',
                    'is_guest' => true,
                    'company_id' => auth()->user()->id,
                ]);
            } else if (auth()->user()->role == 'company') {
                MessageGateway::create([
                    'twilio_account_sid' => $this->twilio_account_sid,
                    'twilio_auth_token' => $this->twilio_auth_token,
                    'sms_type' => $this->sms_type,
                    'twilio_phone_number' => $this->twilio_phone_number,
                    'type' => 'sms',
                    'status' => 'inactive',
                    'is_gateway_type' => 'twilio',
                    'company_id' => auth()->user()->id,
                ]);
            } else {
                MessageGateway::create([
                    'twilio_account_sid' => $this->twilio_account_sid,
                    'twilio_auth_token' => $this->twilio_auth_token,
                    'sms_type' => $this->sms_type,
                    'twilio_phone_number' => $this->twilio_phone_number,
                    'type' => 'sms',
                    'is_gateway_type' => 'twilio',
                    'status' => 'inactive',
                ]);
            }
        } else if ($this->serviceProvider === 'brevo') {
            $this->validate([
                'brevo_api_key' => 'required|string|max:191',
            ]);

            if (auth()->user()->role == 'guest') {
                MessageGateway::create([
                    'brevo_sms_api_key' => $this->brevo_api_key,
                    'is_gateway_type' => 'brevo',
                    'type' => 'sms',
                    'status' => 'inactive',
                    'is_guest' => true,
                    'company_id' => auth()->user()->id,
                ]);
            } else if (auth()->user()->role == 'company') {
                MessageGateway::create([
                    'brevo_sms_api_key' => $this->brevo_api_key,
                    'type' => 'sms',
                    'status' => 'inactive',
                    'is_gateway_type' => 'brevo',
                    'company_id' => auth()->user()->id,
                ]);
            } else {
                MessageGateway::create([
                    'brevo_sms_api_key' => $this->brevo_api_key,
                    'type' => 'sms',
                    'is_gateway_type' => 'brevo',
                    'status' => 'inactive',
                ]);
            }
        }



        $this->resetInputFields();
        if ($this->selectedGateway == 'email') {
            $this->items = MessageGateway::where('type', 'email')->latest()->get();
        } else if ($this->selectedGateway == 'sms') {
            $this->items = MessageGateway::where('type', 'sms')->latest()->get();
        } else {
            $this->items = MessageGateway::where('type', 'whatsapp')->latest()->get();
        }
        $this->emit('closemodal');
        $this->dispatchBrowserEvent(
            'alert',
            ['type' => 'success', 'message' => 'SMS gateway has been created!']
        );
    }



    public function editSmsInfo($id)
    {
        $this->editMode = true;
        $this->item = MessageGateway::find($id);
        $this->twilio_account_sid = $this->item->twilio_account_sid;
        $this->twilio_auth_token = $this->item->twilio_auth_token;
        $this->sms_type = $this->item->sms_type;
        $this->twilio_phone_number = $this->item->twilio_phone_number;
        $this->brevo_api_key = $this->item->brevo_sms_api_key;
        $this->serviceProvider = $this->item->is_gateway_type;
    }


    public function updateSmsGatewayInfo()
    {
        if ($this->serviceProvider === 'twilio') {
            $this->validate([
                'twilio_account_sid' => 'required|string|max:191',
                'twilio_auth_token' => 'required|string|max:191',
                'twilio_phone_number' => 'required|string|max:15',
                'sms_type' => 'required|string|max:191',
            ]);


            $this->item->update([
                'mail_gateway_name' => $this->mail_gateway_name,
                'twilio_auth_token' => $this->twilio_auth_token,
                'twilio_phone_number' => $this->twilio_phone_number,
                'sms_type' => $this->sms_type,
                'twilio_account_sid' => $this->twilio_account_sid,
                'brevo_sms_api_key' => null,
                'is_gateway_type' => 'twilio',
            ]);

            if (auth()->user()->role !== 'guest' || auth()->user()->role !== 'company') {
                if ($this->item->status == 'default') {
                    $path = base_path('.env');
                    $content = file_get_contents($path);

                    $content = preg_replace('/^TWILIO_SID=.*/m', 'TWILIO_SID=' . $this->item->twilio_account_sid, $content);
                    $content = preg_replace('/^TWILIO_AUTH_TOKEN=.*/m', 'TWILIO_AUTH_TOKEN=' . $this->item->twilio_auth_token, $content);
                    $content = preg_replace('/^TWILIO_PHONE_NUMBER=.*/m', 'TWILIO_PHONE_NUMBER=' . $this->item->twilio_phone_number, $content);

                    file_put_contents($path, $content);
                    Artisan::call('config:clear');
                }
            }
        }


        if ($this->serviceProvider === 'brevo') {
            $this->validate([
                'brevo_api_key' => 'required|string|max:191',
            ]);


            $this->item->update([
                'mail_gateway_name' => null,
                'twilio_auth_token' => null,
                'twilio_phone_number' => null,
                'sms_type' => null,
                'twilio_account_sid' => null,
                'brevo_sms_api_key' => $this->brevo_api_key,
                'is_gateway_type' => 'brevo',
            ]);

            if (auth()->user()->role !== 'guest' || auth()->user()->role !== 'company') {
                if ($this->item->status == 'default') {
                    $path = base_path('.env');
                    $content = file_get_contents($path);

                    $content = preg_replace('/^BREVO_SMS_API_KEY=.*/m', 'BREVO_SMS_API_KEY=' . $this->item->brevo_sms_api_key, $content);

                    file_put_contents($path, $content);
                    Artisan::call('config:clear');
                }
            }
        }



        $this->resetInputFields();


        if ($this->selectedGateway == 'email') {
            $this->items = MessageGateway::where('type', 'email')->latest()->get();
        } else if ($this->selectedGateway == 'sms') {
            $this->items = MessageGateway::where('type', 'sms')->latest()->get();
        } else {
            $this->items = MessageGateway::where('type', 'whatsapp')->latest()->get();
        }
        $this->emit('closemodal');
        $this->dispatchBrowserEvent(
            'alert',
            ['type' => 'success', 'message' => 'SMS gateway updated successfully!']
        );
    }




    public function storeWhatsappGateway()
    {
        if ($this->serviceProvider === 'whatsapp_business') {
            $this->validate([
                'whatsapp_business_name' => 'required|string|max:191',
                'whatsapp_access_token' => 'required|string|max:255',
                'whatsapp_no_id' => 'required|string|max:255',
                'whatsapp_account_id' => 'required|string|max:191',
            ]);


            if (auth()->user()->role == 'guest') {
                MessageGateway::create([
                    'whatsapp_business_name' => $this->whatsapp_business_name,
                    'whatsapp_access_token' => $this->whatsapp_access_token,
                    'whatsapp_no_id' => $this->whatsapp_no_id,
                    'whatsapp_account_id' => $this->whatsapp_account_id,
                    'type' => 'whatsapp',
                    'status' => 'inactive',
                    'is_gateway_type' => 'whatsapp_business',
                    'is_guest' => true,
                    'company_id' => auth()->user()->id,
                ]);
            } else if (auth()->user()->role == 'company') {
                MessageGateway::create([
                    'whatsapp_business_name' => $this->whatsapp_business_name,
                    'whatsapp_access_token' => $this->whatsapp_access_token,
                    'whatsapp_no_id' => $this->whatsapp_no_id,
                    'whatsapp_account_id' => $this->whatsapp_account_id,
                    'type' => 'whatsapp',
                    'status' => 'inactive',
                    'is_gateway_type' => 'whatsapp_business',
                    'company_id' => auth()->user()->id,
                ]);
            } else {
                MessageGateway::create([
                    'whatsapp_business_name' => $this->whatsapp_business_name,
                    'whatsapp_access_token' => $this->whatsapp_access_token,
                    'whatsapp_no_id' => $this->whatsapp_no_id,
                    'whatsapp_account_id' => $this->whatsapp_account_id,
                    'type' => 'whatsapp',
                    'status' => 'inactive',
                    'is_gateway_type' => 'whatsapp_business',
                ]);
            }
        }

        if ($this->serviceProvider === 'twilio') {
            $this->validate([
                'twilio_account_sid' => 'required|string|max:255',
                'twilio_auth_token' => 'required|string|max:255',
                'twilio_phone_number' => 'required|string|max:20',
            ]);


            if (auth()->user()->role == 'guest') {
                MessageGateway::create([
                    'twilio_account_sid' => $this->twilio_account_sid,
                    'twilio_auth_token' => $this->twilio_auth_token,
                    'twilio_phone_number' => $this->twilio_phone_number,
                    'type' => 'whatsapp',
                    'status' => 'inactive',
                    'is_gateway_type' => 'twilio',
                    'is_guest' => true,
                    'company_id' => auth()->user()->id,
                ]);
            } else if (auth()->user()->role == 'company') {
                MessageGateway::create([
                    'twilio_account_sid' => $this->twilio_account_sid,
                    'twilio_auth_token' => $this->twilio_auth_token,
                    'twilio_phone_number' => $this->twilio_phone_number,
                    'type' => 'whatsapp',
                    'status' => 'inactive',
                    'is_gateway_type' => 'twilio',
                    'company_id' => auth()->user()->id,
                ]);
            } else {
                MessageGateway::create([
                    'twilio_account_sid' => $this->twilio_account_sid,
                    'twilio_auth_token' => $this->twilio_auth_token,
                    'twilio_phone_number' => $this->twilio_phone_number,
                    'type' => 'whatsapp',
                    'status' => 'inactive',
                    'is_gateway_type' => 'twilio',
                ]);
            }
        }



        $this->resetInputFields();
        if ($this->selectedGateway == 'email') {
            $this->items = MessageGateway::where('type', 'email')->latest()->get();
        } else if ($this->selectedGateway == 'sms') {
            $this->items = MessageGateway::where('type', 'sms')->latest()->get();
        } else {
            $this->items = MessageGateway::where('type', 'whatsapp')->latest()->get();
        }
        $this->emit('closemodal');
        $this->dispatchBrowserEvent(
            'alert',
            ['type' => 'success', 'message' => 'WhatsApp gateway has been created!']
        );
    }


    public function editWhatsappInfo($id)
    {
        $this->editMode = true;
        $this->item = MessageGateway::find($id);
        if ($this->item->is_gateway_type == 'twilio') {
            $this->serviceProvider = 'twilio';
        } else {
            $this->serviceProvider = 'whatsapp_business';
        }
        $this->whatsapp_business_name = $this->item->whatsapp_business_name;
        $this->whatsapp_access_token = $this->item->whatsapp_access_token;
        $this->whatsapp_no_id = $this->item->whatsapp_no_id;
        $this->whatsapp_account_id = $this->item->whatsapp_account_id;
        $this->twilio_phone_number = $this->item->twilio_phone_number;
        $this->twilio_auth_token = $this->item->twilio_auth_token;
        $this->twilio_account_sid = $this->item->twilio_account_sid;
    }



    public function updateWhatsappGateway()
    {

        if ($this->serviceProvider === 'whatsapp_business') {
            $this->validate([
                'whatsapp_business_name' => 'required|string|max:191',
                'whatsapp_access_token' => 'required|string|max:255',
                'whatsapp_no_id' => 'required|string|max:255',
                'whatsapp_account_id' => 'required|string|max:191',
            ]);


            $this->item->update([
                'whatsapp_business_name' => $this->whatsapp_business_name,
                'whatsapp_access_token' => $this->whatsapp_access_token,
                'whatsapp_no_id' => $this->whatsapp_no_id,
                'whatsapp_account_id' => $this->whatsapp_account_id,
                'twilio_account_sid' => null,
                'twilio_auth_token' => null,
                'twilio_phone_number' => null,
                'is_gateway_type' => 'whatsapp_business',
            ]);

            if (auth()->user()->role !== 'guest' || auth()->user()->role !== 'company') {
                if ($this->item->status == 'default') {
                    $path = base_path('.env');
                    $content = file_get_contents($path);

                    $content = preg_replace('/^WHATSAPP_ACCESS_TOKEN=.*/m', 'WHATSAPP_ACCESS_TOKEN=' . $this->item->whatsapp_access_token, $content);
                    $content = preg_replace('/^WHATSAPP_PHONE_NUMBER_ID=.*/m', 'WHATSAPP_PHONE_NUMBER_ID=' . $this->item->whatsapp_no_id, $content);

                    file_put_contents($path, $content);
                    Artisan::call('config:clear');
                }
            }
        }

        if ($this->serviceProvider === 'twilio') {
            $this->validate([
                'twilio_account_sid' => 'required|string|max:255',
                'twilio_auth_token' => 'required|string|max:255',
                'twilio_phone_number' => 'required|string|max:20',
            ]);



            $this->item->update([
                'twilio_account_sid' => $this->twilio_account_sid,
                'twilio_auth_token' => $this->twilio_auth_token,
                'twilio_phone_number' => $this->twilio_phone_number,
                'whatsapp_business_name' => null,
                'whatsapp_access_token' => null,
                'whatsapp_no_id' => null,
                'whatsapp_account_id' => null,
                'is_gateway_type' => 'twilio',
            ]);


            if (auth()->user()->role !== 'guest' || auth()->user()->role !== 'company') {
                if ($this->item->status == 'default') {
                    $path = base_path('.env');
                    $content = file_get_contents($path);

                    $content = preg_replace('/^TWILIO_PHONE=.*/m', 'TWILIO_PHONE=' . $this->item->twilio_phone_number, $content);
                    $content = preg_replace('/^TWILIO_SID=.*/m', 'TWILIO_SID=' . $this->item->twilio_account_sid, $content);
                    $content = preg_replace('/^TWILIO_TOKEN=.*/m', 'TWILIO_TOKEN=' . $this->item->twilio_auth_token, $content);
                    file_put_contents($path, $content);
                    Artisan::call('config:clear');
                }
            }
        }



        $this->resetInputFields();
        if ($this->selectedGateway == 'email') {
            $this->items = MessageGateway::where('type', 'email')->latest()->get();
        } else if ($this->selectedGateway == 'sms') {
            $this->items = MessageGateway::where('type', 'sms')->latest()->get();
        } else {
            $this->items = MessageGateway::where('type', 'whatsapp')->latest()->get();
        }
        $this->emit('closemodal');
        $this->dispatchBrowserEvent(
            'alert',
            ['type' => 'success', 'message' => 'WhatsApp gateway updated successfully!']
        );
    }



    public function delete($id)
    {
        $item = MessageGateway::find($id);
        $item->delete();

        $this->resetInputFields();

        if ($this->selectedGateway == 'email') {
            $this->items = MessageGateway::where('type', 'email')->latest()->get();
        } else if ($this->selectedGateway == 'sms') {
            $this->items = MessageGateway::where('type', 'sms')->latest()->get();
        } else {
            $this->items = MessageGateway::where('type', 'whatsapp')->latest()->get();
        }
        $this->emit('closemodal');
        $this->dispatchBrowserEvent(
            'alert',
            ['type' => 'success', 'message' => 'Gateway deleted successfully.']
        );
    }

    public function setDefault($id)
    {
        $gateway = MessageGateway::find($id);
        if ($gateway->status == 'default') {
            $gateway->update(['status' => 'inactive']);
        } else {
            if (auth()->user()->role == 'company') {
                MessageGateway::where('type', $gateway->type)
                    ->where('company_id', $gateway->company_id)
                    ->update(['status' => 'inactive']);
                $gateway->update(['status' => 'default']);
            } else {
                MessageGateway::where('type', $gateway->type)->update(['status' => 'inactive']);
                $gateway->update(['status' => 'default']);
            }



            if (auth()->user()->role !== 'company') {
                if ($this->selectedGateway == 'email') {
                    $path = base_path('.env');
                    $content = file_get_contents($path);

                    if ($gateway->is_gateway_type == 'brevo') {
                        $content = preg_replace('/^MAIL_API_KEY=.*/m', 'MAIL_API_KEY=' . $gateway->mail_api_key, $content);
                        $content = preg_replace('/^MAIL_FROM_ADDRESS=.*/m', 'MAIL_FROM_ADDRESS=' . $gateway->mail_gateway_email, $content);
                    } else {
                        $content = preg_replace('/^MAIL_HOST=.*/m', 'MAIL_HOST=' . $gateway->mail_host, $content);
                        $content = preg_replace('/^MAIL_PORT=.*/m', 'MAIL_PORT=' . $gateway->mail_port, $content);
                        $content = preg_replace('/^MAIL_ENCRYPTION=.*/m', 'MAIL_ENCRYPTION=' . $gateway->mail_encryption, $content);
                        $content = preg_replace('/^MAIL_USERNAME=.*/m', 'MAIL_USERNAME=' . $gateway->mail_username, $content);
                        $content = preg_replace('/^MAIL_PASSWORD=.*/m', 'MAIL_PASSWORD=' . $gateway->mail_password, $content);
                        $content = preg_replace('/^MAIL_FROM_ADDRESS=.*/m', 'MAIL_FROM_ADDRESS=' . $gateway->mail_gateway_email, $content);
                    }
                    file_put_contents($path, $content);
                    Artisan::call('config:clear');
                } else if ($this->selectedGateway == 'sms') {
                    if ($gateway->is_gateway_type == 'brevo') {
                        $path = base_path('.env');
                        $content = file_get_contents($path);

                        $content = preg_replace('/^BREVO_SMS_API_KEY=.*/m', 'BREVO_SMS_API_KEY=' . $gateway->brevo_sms_api_key, $content);


                        file_put_contents($path, $content);
                        Artisan::call('config:clear');
                    } else {
                        $path = base_path('.env');
                        $content = file_get_contents($path);

                        $content = preg_replace('/^TWILIO_SID=.*/m', 'TWILIO_SID=' . $gateway->twilio_account_sid, $content);
                        $content = preg_replace('/^TWILIO_AUTH_TOKEN=.*/m', 'TWILIO_AUTH_TOKEN=' . $gateway->twilio_auth_token, $content);
                        $content = preg_replace('/^TWILIO_PHONE_NUMBER=.*/m', 'TWILIO_PHONE_NUMBER=' . $gateway->twilio_phone_number, $content);

                        file_put_contents($path, $content);
                        Artisan::call('config:clear');
                    }
                } else {
                    $path = base_path('.env');
                    $content = file_get_contents($path);
                    if ($gateway->is_gateway_type == 'twilio') {
                        $content = preg_replace('/^TWILIO_PHONE_NUMBER=.*/m', 'TWILIO_PHONE_NUMBER=' . $gateway->twilio_phone_number, $content);
                        $content = preg_replace('/^TWILIO_SID=.*/m', 'TWILIO_SID=' . $gateway->twilio_account_sid, $content);
                        $content = preg_replace('/^TWILIO_AUTH_TOKEN=.*/m', 'TWILIO_AUTH_TOKEN=' . $gateway->twilio_auth_token, $content);
                    } else {
                        $content = preg_replace('/^WHATSAPP_ACCESS_TOKEN=.*/m', 'WHATSAPP_ACCESS_TOKEN=' . $gateway->whatsapp_access_token, $content);
                        $content = preg_replace('/^WHATSAPP_PHONE_NUMBER_ID=.*/m', 'WHATSAPP_PHONE_NUMBER_ID=' . $gateway->whatsapp_no_id, $content);
                    }

                    file_put_contents($path, $content);
                    Artisan::call('config:clear');
                }
            }
        }


        $this->resetInputFields();



        if ($this->selectedGateway == 'email') {
            $this->items = MessageGateway::where('type', 'email')->latest()->get();
        } else if ($this->selectedGateway == 'sms') {
            $this->items = MessageGateway::where('type', 'sms')->latest()->get();
        } else {
            $this->items = MessageGateway::where('type', 'whatsapp')->latest()->get();
        }


        $this->dispatchBrowserEvent(
            'alert',
            ['type' => 'success', 'message' => 'Default gateway updated successfully!']
        );
    }
}
