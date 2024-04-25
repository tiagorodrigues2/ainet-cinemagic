<div class="bg-white rounded-lg shadow-lg hover:shadow-xl mx-4 my-4 w-48" style="cursor: pointer;">
    <img src="{{ asset('storage/posters/'. $movie->poster_filename) }}" alt="{{ $movie->title }}" class="w-48 h-64 object-cover rounded-t-lg">
    <div class="p-4 whitespace-normal">
        <h2 class="text-xl font-bold">{{ $movie->title }}</h2>
        <p class="text-gray-500">{{ $movie->genre }}</p>
    </div>
</div>
