<x-layouts::main-content :title="$course->name"
                        :heading="'Course '. $course->name">
    <div class="flex flex-col space-y-6">
        <div class="max-full">
            <section>
                <div class="mt-6 space-y-4">
                    @include('courses.partials.fields', ['mode' => 'show'])
                </div>
                @include('partials.form-buttons', ['entity' => 'course', 'value' => $course, 'new' => true, 'edit' => true, 'delete' => true])
                <h3 class="pt-16 pb-4 text-lg font-medium text-gray-900
                           dark:text-gray-100">
                    Curriculum
                </h3>
                <x-courses.curriculum :disciplines="$course->disciplines"
                                      :showView="true"
                                      class="pt-4"
                />
            </section>
        </div>
    </div>
    <form id="delete-form" method="POST" action="{{ route('courses.destroy', ['course' => $course]) }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</x-layouts::main-content>
