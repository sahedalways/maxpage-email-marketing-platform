<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageViewController extends Controller
{
    public function view($id)
    {
        $message = Message::findOrFail($id);
        return view('message.show', compact('message'));
    }
}
