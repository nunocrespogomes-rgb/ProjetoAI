@php
    $deleteForm = $deleteForm ?? 'delete-form';
    $new = $new ?? false;
    $show = $show ?? false;
    $edit = $edit ?? false;
    $delete = $delete ?? false;
    $save = $save ?? false;
    $cancel = $cancel ?? false;
@endphp
<div class="mt-6 flex flex-wrap justify-start items-center gap-4">
    @if($new)
        <flux:button variant="primary" href="{{ route($entity . 's.create') }}">{{ __('Novo') }}</flux:button>
    @endif
    @if($show)
        <flux:button variant="filled" class="uppercase" href="{{ route($entity . 's.show', $value) }}">{{ __('Ver') }}</flux:button>
    @endif
    @if($edit)
        <flux:button variant="filled" class="uppercase" href="{{ route($entity . 's.edit', $value) }}">{{ __('Editar') }}</flux:button>
    @endif
    @if($delete)
        <flux:button variant="danger" type="submit" form="{{ $deleteForm }}" class="uppercase">{{ __('Eliminar') }}</flux:button>
    @endif
    <div class="grow"></div>
    @if($save)
        <flux:button variant="primary" type="submit" class="uppercase">{{ __('Guardar') }}</flux:button>
    @endif
    @if($cancel)
        <flux:button variant="filled" class="uppercase" href="{{ url()->full() }}">{{ __('Cancelar') }}</flux:button>
    @endif
</div>