@props(['administratives', 'showView' => false, 'showEdit' => false, 'showDelete' => false])

<div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
        <tr class="bg-zinc-50 dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400 uppercase text-xs font-semibold border-b border-zinc-200 dark:border-zinc-700">
            <th class="px-6 py-4">{{ __('Nome') }}</th>
            <th class="px-6 py-4 hidden md:table-cell">{{ __('Email') }}</th>
            <th class="px-6 py-4 text-center">{{ __('Papel') }}</th>
            <th class="px-6 py-4 text-center">{{ __('Estado') }}</th>
            <th class="px-6 py-4 text-center">{{ __('Ações') }}</th>
        </tr>
        </thead>
        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
        @forelse ($administratives as $administrative)
            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition">
                <td class="px-6 py-4 font-medium text-zinc-900 dark:text-white">
                    {{ $administrative->name }}
                </td>

                <td class="px-6 py-4 text-zinc-500 dark:text-zinc-400 hidden md:table-cell">
                    {{ $administrative->email }}
                </td>

                <td class="px-6 py-4 text-center">
                    @if($administrative->isAdmin())
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400 border border-purple-200 dark:border-purple-800">
            {{ __('Administrador') }}
        </span>
                    @elseif($administrative->isEmployee())
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800">
            {{ __('Funcionário') }}
        </span>
                    @endif
                </td>

                <td class="px-6 py-4 text-center">
                    @if($administrative->blocked)
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">
                                {{ __('Bloqueado') }}
                            </span>
                    @else
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                {{ __('Ativo') }}
                            </span>
                    @endif
                </td>

                <td class="px-6 py-4">
                    <div class="flex justify-center items-center gap-2">
                        @if($showView)
                            <flux:button
                                href="{{ route('administratives.show', ['administrative' => $administrative]) }}"
                                variant="ghost" size="sm" class="cursor-pointer" title="Visualizar">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-5 w-5 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </flux:button>
                        @endif

                        @if($showEdit)
                            <flux:button
                                href="{{ route('administratives.edit', ['administrative' => $administrative]) }}"
                                variant="ghost" size="sm" class="cursor-pointer" title="Editar">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-5 w-5 text-zinc-400 hover:text-blue-500" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </flux:button>
                        @endif

                        <form method="POST"
                              action="{{ route('administratives.toggle-block', ['administrative' => $administrative]) }}"
                              class="m-0">
                            @csrf
                            @method('PATCH')
                            <flux:button type="submit" variant="ghost" size="sm" class="cursor-pointer"
                                         title="{{ $administrative->blocked ? 'Desbloquear Conta' : 'Bloquear Conta' }}">
                                @if($administrative->blocked)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-5 w-5 text-zinc-400 hover:text-amber-500" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                @endif
                            </flux:button>
                        </form>

                        @if($showDelete)
                            <form method="POST"
                                  action="{{ route('administratives.destroy', ['administrative' => $administrative]) }}"
                                  onsubmit="return confirm('Tem a certeza de que deseja remover este administrador?');"
                                  class="m-0">
                                @csrf
                                @method('DELETE')
                                <flux:button type="submit" variant="ghost" size="sm" class="cursor-pointer"
                                             title="Remover">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-5 w-5 text-zinc-400 hover:text-red-500" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-16v1a3 3 0 003 3h10M9 3h6m2 4H7"/>
                                    </svg>
                                </flux:button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-6 py-10 text-center text-zinc-400 dark:text-zinc-500">
                    {{ __('Nenhum administrador encontrado correspondente ao filtro.') }}
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
