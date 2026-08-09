<?php

namespace App\Http\Livewire\Admin\MessageHub;

use App\Models\Message;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\Cursor;

class MessageHistory extends Component
{
    public $messages;

    public $search = '';
    public $startDate;
    public $endDate;
    public $status;
    public $nextCursor;
    public $selectedMessage;
    protected $currentCursor;
    public $hasMorePages;

    public function mount()
    {
        $this->messages = new EloquentCollection();

        $this->loadItems();
    }

    public function render()
    {
        return view('livewire.admin.message-hub.message-history');
    }

    public function refresh()
    {
        if ($this->search == '') {
            $this->messages = $this->messages->fresh();
        }
    }

    public function loadItems()
    {
        if ($this->hasMorePages !== null && !$this->hasMorePages) {
            return;
        }

        $itemList = $this->filterdata();

        $this->messages->push(...$itemList->items());

        if ($this->hasMorePages = $itemList->hasMorePages()) {
            $this->nextCursor = $itemList->nextCursor()->encode();
        }

        $this->currentCursor = $itemList->cursor();
    }

    public function filterdata()
    {
        $query = Message::with('messageHistories')->latest();


        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('receiver_email', 'like', '%' . $this->search . '%')
                    ->orWhere('receiver_phone_no', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->startDate)) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if (!empty($this->endDate)) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        if (!empty($this->status)) {
            $query->whereHas('messageHistories', function ($q) {
                $q->where('status', $this->status);
            });
        }

        return $query->cursorPaginate(10, ['*'], 'cursor', $this->nextCursor ? Cursor::fromEncoded($this->nextCursor) : null);
    }

    public function reloadItems()
    {
        $this->messages = new EloquentCollection();
        $this->nextCursor = null;
        $this->hasMorePages = null;

        $this->loadItems();
    }

    public function filterResults()
    {
        $this->reloadItems();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->startDate = null;
        $this->endDate = null;
        $this->status = null;

        $this->reloadItems();
    }

    public function quickView($id)
    {
        $this->selectedMessage = Message::with('messageHistories')->find($id);
    }

    public function delete($id)
    {
        $item = Message::find($id);
        $item->delete();

        $this->reloadItems();

        $this->emit('closemodal');
        $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => 'Message deleted successfully.']);
    }
}
