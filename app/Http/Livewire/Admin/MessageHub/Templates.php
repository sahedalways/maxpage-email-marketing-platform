<?php

namespace App\Http\Livewire\Admin\MessageHub;

use App\Models\MessageTemplate;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\Cursor;

class Templates extends Component
{

    public $templates;
    public $template;
    public $items;
    public $selectedItem;
    public $name = '', $content = '', $search = '';

    public $editMode = false;
    public $nextCursor;
    public $currentCursor;
    public $hasMorePages;
    protected $listeners = ['deleteTemp' => 'delete'];


    public $variables = [
        '{{name}}' => 'User First Name',
    ];

    public function mount()
    {

        $this->templates = new EloquentCollection();

        $this->loadItems();
    }


    public function render()
    {
        return view('livewire.admin.message-hub.templates');
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
        $this->templates->push(...$itemList->items());
        if ($this->hasMorePages = $itemList->hasMorePages()) {
            $this->nextCursor = $itemList->nextCursor()->encode();
        }
        $this->currentCursor = $itemList->cursor();
    }
    public function filterData()
    {
        if ($this->search || $this->search != '') {

            $data = MessageTemplate::where('name', 'like', '%' . $this->search . '%')
                ->latest()
                ->cursorPaginate(10, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));


            return $data;
        } else {
            $data = MessageTemplate::latest()
                ->cursorPaginate(10, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));

            return $data;
        }
    }
    public function reloadItems()
    {
        $this->templates = new EloquentCollection();
        $this->nextCursor = null;
        $this->hasMorePages = null;
        if ($this->hasMorePages !== null && !$this->hasMorePages) {
            return;
        }
        $items = $this->filterData();
        $this->templates->push(...$items->items());
        if ($this->hasMorePages = $items->hasMorePages()) {
            $this->nextCursor = $items->nextCursor()->encode();
        }
        $this->currentCursor = $items->cursor();
    }


    public function store()
    {

        $this->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string|max:255',
        ]);

        MessageTemplate::create([
            'name' => $this->name,
            'content' => $this->content,
        ]);

        $this->resetInputFields();

        $this->templates = MessageTemplate::latest()->get();

        $this->emit('closemodal');

        $this->dispatchBrowserEvent(
            'alert',
            ['type' => 'success', 'message' => 'Template has been created!']
        );
        $this->dispatchBrowserEvent('pageReload');
    }



    public function editTemp($id)
    {
        $this->editMode = true;
        $this->template = MessageTemplate::findOrFail($id);

        $this->name = $this->template->name;
        $this->content = $this->template->content;
        $this->dispatchBrowserEvent('open-edit-modal');
    }


    /* update email gateway details */
    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string|max:255',
        ]);


        $this->item->update([
            'name' => $this->name,
            'content' => $this->content,
        ]);

        $this->refresh();
        $this->resetInputFields();
        $this->editMode = false;
        $this->emit('closemodal');

        $this->dispatchBrowserEvent(
            'alert',
            ['type' => 'success', 'message' => 'Template info has been updated!']
        );
        $this->dispatchBrowserEvent('pageReload');
    }



    public function resetInputFields()
    {
        $this->name = null;
        $this->content = null;
        $this->search = '';
        $this->template = null;

        $this->resetErrorBag();
    }


    public function quickView($id)
    {
        $this->selectedItem = MessageTemplate::find($id);
    }



    public function delete($id)
    {
        $item = MessageTemplate::find($id);
        $item->delete();

        $this->resetInputFields();


        $this->templates = MessageTemplate::latest()->get();

        $this->emit('closemodal');
        $this->dispatchBrowserEvent(
            'alert',
            ['type' => 'success', 'message' => 'Template deleted successfully.']
        );
        $this->dispatchBrowserEvent('pageReload');
    }
}
