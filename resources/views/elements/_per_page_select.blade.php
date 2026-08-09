<div class="d-flex align-items-center" total-items-count="{{ number_with_delimiter($items->toArray()["total"]) }}">
    <div class="num_per_page mr-auto my-0">
        <select class="select" name="per_page">
            @php
                $perPages = [2,8,10,12,15,25,50,100];

                if (isset($custom_per_pages)) {
                    $perPages = $custom_per_pages;
                }
            @endphp
            @foreach ($perPages as $num)
                <option{{ $items->toArray()["per_page"] == $num ? " selected" : "" }} value="{{ $num }}">{{ $num }}</option>
            @endforeach
        </select>
        <label>items per page</label>
        @if (isset($items))
            <label>|
                From <span class="text-teal-600"> {{ $items->toArray()["per_page"]*($items->toArray()["current_page"]-1)+1 }}</span> to <span class="text-teal-600"> {{ ($items->toArray()["per_page"]*$items->toArray()["current_page"] > $items->toArray()["total"] ? $items->toArray()["total"] : $items->toArray()["per_page"]*$items->toArray()["current_page"]) }}</span>. Total <span class="text-teal-600"> {{ $items->toArray()["total"] }}</span> records
                <input type="hidden" name="total_items_count" value="{{ $items->toArray()["total"] }}" />
        @endif
    </div>
    <div class="d-flex align-items-end" style="margin-left: auto;">@include('helpers._pagination', ['paginator' => $items])</div>
</div>
