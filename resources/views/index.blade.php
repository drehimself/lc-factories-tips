<x-guest-layout>
   <div class="mx-auto container px-12">
       @if (session('success_message'))
           <div class="bg-green-200 rounded px-2 py-3 mb-4">
               {{ session('success_message') }}
           </div>
       @endif

       <ul>
            @foreach ($posts as $post)
                <li class="list-disc">
                    <a href="{{ route('post.show', $post) }}" class="text-blue-600">{{ $post->title }}</a>
                    @if ($post->pinned)(pinned)@endif
                </li>
            @endforeach
       </ul>

       <div class="mt-8">
            <a href="{{ route('post.create') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded">Create Post</a>
        </div>
   </div>
</x-guest-layout>
