<div class="flex justify-center items-center mt-8">
    <a class="group rounded-xl overflow-hidden" href="#">
        <div class="sm:flex">
            <div class="flex-shrink-0 relative rounded-xl overflow-hidden w-full sm:w-32 h-44">
                <img src="{{ asset('storage/'.auth()->user()->company->logo) }}" class="group-hover:scale-105 transition-transform duration-500 ease-in-out absolute top-0 left-0 object-cover rounded-xl" alt="Logo">
            </div>
            <div class="grow mt-4 sm:mt-0 sm:ml-6 px-4 sm:px-0">
                <h3 class="text-2xl font-bold text-gray-800 group-hover:text-gray-600 dark:text-gray-300 dark:group-hover:text-white">
                    Kanban Dashboard
                </h3>
                <p class="text-xl mt-3 text-gray-600 dark:text-gray-400">
                    {{auth()->user()->company->name}}
                </p>

            </div>
        </div>
    </a>
</div>
