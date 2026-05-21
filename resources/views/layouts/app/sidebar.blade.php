<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-white dark:bg-zinc-800">
<flux:sidebar sticky collapsible="mobile"
              class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
    <flux:sidebar.header>
        <x-app-logo :href="route('home')" wire:navigate/>
        <flux:sidebar.collapse class="lg:hidden"/>
    </flux:sidebar.header>

    @auth
        @if(count(session('cart', [])) > 0)
            <flux:sidebar.nav variant="outline">
                <div class="relative inline-flex items-center mr-4">
                    <div class="-top-0.5 absolute left-6 z-10">
                        <p class="flex p-3 h-3 w-3 items-center justify-center rounded-full bg-red-500 text-xs text-white">
                            {{ count(session('cart', [])) }}
                        </p>
                    </div>
                    <flux:navlist.item icon="shopping-cart" icon:variant="solid" :href="route('cart.show')"
                                       :current="request()->routeIs('cart.show')" wire:navigate><span class="pl-2">Shopping Cart</span>
                    </flux:navlist.item>
                </div>
            </flux:sidebar.nav>
        @endif

        @can('admin')
            <flux:sidebar.nav>
                <flux:sidebar.group heading="Management" class="grid">
                    <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                                       wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="building-library" :href="route('departments.index')"
                                       :current="request()->routeIs('departments.index')" wire:navigate>
                        Departments
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="folder-open" :href="route('courses.index')"
                                       :current="request()->routeIs('courses.index')" wire:navigate>
                        Courses
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>
        @endcan
    @endauth

    <flux:sidebar.nav>
        <flux:sidebar.group heading="Academics" class="grid">
            <flux:sidebar.item icon="academic-cap" :href="route('courses.showcase')"
                               :current="request()->routeIs('courses.showcase')" wire:navigate>
                Courses
            </flux:sidebar.item>
            <flux:sidebar.item icon="document" :href="route('disciplines.index')"
                               :current="request()->routeIs('disciplines.index')" wire:navigate>
                Disciplines
            </flux:sidebar.item>
            <flux:navlist.group heading="Curricula" expandable :expanded="request()->routeIs('courses.curriculum')">
                @foreach(($sharedCourses ?? []) as $course)
                    <flux:sidebar.item :href="route('courses.curriculum', ['course' => $course])"
                                       :current="request()->routeIs('courses.curriculum')
                                       && request()->route('course')?->is($course)" class="font-light font-sm">
                        {{ $course->abbreviation }}
                    </flux:sidebar.item>
                @endforeach
            </flux:navlist.group>
        </flux:sidebar.group>
    </flux:sidebar.nav>

    @auth
        <flux:sidebar.nav>
            <flux:sidebar.group heading="People" class="grid">
                <flux:sidebar.item icon="user" :href="route('teachers.index')"
                                   :current="request()->routeIs('teachers.index')" wire:navigate>
                    Teachers
                </flux:sidebar.item>
                @can('viewAny', \App\Models\Student::class)
                    <flux:sidebar.item icon="users" :href="route('students.index')"
                                       :current="request()->routeIs('students.index')" wire:navigate>
                        Students
                    </flux:sidebar.item>
                @endcan
                <flux:sidebar.item icon="user-circle" :href="route('administratives.index')"
                                   :current="request()->routeIs('administratives.index')" wire:navigate>
                    Administratives
                </flux:sidebar.item>
            </flux:sidebar.group>
        </flux:sidebar.nav>
    @endauth

    <flux:spacer/>

    @auth
        <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name"/>
    @else
        <flux:sidebar.item icon="user" :href="route('login')" :current="request()->routeIs('login')" wire:navigate>
            Login
        </flux:sidebar.item>
    @endauth
</flux:sidebar>

@auth
    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left"/>
        <flux:spacer/>
        <flux:dropdown position="top" align="end">
            <flux:profile
                :initials="auth()->user()->initials()"
                icon-trailing="chevron-down"
                :avatar="auth()->user()->photo_url ? auth()->user()->photo_full_url : null"
            />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <flux:avatar
                                :name="auth()->user()->name"
                                :initials="auth()->user()->initials()"
                                :src="auth()->user()->photo_url ? auth()->user()->photo_full_url : null"
                            />
                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator/>

                <flux:menu.radio.group>
                    <flux:menu.item icon="document" :href="route('disciplines.my')"
                                    :current="request()->routeIs('disciplines.my')" wire:navigate>
                        My Disciplines
                    </flux:menu.item>
                    <flux:menu.item icon="user" :href="route('home')" :current="false" wire:navigate>
                        My Teachers
                    </flux:menu.item>
                    <flux:menu.item icon="users" :href="route('home')" :current="false" wire:navigate>
                        My Students
                    </flux:menu.item>
                </flux:menu.radio.group>


                <flux:menu.separator/>

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                        {{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator/>

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item
                        as="button"
                        type="submit"
                        icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer"
                        data-test="logout-button"
                    >
                        {{ __('Log out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>
@else
    <!-- Mobile Menu Login-->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left"/>
        <flux:spacer/>
        <flux:sidebar.item position="top" align="end" icon="user" :href="route('login')"
                           :current="request()->routeIs('login')" wire:navigate>
            Login
        </flux:sidebar.item>
    </flux:header>
@endauth


{{ $slot }}

@persist('toast')
<flux:toast.group>
    <flux:toast/>
</flux:toast.group>
@endpersist

@fluxScripts
</body>
</html>
