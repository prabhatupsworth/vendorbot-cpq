<div class="table-responsive">
    <table {{ $attributes->merge([
        'class' => 'table text-nowrap table-striped table-hover',
    ]) }}>
        {{ $slot }}
    </table>
</div>
