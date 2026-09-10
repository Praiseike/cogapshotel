<x-layouts.admin>
    @section('title', 'Contact: ' . $contact->subject)
    @section('header', 'Contact Message')

    <div class="max-w-2xl">
        <div class="card p-6 space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">{{ $contact->subject }}</h3>
                <span class="{{ $contact->is_read ? 'badge-gray' : 'badge-info' }}">{{ $contact->is_read ? 'Read' : 'New' }}</span>
            </div>

            <dl class="grid grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm text-gray-500">Name</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $contact->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Email</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $contact->email }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Phone</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $contact->phone ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Date</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $contact->created_at->format('M d, Y H:i') }}</dd>
                </div>
            </dl>

            <div class="border-t pt-4">
                <p class="text-sm text-gray-500 mb-2">Message</p>
                <p class="text-gray-900 whitespace-pre-wrap">{{ $contact->message }}</p>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t">
                <a href="mailto:{{ $contact->email }}?subject=Re: {{ $contact->subject }}" class="btn-primary">Reply via Email</a>
                <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}" onsubmit="return confirm('Delete?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-danger">Delete</button>
                </form>
                <a href="{{ route('admin.contacts.index') }}" class="btn-secondary">Back</a>
            </div>
        </div>
    </div>
</x-layouts.admin>
