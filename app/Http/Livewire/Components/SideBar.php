<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SideBar extends Component
{
    protected $listeners = ['updateTimezone'];
    public $dashActive = false;
    public $activeContacts = false;
    public $messageHubActive = false;
    public $activeSendMessage = false;
    public $activeTemplates = false;
    public $activeHistory = false;
    public $activeGateway = false;
    /* render the page */
    public function render()
    {
        return view('livewire.components.side-bar');
    }
    /* process before render */
    public function mount()
    {
        $path = request()->path();
        $this->dashActive = str_starts_with($path, 'admin/dashboard');
        $this->activeContacts = str_starts_with($path, 'admin/contacts');
        $this->messageHubActive = str_starts_with($path, 'admin/messages') || str_starts_with($path, 'admin/templates');
        $this->activeSendMessage = str_starts_with($path, 'admin/messages/send');
        $this->activeTemplates = str_starts_with($path, 'admin/templates');
        $this->activeHistory = str_starts_with($path, 'admin/messages/history');
        $this->activeGateway = str_starts_with($path, 'admin/messages/gateway');
    }
    //Perform Logout
    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect('/');
    }




    public function updateTimezone($timezone)
    {
        $user = auth()->user();
        if ($user && $user->timezone !== $timezone) {
            $user->timezone = $timezone;
            $user->save();
        }

        $this->skipRender();
    }
}
