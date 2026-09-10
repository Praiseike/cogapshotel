<x-layouts.admin>
    @section('title', 'Booking ' . $booking->payment_reference)
    @section('header', 'Booking Details')

    <div class="max-w-4xl">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Booking Information</h3>
                    <dl class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm text-gray-500">Reference</dt>
                            <dd class="text-sm font-mono font-medium text-gray-900">{{ $booking->payment_reference }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Status</dt>
                            <dd><span class="{{ $booking->getStatusBadgeClass() }}">{{ str_replace('_', ' ', ucfirst($booking->status)) }}</span></dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Room</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $booking->room->name }} ({{ $booking->room->category->name }})</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Guests</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $booking->guests_count }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Check-in</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $booking->check_in->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Check-out</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $booking->check_out->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Nights</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $booking->getNightsCount() }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Total Amount</dt>
                            <dd class="text-lg font-bold text-amber-600">&#8358;{{ number_format($booking->total_amount, 2) }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Guest Information</h3>
                    <dl class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm text-gray-500">Name</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $booking->user->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Email</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $booking->user->email }}</dd>
                        </div>
                    </dl>
                </div>

                @if($booking->payment)
                    <div class="card p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h3>
                        <dl class="grid grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm text-gray-500">Reference</dt>
                                <dd class="text-sm font-mono font-medium text-gray-900">{{ $booking->payment->paystack_reference }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">Status</dt>
                                <dd><span class="{{ $booking->payment->isSuccess() ? 'badge-success' : 'badge-danger' }}">{{ ucfirst($booking->payment->status) }}</span></dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">Paid At</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $booking->payment->paid_at?->format('M d, Y H:i') ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>
                @endif
            </div>

            <div class="space-y-6">
                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Update Status</h3>
                    <form method="POST" action="{{ route('admin.bookings.update', $booking) }}">
                        @csrf @method('PUT')
                        <div class="space-y-4">
                            <select name="status" class="input-field">
                                <option value="pending_payment" {{ $booking->status === 'pending_payment' ? 'selected' : '' }}>Pending Payment</option>
                                <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            <button type="submit" class="btn-primary w-full">Update Status</button>
                        </div>
                    </form>
                </div>

                <a href="{{ route('admin.bookings.index') }}" class="btn-secondary w-full justify-center">Back to Bookings</a>
            </div>
        </div>
    </div>
</x-layouts.admin>
