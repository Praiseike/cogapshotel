<x-layouts.admin>
    @section('title', 'Activity Logs')
    @section('header', 'Activity Logs')

    <div class="mb-6">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search description, actor, action..." class="input-field max-w-xs">
            <select name="action" class="input-field w-auto" onchange="this.form.submit()">
                <option value="">All events</option>
                @foreach($groups as $group)
                    <option value="{{ $group }}" {{ request('action') == $group ? 'selected' : '' }}>{{ $group }}</option>
                @endforeach
            </select>
            <input type="date" name="from" value="{{ request('from') }}" class="input-field w-auto" title="From date">
            <input type="date" name="to" value="{{ request('to') }}" class="input-field w-auto" title="To date">
            <button type="submit" class="btn-secondary">Filter</button>
            @if(request()->anyFilled(['search', 'action', 'from', 'to']))
                <a href="{{ route('admin.activity-logs.index') }}" class="text-sm text-gray-500 hover:underline">Clear</a>
            @endif
        </form>
    </div>

    <div class="card">
        @if($logs->count())
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">When</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Event</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">What happened</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($logs as $log)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap" title="{{ $log->created_at }}">{{ $log->created_at->diffForHumans() }}</td>
                                <td class="px-6 py-4"><span class="text-xs font-mono bg-gray-100 rounded px-2 py-1">{{ $log->action }}</span></td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $log->description ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $log->actorName() }}
                                    @if($log->user?->role === 'admin')
                                        <span class="ml-1 text-[10px] font-bold uppercase tracking-wider text-amber-700 bg-amber-100 rounded px-1.5 py-0.5">admin</span>
                                    @elseif($log->user)
                                        <span class="ml-1 text-[10px] font-bold uppercase tracking-wider text-gray-500 bg-gray-100 rounded px-1.5 py-0.5">guest</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-mono text-gray-400">{{ $log->ip ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4">{{ $logs->links() }}</div>
        @else
            <div class="p-12 text-center text-gray-500"><p>No activity yet — events appear here as they happen.</p></div>
        @endif
    </div>
</x-layouts.admin>
