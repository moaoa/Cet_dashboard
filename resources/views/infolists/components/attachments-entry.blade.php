<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    @php
    $attachments = json_decode($getState()) || [];
    if(is_array($attachments)) $attachments = [];
    @endphp

    @foreach(json_decode($getState()) ?? [] as $attachment)
    <div>
        <a
            class="attachment-entry-link"
            href="{{$attachment->url}}"
            target="_blank">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-text" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path>
                <path d="M9 9l1 0"></path>
                <path d="M9 13l6 0"></path>
                <path d="M9 17l6 0"></path>
            </svg>
            {{ $attachment->name }}
        </a>
    </div>
    @endforeach
</x-dynamic-component>

<style>
    .attachment-entry-link {
        display: inline-flex;
        align-items: center;
        gap: 0.24rem;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        color: #0d6efd;
        text-decoration: none;
        background-color: transparent;
        border: 1px solid #0d6efd;
        border-radius: 0.5rem;
        padding: 0.5rem;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        cursor: pointer;
        text-align: center;
    }
</style>