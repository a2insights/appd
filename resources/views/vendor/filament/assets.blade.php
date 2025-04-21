@if (isset($data))
    <script>
        window.filamentData = @js($data)
    </script>
@endif
@vite('resources/js/app.js')
@foreach ($assets as $asset)
    @if (!$asset->isLoadedOnRequest())
        {{ $asset->getHtml() }}
    @endif
@endforeach

<style>
    :root {
        @foreach ($cssVariables ?? [] as $cssVariableName => $cssVariableValue)
            --{{ $cssVariableName }}: {{ $cssVariableValue }};
        @endforeach
    }
</style>
