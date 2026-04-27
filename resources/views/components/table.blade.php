<div class="table-responsive">
    <table class="table text-nowrap">

        <!-- HEADER -->
        <thead class="{{ $theadClass ?? 'table-primary' }}">
            <tr>
                @foreach ($columns as $column)
                    <th scope="col">
                        {{ is_array($column) ? $column['label'] : $column }}
                    </th>
                @endforeach
            </tr>
        </thead>

        <!-- BODY -->
        <tbody>
            {{ $slot }}
        </tbody>

    </table>
</div>
