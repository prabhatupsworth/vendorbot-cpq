@props([
    'name' => 'image',
    'value' => null,
    'id' => uniqid('img_'),
])

<div class="profile-pic-upload" id="{{ $id }}">

    <!-- Preview -->
    <div class="profile-pic preview-container">

        @if ($value)
            <img src="{{ $value }}" class="preview-img">
        @else
            <span><i class="ti ti-photo"></i></span>
        @endif

    </div>

    <div class="upload-content">
        <div class="upload-btn">
            <input type="file" name="{{ $name }}" class="image-input" accept="image/*">
            <span>
                <i class="ti ti-file-broken"></i> Upload File
            </span>
        </div>
        <p>JPG, GIF or PNG. Max size of 800K</p>
    </div>

</div>

