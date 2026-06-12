@php
    $mode = $mode ?? 'edit';
    $readonly = $mode == 'show';
@endphp
<div class="flex flex-wrap space-x-8">
    <div class="grow mt-6 space-y-4">
        <flux:input name="name" label="Nome" :value="old('name', $administrative->name)" :disabled="$readonly" />
        <flux:input name="email" type="email" label="E-mail" :value="old('email', $administrative->email)" :disabled="$readonly" />

        <flux:radio.group name="gender" label="{{ __('Género') }}" :disabled="$readonly">
            <flux:radio value="M" label="{{ __('Masculino') }}" :checked="$administrative->gender == 'M'" />
            <flux:radio value="F" label="{{ __('Feminino') }}" :checked="$administrative->gender == 'F'" />
        </flux:radio.group>
        <flux:error name="gender" />

        <flux:radio.group name="user_type" label="{{ __('Papel') }}" :disabled="$readonly">
            <flux:radio value="A" label="{{ __('Administrador') }}" :checked="old('user_type', $administrative->user_type) === 'A'" />
            <flux:radio value="F" label="{{ __('Funcionário') }}" :checked="old('user_type', $administrative->user_type) === 'F'" />
        </flux:radio.group>
        <flux:error name="user_type" />
    </div>
    <div class="pb-6 pe-12">
        <x-field.image
            name="photo_file"
            label="{{ __('Fotografia de perfil') }}"
            width="md"
            :readonly="$readonly"
            deleteTitle="{{ __('Eliminar fotografia') }}"
            :deleteAllow="($mode == 'edit') && ($administrative->photo_url)"
            deleteForm="form_to_delete_photo"
            :imageUrl="$administrative->photoFullUrl"/>
    </div>
</div>
