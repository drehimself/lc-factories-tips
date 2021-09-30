<x-guest-layout>
   <div class="mx-auto container px-12">
       <div>
           <a href="{{ route('post.index') }}" class="text-blue-600">Back to blog</a>
       </div>
       <h2 class="font-semibold text-2xl mt-4">{{ $post->title }}</h2>
       <div class="mt-4">
           {{ $post->body }}
       </div>

        @if ($post->user->id === auth()->id() || auth()->user()?->isAdmin())
            <div class="mt-8 flex space-x-4">
                <a href="{{ route('post.edit', $post) }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded">Edit Post</a>

                <form action="{{ route('post.destroy', $post) }}" method="POST">
                    @csrf
                    @method('delete')
                    <button class="inline-block bg-red-600 text-white px-4 py-2 rounded">Delete Post</button>
                </form>
            </div>
        @endif

   </div>
</x-guest-layout>
