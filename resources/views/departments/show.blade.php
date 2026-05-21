<x-layouts::main-content :title="$department->name"
                        :heading="'Department '. $department->name">
    <div class="flex flex-col space-y-6">
        <div class="max-full">
            <section>
                <div class="mt-6 space-y-4">
                    @include('departments.partials.fields', ['mode' => 'show'])
                </div>
                @include('partials.form-buttons', ['entity' => 'department', 'value' => $department, 'new' => true, 'edit' => true, 'delete' => true])
                <h3 class="pt-16 pb-4 text-2xl font-medium text-gray-900 dark:text-gray-100">
                    Teachers
                </h3>
                <x-teachers.table :teachers="$department->teachers"
                                  :showDepartment="false"
                                  :showView="true"
                                  :showEdit="false"
                                  :showDelete="false"
                                  class="pt-4"
                />
            </section>
        </div>
    </div>

    <form id="delete-form" method="POST" action="{{ route('departments.destroy', ['department' => $department]) }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</x-layouts::main-content>
