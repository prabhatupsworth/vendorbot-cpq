@props(['config' => null])

<div class="row">
    @foreach ($config as $field)
        <div class="col-md-{{ $field['col'] ?? 12 }}">

            @if ($field['type'] === 'checkbox')

                <x-form.checkbox
                    :middle="$field['middle'] ?? false"
                    :label="$field['label']"
                    :name="$field['name']"
                    :checked="$field['value'] ?? false"
                />

            @elseif ($field['type'] === 'select')
                <x-form.select
                    :id="$field['id'] ?? ''"
                    :label="$field['label']"
                    :name="$field['name']"
                    :options="$field['options'] ?? []"
                    :required="$field['required'] ?? false"
                    :placeholder="$field['placeholder'] ?? ''"
                    :value="$field['value'] ?? null"
                    :multiple="$field['multiple'] ?? false"
                    :disabledOptions="$field['disabledOptions'] ?? []"
                />

            @elseif ($field['type'] === 'file')

            <x-form.file
                :label="$field['label']"
                :name="$field['name']"
                :required="$field['required'] ?? false"
            />

            @else

                <x-form.input
                    :type="$field['type']"
                    :step="$field['step'] ?? null"
                    :label="$field['label']"
                    :name="$field['name']"
                    :value="$field['value'] ?? null"
                    :required="$field['required'] ?? false"
                    :placeholder="$field['placeholder'] ?? ''"
                    :disabled="$field['disabled'] ?? false"
                />

            @endif

        </div>
    @endforeach
</div>
