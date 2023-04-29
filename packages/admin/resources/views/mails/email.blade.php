@component('shopper::mails.html.message')
    {{-- Greeting --}}
    @if (! empty($greeting))
        <p style="
            font-family: 'Poppins', sans-serif;
            font-size: 18px;
            font-weight: 500;
            text-transform: uppercase;
            font-style: normal;
            font-stretch: normal;
            line-height: 1.11;
            letter-spacing: normal;
            color: #007cc3;"
        >
            {{ $greeting }}
        </p>
    @else
        @if ($level === 'error')
            <p style="font-weight: 500;">@lang('Whoops!')</p>
        @else
            <p style="font-weight: 500;">@lang('Hello!')</p>
        @endif
    @endif

    {{-- Intro Lines --}}
    @foreach ($introLines as $line)
        <p>{{ $line }}</p>
    @endforeach

    {{-- Action Button --}}
    @isset($actionText)
        <?php
        switch ($level) {
            case 'success':
            case 'error':
                $color = $level;
                break;
            default:
                $color = 'primary';
        }
        ?>
        @component('shopper::mails.html.button', ['url' => $actionUrl, 'color' => $color])
            {{ $actionText }}
        @endcomponent
    @endisset

    {{-- Outro Lines --}}
    @foreach ($outroLines as $line)
        <p>{{ $line }}</p>
    @endforeach

    {{-- Salutation --}}
    @if (! empty($salutation))
        <p style="font-weight: 500;font-style: normal;font-stretch: normal;line-height: 2.5;letter-spacing: normal;color: #001737;margin-bottom: 0px;">
            {{ $salutation }}
        </p>
    @else
        <p style="font-weight: normal;font-style: normal;font-stretch: normal;line-height: 1.71;letter-spacing: normal;color: #001737;margin-bottom: 0px;">
            @lang('Regards'),<br>
            <span style="font-weight: 500;">{{ config('app.name') }}</span>
        </p>
    @endif

    {{-- Subcopy --}}
    @isset($actionText)
        @slot('subcopy')
            @lang(
                "If you’re having trouble clicking the \":actionText\" button, copy and paste the URL below\n".
                'into your web browser: <a href=":actionURL" class="link">:actionURL</a>',
                [
                    'actionText' => $actionText,
                    'actionURL' => $actionUrl,
                ]
            )
        @endslot
    @endisset
@endcomponent
