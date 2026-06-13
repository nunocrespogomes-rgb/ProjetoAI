<x-layouts::main-content :title="$administrative->name"
                        heading="View Administrative"
                        :subheading="$administrative->name">
    <div class="flex flex-col space-y-6">
        <div class="max-full">
            <section>
                <div class="mt-6 space-y-4">
                    @include('administratives.partials.fields', ['mode' => 'show'])
                </div>
                <div class="mt-6 flex flex-wrap justify-start items-center gap-4 w-full">
                    <flux:button variant="filled" class="uppercase cursor-pointer" href="{{ route('administratives.index') }}">
                            {{ __('Voltar') }}
                    </flux:button>
                    <div class="grow flex items-center [&>div]:mt-0 [&>div]:w-full">
                        @include('partials.form-buttons', ['entity' => 'administrative', 'value' => $administrative, 'new' => false, 'edit' => true, 'delete' => true])
                    </div>
                </div>
            </section>
        </div>
    </div>
    <form id="delete-form" method="POST" action="{{ route('administratives.destroy', ['administrative' => $administrative]) }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</x-layouts::main-content>
