<table class="min-w-full bg-white">
    <tbody>
        @foreach ($users as $index => $user)
            <tr class="even:bg-gray-100">
                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-300">
                    <div class="flex items-center">
                        <div class="ml-4">
                            <div class="text-sm leading-5 font-medium text-gray-900">{{ $user->id }}</div>
                        </div>
                    </div>
                </td>
                @isset($user->photo_filename)
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-300">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full" src="{{ asset('storage/photos/' . $user->photo_filename) }}" alt="{{ $user->name }}">
                            </div>
                        </div>
                    </td>
                @else
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-300">
                    </td>
                @endif
                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-300">
                    <div class="text-sm leading-5 font-medium text-gray-900">{{ $user->name }}</div>
                </td>
                @if($type == 'costumers')
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-300">
                        <form action="{{ route('costumers.toggle-block', ['id' => $user->id]) }}" method="post">
                            @csrf
                            @if ($user->blocked == true)
                                <button class="px-4 py-2 text-black rounded-md hover:bg-blue-300 ring-1 ring-blue-500" type="submit">Unblock</button>
                            @else
                                <button class="px-4 py-2 text-black rounded-md hover:bg-amber-300 ring-1 ring-amber-500" type="submit">Block</button>
                            @endif
                        </form>
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-300">
                        <form action="{{ route('costumers.delete', ['id' => $user->id]) }}" method="post">
                            @method('DELETE')
                            @csrf
                        <button class="px-4 py-2 text-black rounded-md hover:bg-red-300 ring-1 ring-red-500" type="submit">Delete</button>
                        </form>
                    </td>
                @elseif ($type == 'employees')
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-300">
                        <a><button class="px-4 py-2 text-black rounded-md hover:bg-blue-300 ring-1 ring-blue-500" type="submit">Manage</button></a>
                    </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
