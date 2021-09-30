<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div>
                        <a href="{{ route('post.index') }}" class="text-blue-600">Back to blog</a>

                        <h2 class="text-xl mt-4">Create Post</h2>

                        <form action="{{ route('post.store') }}" method="POST" class="mt-4">
                            @csrf
                            <div>
                                <label for="title" class="block">Title</label>
                                <input type="text" id="title" name="title">
                                @error('title')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <label for="body" class="block">Body</label>
                                <textarea name="body" id="body" cols="30" rows="6"></textarea>
                                @error('body')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mt-8">
                                <button type="submit" class="inline-block bg-blue-600 text-white px-4 py-2 rounded">Create Post</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
