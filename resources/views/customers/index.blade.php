<x-layouts::app title="List of Customers">
    <div class="p-6 max-w-7xl mx-auto space-y-6">
        
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">List of Customers</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Manage the registered customers of the store</p>
        </div>

        <div class="p-4 bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700">
            <form method="GET" action="{{ route('customers.index') }}" class="flex items-end gap-4">
                <div class="flex-1">
                    <label for="name" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Filter by Name</label>
                    <input type="text" name="name" id="name" value="{{ $filterByName }}" 
                           class="w-full bg-zinc-50 dark:bg-zinc-800 text-zinc-900 dark:text-white rounded-lg px-3 py-2 border border-zinc-300 dark:border-zinc-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <flux:button type="submit" variant="filled" class="cursor-pointer">
                    Filter
                </flux:button>
                @if($filterByName)
                    <flux:button href="{{ route('customers.index') }}" variant="ghost">Cancel</flux:button>
                @endif
            </form>
        </div>

        @if(session('alert-msg'))
            <div class="p-4 rounded-lg bg-emerald-600 text-white font-semibold">
                {{ session('alert-msg') }}
            </div>
        @endif

        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-zinc-50 dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400 uppercase text-xs font-semibold border-b border-zinc-200 dark:border-zinc-700">
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition">
                            <td class="px-6 py-4 font-medium text-zinc-900 dark:text-white">{{ $customer->name }}</td>
                            <td class="px-6 py-4 text-zinc-500 dark:text-zinc-400">{{ $customer->email }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($customer->blocked)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">
                                        Blocked
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                        Active
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center items-center gap-2">
                                    <form method="POST" action="{{ route('customers.toggle-block', $customer) }}">
                                        @csrf
                                        @method('PATCH')
                                        <flux:button type="submit" variant="ghost" size="sm" class="cursor-pointer" title="{{ $customer->blocked ? 'Unblock Account' : 'Block Account' }}">
                                            @if($customer->blocked)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-zinc-400 hover:text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                                            @endif
                                        </flux:button>
                                    </form>

                                    <form method="POST" action="{{ route('customers.destroy', $customer) }}" onsubmit="return confirm('Are you sure you want to completely delete this customer?');">
                                        @csrf
                                        @method('DELETE')
                                        <flux:button type="submit" variant="ghost" size="sm" class="cursor-pointer" title="Delete Customer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-zinc-400 hover:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-16v1a3 3 0 003 3h10M9 3h6m2 4H7"/></svg>
                                        </flux:button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-zinc-400 dark:text-zinc-500">No customers found matching the filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $customers->links() }}
        </div>
    </div>
</x-layouts::app>  