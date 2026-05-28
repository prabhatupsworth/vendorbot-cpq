@props([
    'colspan' => 1,
    'title' => 'No Data Found',
    'subtitle' => 'No records available.',
    'image' => 'https://cdn-icons-png.flaticon.com/512/7486/7486740.png',
])

<tr>
    <td colspan="{{ $colspan }}">

        <div class="text-center py-5">

            <img src="{{ $image }}" width="120" class="mb-3" alt="empty">

            <h5 class="fw-semibold">
                {{ $title }}
            </h5>

            <p class="text-muted mb-0">
                {{ $subtitle }}
            </p>

        </div>

    </td>
</tr>
