@props(['config' => null])

<div class="row">
    @foreach ($config as $field)

        <div class="col-md-{{ $field['col'] ?? 12 }}">

            @if ($field['type'] === 'checkbox')

                <x-form.checkbox
                    :label="$field['label']"
                    :name="$field['name']"
                    :checked="$field['value'] ?? false"
                />

            @elseif ($field['type'] === 'select')

                <x-form.select
                    :label="$field['label']"
                    :name="$field['name']"
                    :options="$field['options'] ?? []"
                    :required="$field['required'] ?? false"
                    :placeholder="$field['placeholder'] ?? ''"
                />

            @else

                <x-form.input
                    :label="$field['label']"
                    :name="$field['name']"
                    :required="$field['required'] ?? false"
                    :placeholder="$field['placeholder'] ?? ''"
                />

            @endif

        </div>
    @endforeach
</div>
