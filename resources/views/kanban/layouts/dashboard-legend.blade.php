<aside class="items-center grid-flow-col">
    <img src="{{ asset('storage/'.auth()->user()->company->logo) }}" class="w-20 h-20 group-hover:scale-105 transition-transform duration-500 ease-in-out object-cover rounded-md" alt="Logo">
    <div class="text-white">
        <p class="text-4xl font-bold">Kanban Dashboard</p>
        <p class="text-2xl font-bold text-white"> {{auth()->user()->company->name}}</p>
    </div>

</aside>
<aside class="items-center grid-flow-col">
    <x-tabler-checkup-list class="w-10 h-10 text-success" />

    <div class="text-white">
        <p class="text-xl font-bold">Standard Stock Bra</p>
        <p class="text-4xl font-bold text-success">840 pcs</p>
    </div>

</aside>
<aside class="items-center grid-flow-col">
    <x-tabler-checkup-list class="w-10 h-10 text-warning animate-flash animate-infinite" />

    <div class="text-white">
        <p class="text-xl font-bold">Middle Stock Bra</p>
        <p class="text-4xl font-bold text-warning">420 pcs</p>
    </div>

</aside>
<aside class="items-center grid-flow-col">
    <x-tabler-checkup-list class="w-10 h-10 text-success" />

    <div class="text-white">
        <p class="text-xl font-bold">Standard Stock Brief</p>
        <p class="text-4xl font-bold text-success">960 pcs</p>
    </div>

</aside>
<aside class="items-center grid-flow-col">
    <x-tabler-checkup-list class="w-10 h-10 text-warning animate-flash animate-infinite" />

    <div class="text-white">
        <p class="text-xl font-bold">Middle Stock Brief</p>
        <p class="text-4xl font-bold text-warning">480 pcs</p>
    </div>

</aside>
