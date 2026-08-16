@if ($criteria === 'email')
    <div class="mb-4">
        <label for="subject" class="form-label">Subject <span
                class="text-danger">*</span> <i
                class="fas fa-question-circle text-primary"
                data-bs-toggle="tooltip" data-bs-placement="top"
                title="Provide a subject for the mail, so it does not go into spam."></i></label>
        <input type="text" id="subject" class="form-control"
            placeholder="Enter subject" wire:model.defer="subject">
        @error('subject')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
@endif

<div class="row">
    <div class="col-md-3 mb-4">
        <label for="template" class="form-label">Select Template</label>
        <select id="template" class="form-select"
            wire:model="selectedTemplate"
            wire:change="loadTemplateContent">
            <option value="" hidden>-- Select Template --</option>
            @foreach ($templates as $template)
                <option value="{{ $template->id }}">{{ $template->name }}
                </option>
            @endforeach
        </select>
    </div>

    @if (!$isSelectTemp)
        <div class="col-md-9">
            @if ($criteria === 'email')
                <div class="mb-4 custom-textarea" wire:ignore>
                    <label for="content" class="form-label">Message <span
                            class="text-danger">*</span></label>
                    <textarea id="editor" rows="10" class="form-control">{{ $content }}</textarea>
                </div>
                <input type="hidden" wire:model.defer="content"
                    id="livewire-content">
            @else
                <div class="mb-4 custom-textarea">
                    <label for="content" class="form-label">Message <span
                            class="text-danger">*</span></label>
                    <textarea id="content" wire:model="content" class="form-control"
                        placeholder="Type your message here...">{{ $content }}</textarea>
                </div>
            @endif
        </div>
    @endif
</div>

<button type="submit" wire:click.prevent="{{ $sendMethod }}"
    class="btn btn-primary" wire:loading.attr="disabled"
    wire:target="{{ $sendMethod }}">
    <span wire:loading.remove wire:target="{{ $sendMethod }}">Send</span>
    <span wire:loading wire:target="{{ $sendMethod }}">Loading...</span>
</button>
