<div>
    <div class="breadcrumbs text-sm mt-4">
        <ul>
            <li><a href="{{ route('welcome') }}">Dashboard</a></li>
            <li><a href="{{ route('bagian.index') }}">Bagian</a></li>
            <li><a class="text-black-400 font-semibold">Add data Bagian</a></li>
        </ul>
    </div>
    <a href="{{ route('bagian.index') }}" class="btn btn-md bg-white text-black mt-2">
        <i class="bx bx-arrow-back text-xl"></i>
    </a>
    <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        Add Bagian
    </h2>

    <form wire:submit.prevent="store">
        @csrf
        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">

            <label class="block text-sm">
                <span class="text-black-700 dark:text-black-400">Name</span>
                <input
                    class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input text-black"
                    placeholder="Insert bagian name" wire:model="name" />
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </label>
        </div>
        <button type="reset" class="btn btn-md btn-warning text-black hover:text-white">Reset</button>
        <button type="submit" class="btn btn-md btn-primary text-black hover:text-white">Save</button>
    </form>
</div>
