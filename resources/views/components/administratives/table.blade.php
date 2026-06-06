<div>
    <table class="table-auto border-collapse w-full">
        <thead>
            <tr class="border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
                <th class="px-2 py-2 text-left">{{ __('Nome') }}</th>
                <th class="px-2 py-2 text-left hidden md:table-cell">{{ __('E-mail') }}</th>
                <th class="px-2 py-2 text-center">{{ __('Adm.') }}</th>
                <th class="px-2 py-2 text-center">{{ __('Estado') }}</th>
                @if($showView)
                <th></th>
                @endif
                @if($showEdit)
                <th></th>
                @endif
                <th></th>
                @if($showDelete)
                <th></th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($administratives as $administrative)
            <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                <td class="px-2 py-2 text-left">{{ $administrative->name }}</td>
                <td class="px-2 py-2 text-left hidden md:table-cell">{{ $administrative->email }}</td>

                <td class="px-2 py-2 text-center">{{ $administrative->admin ? __('Sim') : '-' }}</td>

                <td class="px-2 py-2 text-center">
                    @if($administrative->blocked)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                        {{ __('Bloqueado') }}
                    </span>
                    @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                        {{ __('Ativo') }}
                    </span>
                    @endif
                </td>

                @if($showView)
                <td class="ps-2 px-0.5">
                    <a href="{{ route('administratives.show', ['administrative' => $administrative]) }}">
                        <flux:icon.eye class="size-5 hover:text-green-600" />
                    </a>
                </td>
                @endif

                @if($showEdit)
                <td class="px-0.5">
                    <a href="{{ route('administratives.edit', ['administrative' => $administrative]) }}">
                        <flux:icon.pencil-square class="size-5 hover:text-blue-600" />
                    </a>
                </td>
                @endif

                <td class="px-0.5">
                    <form method="POST" action="{{ route('administratives.toggle-block', ['administrative' => $administrative]) }}" class="flex items-center">
                        @csrf
                        @method('PATCH')
                        <button type="submit" title="{{ $administrative->blocked ? __('Desbloquear conta') : __('Bloquear conta') }}">
                            @if($administrative->blocked)
                            <flux:icon.lock-open class="size-5 text-emerald-600 hover:text-emerald-700" />
                            @else
                            <flux:icon.lock-closed class="size-5 text-amber-500 hover:text-amber-600" />
                            @endif
                        </button>
                    </form>
                </td>

                @if($showDelete)
                <td class="px-0.5">
                    <form method="POST" action="{{ route('administratives.destroy', ['administrative' => $administrative]) }}" class="flex items-center" onsubmit="return confirm(`{{ __('Tem a certeza de que pretende eliminar este administrativo?') }}`)">
                        @csrf
                        @method('DELETE')
                        <button type="submit">
                            <flux:icon.trash class="size-5 hover:text-red-600" />
                        </button>
                    </form>
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</div>