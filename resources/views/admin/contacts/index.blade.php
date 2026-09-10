<x-layouts.admin>
    @section('title', 'Contacts')
    @section('header', 'Contact Messages')

    <div class="card">
        @if($contacts->count())
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($contacts as $contact)
                            <tr class="hover:bg-gray-50 {{ !$contact->is_read ? 'bg-amber-50/50' : '' }}">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $contact->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $contact->email }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $contact->subject }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $contact->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4">
                                    <span class="{{ $contact->is_read ? 'badge-gray' : 'badge-info' }}">{{ $contact->is_read ? 'Read' : 'New' }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.contacts.show', $contact) }}" class="text-sm text-amber-600 hover:text-amber-700">View</a>
                                        <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}" onsubmit="return confirm('Delete?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:text-red-700">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4">{{ $contacts->links() }}</div>
        @else
            <div class="p-12 text-center text-gray-500"><p>No contact messages yet.</p></div>
        @endif
    </div>
</x-layouts.admin>
