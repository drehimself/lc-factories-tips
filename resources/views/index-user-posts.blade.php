<x-guest-layout>
   <div class="mx-auto container px-12">
       @if (session('success_message'))
           <div class="bg-green-200 rounded px-2 py-3 mb-4">
               {{ session('success_message') }}
           </div>
       @endif

       <h2 class="text-xl">Posts by {{ $user->name }}</h2>

       <ul>
            @foreach ($posts as $post)
                <li class="list-disc">
                    <a href="{{ route('post.show', $post) }}" class="text-blue-600">{{ $post->title }}</a>
                </li>
            @endforeach
       </ul>

       Post Count: {{ $posts->count() }}
   </div>
</x-guest-layout>
