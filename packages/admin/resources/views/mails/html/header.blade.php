<tr style="border-bottom:1px dashed #ddd">
    <td class="app-name">
        <a href="{{ $url }}">
            {{ $slot }}
        </a>
    </td>
</tr>
<tr>
    <td style="padding-top: 20px;">
        <img style="float:left; height: 45px;" src="{{ asset('shopper/images/logo.svg') }}" alt="Shopper Logo">
        <p class="app-description">
            {{ $description }}
        </p>
    </td>
</tr>
