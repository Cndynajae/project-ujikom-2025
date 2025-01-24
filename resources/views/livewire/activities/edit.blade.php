<div>
    <div class="breadcrumbs text-sm mt-4">
        <ul>
            <li><a href="{{ route('welcome') }}">Dashboard</a></li>
            <li><a href="{{ route('activities.index') }}">Activities</a></li>
            <li><a class="text-gray-400 font-semibold">Edit data Activities</a></li>
        </ul>
    </div>
    <a href="{{ route('activities.index') }}" class="btn btn-md bg-white text-black mt-2">
        <i class="bx bx-arrow-back text-xl"></i>
    </a>
    <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        Edit Activities
    </h2>
    <div class="p-6 bg-white shadow rounded-lg dark:bg-gray-800">
        <form wire:submit.prevent="update">
            <input type="name" hidden wire:model="aktivitasId">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Name</label>
                <input type="text" id="name" wire:model="name"
                    class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input text-black"
                    placeholder="Enter type name">
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Description</label>
                <input type="text" id="description" wire:model="description"
                    class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input text-black"
                    placeholder="Description">
                @error('description')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>



            <div class="flex">
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2">Save</button>
                <button type="reset" class="btn btn-md btn-warning text-black hover:text-white mr-2 dark:text-white">Reset</button>
            </div>
        </form>

        @if (session()->has('message'))
            <div class="mt-4 p-4 bg-green-100 text-green-800 rounded-lg">
                {{ session('message') }}
            </div>
        @endif
    </div>
</div>
