<div class="max-w-[85rem] mx-auto hidden md:block" wire.poll.keep-alive>
    <div class="stats stats-vertical shadow">
        <div class="stat">
            <div class="stat-figure text-error">
                <x-heroicon-o-clipboard-document-list class="inline-block w-8 h-8 stroke-current" />

            </div>
            <div class="stat-title">Carton Number</div>
            <div class="stat-value text-md">{{$carton->carton_number}}</div>
            <div class="stat-desc text-error">Box Code : {{$carton->box_code}}</div>
        </div>
        @if ($carton->type === 'RATIO')
        <div class="stat">
            <div class="stat-figure text-secondary">
                <x-heroicon-o-document-duplicate class="inline-block w-8 h-8 stroke-current" />
            </div>
            <div class="stat-title font-bold">Garment Tags</div>
            <div class="stat-value text-secondary">{{$count}}</div>
            <div class="stat-desc">Validated</div>
        </div>
        @endif
        <div class="stat">
            <div class="stat-figure text-secondary">
                <x-heroicon-o-document-duplicate class="inline-block w-8 h-8 stroke-current" />
            </div>
            <div class="stat-title font-bold">Polybags Tags</div>
            <div class="stat-value text-secondary">{{$count}}</div>
            <div class="stat-desc">Validated</div>
        </div>
        <div class="stat">
            <div class="stat-figure text-success">
                <x-heroicon-o-swatch class="inline-block w-8 h-8 stroke-current" />
            </div>
            <div class="stat-title text-xl font-bold">Purchase Order</div>
            <div class="stat-value text-success text-sm">{{$carton->packingList->po}}</div>
            <div class="stat-desc">Buyer {{$carton->packingList->buyer->name}}</div>
        </div>
        <div class="stat">
            <div class="stat-figure text-info">
                <x-heroicon-o-inbox-stack class="inline-block w-8 h-8 stroke-current" />
            </div>
            <div class="stat-title text-xl font-bold">Master Order</div>
            <div class="stat-value text-info text-sm">{{$carton->packingList->contract_no}}</div>
            <div class="stat-desc">Type {{$type}}</div>
        </div>


    </div>
