<div class="table-responsive">
    <table {{ $attributes->merge([
        'class' => 'table text-nowrap',
    ]) }}>
        {{ $slot }}
    </table>
</div>
