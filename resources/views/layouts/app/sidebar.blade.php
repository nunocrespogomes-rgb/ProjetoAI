<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky collapsible="mobile"
        class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.header>
            <x-app-logo :href="route('home')" wire:navigate />
            <flux:sidebar.collapse class="lg:hidden" />
        </flux:sidebar.header>

        @if(count(session('cart', [])) > 0)
        <flux:sidebar.nav variant="outline">
            <div class="relative inline-flex items-center mr-4 w-full">
                <div class="-top-0.5 absolute left-6 z-10">
                    <p class="flex p-3 h-3 w-3 items-center justify-center rounded-full bg-red-500 text-xs text-white">
                        {{ count(session('cart', [])) }}
                    </p>
                </div>
                <flux:navlist.item icon="shopping-cart" icon:variant="solid" :href="route('cart.index')"
                    :current="request()->routeIs('cart.index')" wire:navigate>
                    <span class="pl-2">Carrinho</span>
                </flux:navlist.item>
            </div>
        </flux:sidebar.nav>
        @endif

        <flux:sidebar.nav>
            <flux:sidebar.group heading="Loja" class="grid">
                <flux:sidebar.item icon="shopping-bag" :href="route('catalog.index')"
                    :current="request()->routeIs('catalog.index')" wire:navigate>
                    Catálogo
                </flux:sidebar.item>
            </flux:sidebar.group>
        </flux:sidebar.nav>

        @auth
        @if(auth()->user()->isCustomer())
        <flux:sidebar.nav>
            <flux:sidebar.group heading="Área do Cliente" class="grid">
                <flux:sidebar.item icon="document-text"
                    :href="route('orders.index')"
                    :current="request()->routeIs('orders.*')"
                    wire:navigate>
                    Minhas Encomendas
                </flux:sidebar.item>

                <flux:sidebar.item icon="photo"
                    :href="route('my_images.index')"
                    :current="request()->routeIs('my_images.*')"
                    wire:navigate>
                    Imagens Pessoais
                </flux:sidebar.item>
            </flux:sidebar.group>
        </flux:sidebar.nav>
        @endif

        @if(auth()->user()->isEmployee() || auth()->user()->isAdmin())
        <flux:sidebar.nav>
            <flux:sidebar.group heading="Operações" class="grid">
                <flux:sidebar.item icon="clock" :href="route('orders.index')" :current="request()->routeIs('orders.index')" wire:navigate>
                    Gestão de Encomendas
                </flux:sidebar.item>
            </flux:sidebar.group>
        </flux:sidebar.nav>
        @endif

        @if(auth()->user()->isAdmin())
        <flux:sidebar.nav>
            <flux:sidebar.group heading="Estatísticas" class="grid">
                <flux:sidebar.item icon="home" :href="route('admin.dashboard')" :current="request()->routeIs('admin.dashboard')" wire:navigate>
                    Dashboard
                </flux:sidebar.item>
            </flux:sidebar.group>
        </flux:sidebar.nav>
        @endif

        @if(auth()->user()->isAdmin())
        <flux:sidebar.nav>
            <flux:sidebar.group heading="Administração" class="grid">
                <flux:sidebar.item icon="users" :href="route('customers.index')" :current="request()->routeIs('customers.index')" wire:navigate>
                    Clientes
                </flux:sidebar.item>
                <flux:sidebar.item icon="user-circle" :href="route('administratives.index')" :current="request()->routeIs('administratives.index')" wire:navigate>
                    Funcionários / Admins
                </flux:sidebar.item>
                <flux:sidebar.item
                    icon="tag"
                    :href="route('admin.categories.index')"
                    :current="request()->routeIs('admin.categories.*')"
                    wire:navigate>
                    Categorias
                </flux:sidebar.item>

                <flux:sidebar.item
                    icon="paint-brush"
                    :href="route('admin.colors.index')"
                    :current="request()->routeIs('admin.colors.*')"
                    wire:navigate>
                    Cores
                </flux:sidebar.item>

                <flux:sidebar.item
                    icon="currency-euro"
                    :href="route('admin.prices.edit')"
                    :current="request()->routeIs('admin.prices.*')"
                    wire:navigate>
                    Preços
                </flux:sidebar.item>
            </flux:sidebar.group>
        </flux:sidebar.nav>
        @endif
        @endauth

        <flux:spacer />

        @auth
        <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        @else
        <flux:sidebar.item icon="user" :href="route('login')" :current="request()->routeIs('login')" wire:navigate>
            Entrar (Login)
        </flux:sidebar.item>
        @endauth
    </flux:sidebar>

    @auth
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
        <flux:spacer />
        <flux:dropdown position="top" align="end">
            <flux:profile
                :initials="auth()->user()->initials()"
                icon-trailing="chevron-down"
                :avatar="auth()->user()->photo_url ? auth()->user()->photo_full_url : null" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <flux:avatar
                                :name="auth()->user()->name"
                                :initials="auth()->user()->initials()"
                                :src="auth()->user()->photo_url ? auth()->user()->photo_full_url : null" />
                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    @if(auth()->user()->isCustomer())
                    <flux:menu.item icon="document-text" :href="route('orders.index')" wire:navigate>
                        Minhas Encomendas
                    </flux:menu.item>
                    @else
                    <flux:menu.item icon="clock" :href="route('catalog.index')" wire:navigate>
                        Encomendas Pendentes
                    </flux:menu.item>
                    @endif
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                        {{ __('Configurações') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item
                        as="button"
                        type="submit"
                        icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer">
                        {{ __('Sair') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>
    @else
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
        <flux:spacer />
        <flux:sidebar.item position="top" align="end" icon="user" :href="route('login')"
            :current="request()->routeIs('login')" wire:navigate>
            Entrar
        </flux:sidebar.item>
    </flux:header>
    @endauth

    {{ $slot }}

    @persist('toast')
    <flux:toast.group>
        <flux:toast />
    </flux:toast.group>
    @endpersist

    @fluxScripts
</body>

</html>
