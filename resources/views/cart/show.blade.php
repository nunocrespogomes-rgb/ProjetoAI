<x-layouts::main-content title="Cart"
                        heading="Shopping Cart"
                        subheading="Disciplines to register for a student">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl ">
        <div class="flex justify-start ">
            @if(!$cart || $cart->isEmpty())
                {{-- <div class="flex items-center justify-center w-full h-full"> --}}
                <div class="my-4 p-6 ">
                    <h2 class="text-2xl font-bold text-gray-700 dark:text-gray-300">Your cart is empty</h2>
                </div>
            @else
                <div class="my-4 p-6">
                    <h2 class="mb-4 text-2xl font-bold text-gray-700 dark:text-gray-300">Disciplines in your cart:</h2>
                    <div class="my-4 font-base text-sm text-gray-700 dark:text-gray-300">
                        <x-disciplines.table :disciplines="$cart"
                            :showCourse="true"
                            :showView="false"
                            :showEdit="false"
                            :showDelete="false"
                            :showAddToCart="false"
                            :showRemoveFromCart="true"
                            />
                    </div>
                    <div class="mt-12">
                        <div>
                            <h3 class="mb-4 text-xl">Shopping Cart Confirmation </h3>
                        </div>
                        <div class="flex justify-between items-end space-x-4">
                            <form action="{{ route('cart.confirm') }}" method="post" class="flex items-end space-x-4">
                                @csrf
                                <flux:input name="student_number" label="Student Number" value="{{ old('student_number') }}"/>
                                <flux:button variant="primary" type="submit">Confirm</flux:button>
                            </form>
                            <form action="{{ route('cart.destroy') }}" method="post" class="flex items-end">
                                @csrf
                                @method('DELETE')
                                <flux:button variant="danger" type="submit">Clear Cart</flux:button>
                            </form>
                        </div>
                    </div>
                </div>
            @endempty
        </div>
    </div>
</x-layouts::main-content>
