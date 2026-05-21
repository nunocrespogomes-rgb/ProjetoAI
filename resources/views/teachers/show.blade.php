<x-layouts::main-content :title="$teacher->name"
                        heading="View Teacher"
                        :subheading="$teacher->user->name">
    <div class="flex flex-col space-y-6">
        <div class="max-full">
            <section>
                <div class="mt-6 space-y-4">
                    @include('teachers.partials.fields', ['mode' => 'show'])
                </div>
                @include('partials.form-buttons', ['entity' => 'teacher', 'value' => $teacher, 'new' => true, 'edit' => true, 'delete' => true])
                <h3 class="pt-16 pb-4 text-2xl font-medium text-gray-900 dark:text-gray-100">
                    Disciplines
                </h3>
                <x-disciplines.table :disciplines="$teacher->disciplines"
                                     :showView="true"
                                     :showEdit="false"
                                     :showDelete="false"
                                     :showAddToCart="true"
                                     :showRemoveFromCart="false"
                                     class="pt-4"
                />
            </section>
        </div>
    </div>
    <form id="delete-form" method="POST" action="{{ route('teachers.destroy', ['teacher' => $teacher]) }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</x-layouts.main-content>
