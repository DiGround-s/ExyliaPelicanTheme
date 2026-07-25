{{--
    Exylia override of filament.components.server-console.
    Resolved before the panel's own view (see ExyliaThemePlugin::registerServerConsoleOverrides()).
    The upstream file lives at resources/views/filament/components/server-console.blade.php
    and is left untouched on disk.

    All websocket/event wiring below is copied verbatim from the upstream
    component — only the xterm.js theme object and the input row markup were
    changed. Do not rename any Livewire/window events here, the backing
    Filament\Server\Widgets\ServerConsole class dispatches/listens for the
    exact names used below.
--}}
<x-filament::widget>
    @assets
    @php
        $userFont = (string) user()?->getCustomization(\App\Enums\CustomizationKey::ConsoleFont);
        $userFontSize = (int) user()?->getCustomization(\App\Enums\CustomizationKey::ConsoleFontSize);
        $userRows = (int) user()?->getCustomization(\App\Enums\CustomizationKey::ConsoleRows);

        $terminalPrelude = str(config('app.name'))->slug()->lower()->toString();
    @endphp
    @if($userFont !== "monospace")
        <link rel="preload" href="{{ asset("storage/fonts/{$userFont}.ttf") }}" as="font" crossorigin>
        <style>
            @font-face {
                font-family: '{{ $userFont }}';
                src: url('{{ asset("storage/fonts/{$userFont}.ttf") }}');
            }
        </style>
    @endif
    @vite(['resources/js/console.js', 'resources/css/console.css'])
    @endassets

    <div class="exylia-terminal-frame">
        <div class="exylia-terminal-frame__chrome">
            <span class="exylia-terminal-frame__dot" data-tone="danger"></span>
            <span class="exylia-terminal-frame__dot" data-tone="warning"></span>
            <span class="exylia-terminal-frame__dot" data-tone="success"></span>
            <span class="exylia-terminal-frame__title">console</span>
        </div>

        <div id="terminal" wire:ignore class="exylia-terminal"></div>

        @if ($this->authorizeSendCommand())
            <div class="exylia-terminal-input">
                <x-filament::icon icon="tabler-chevrons-right" class="exylia-terminal-input__caret" />
                <input
                    id="send-command"
                    class="exylia-terminal-input__field"
                    type="text"
                    :readonly="{{ $this->canSendCommand() ? 'false' : 'true' }}"
                    title="{{ $this->canSendCommand() ? '' : trans('server/console.command_blocked_title') }}"
                    placeholder="{{ $this->canSendCommand() ? trans('server/console.command') : trans('server/console.command_blocked') }}"
                    wire:model="input"
                    wire:keydown.enter="enter"
                    wire:keydown.up.prevent="up"
                    wire:keydown.down="down"
                >
            </div>
        @endif
    </div>

    @script
    <script>
        let theme = {
            background: 'rgba(11, 7, 19, 0)',
            cursor: 'transparent',
            black: '#160e26',
            red: '#a33b53',
            green: '#8fffc1',
            yellow: '#ffc58f',
            blue: '#59a4ff',
            magenta: '#8a51c4',
            cyan: '#7db7ff',
            white: '#e7cfff',
            brightBlack: 'rgba(231, 207, 255, 0.25)',
            brightRed: '#b36476',
            brightGreen: '#a1ffc3',
            brightYellow: '#ffd2a8',
            brightBlue: '#7db7ff',
            brightMagenta: '#aa76de',
            brightCyan: '#b48fd9',
            brightWhite: '#ffffff',
            selection: '#8a51c4'
        };

        let options = {
            fontSize: {{ $userFontSize }},
            fontFamily: '{{ $userFont }}, monospace',
            lineHeight: 1.35,
            disableStdin: true,
            cursorStyle: 'bar',
            cursorInactiveStyle: 'bar',
            allowTransparency: true,
            rows: {{ $userRows }},
            theme: theme
        };

        const { Terminal, FitAddon, WebLinksAddon, SearchAddon, SearchBarAddon, WebglAddon } = window.Xterm;

        const terminal = new Terminal(options);
        const fitAddon = new FitAddon();
        const webLinksAddon = new WebLinksAddon();
        const searchAddon = new SearchAddon();
        const searchAddonBar = new SearchBarAddon({ searchAddon });
        const webglAddon = new WebglAddon();
        terminal.loadAddon(fitAddon);
        terminal.loadAddon(webLinksAddon);
        terminal.loadAddon(searchAddon);
        terminal.loadAddon(searchAddonBar);
        terminal.loadAddon(webglAddon);

        terminal.open(document.getElementById('terminal'));

        fitAddon.fit(); // Fixes SPA issues.

        window.addEventListener('load', () => {
            fitAddon.fit();
        });

        window.addEventListener('resize', () => {
            fitAddon.fit();
        });

        terminal.attachCustomKeyEventHandler((event) => {
            if ((event.ctrlKey || event.metaKey) && event.key === 'c') {
                navigator.clipboard.writeText(terminal.getSelection());
                return false;
            } else if ((event.ctrlKey || event.metaKey) && event.key === 'f') {
                event.preventDefault();
                searchAddonBar.show();
                return false;
            } else if (event.key === 'Escape') {
                searchAddonBar.hidden();
            }
            return true;
        });

        const TERMINAL_PRELUDE = '\u001b[1m\u001b[35m{{ $terminalPrelude }}@' + '{{ \Filament\Facades\Filament::getTenant()->name }}' + ' ~ \u001b[0m';

        const handleConsoleOutput = (line, prelude = false) =>
            terminal.writeln((prelude ? TERMINAL_PRELUDE : '') + line.replace(/(?:\r\n|\r|\n)$/im, '') + '\u001b[0m');

        const handleTransferStatus = (status) =>
            status === 'failure' && terminal.writeln(TERMINAL_PRELUDE + 'Transfer has failed.\u001b[0m');

        const handleDaemonErrorOutput = (line) =>
            terminal.writeln(TERMINAL_PRELUDE + '\u001b[1m\u001b[41m' + line.replace(/(?:\r\n|\r|\n)$/im, '') + '\u001b[0m');

        const handlePowerChangeEvent = (state) =>
            terminal.writeln(TERMINAL_PRELUDE + 'Server marked as ' + state + '...\u001b[0m');

        const socket = new WebSocket("{{ $this->getSocket() }}");

        socket.onerror = (event) => {
            $wire.dispatchSelf('websocket-error');
        };

        socket.onmessage = function(websocketMessageEvent) {
            let { event, args } = JSON.parse(websocketMessageEvent.data);

            switch (event) {
                case 'console output':
                case 'install output':
                    handleConsoleOutput(args[0]);
                    break;
                case 'install completed':
                    $wire.dispatch('refresh-sidebar');
                    $wire.dispatch('refresh-topbar');
                    $wire.dispatch('removeAlertBanner', { id: 'server_conflict' });
                    break;
                case 'feature match':
                    Livewire.dispatch('mount-feature', { data: args[0] });
                    break;
                case 'status':
                    handlePowerChangeEvent(args[0]);
                    $wire.dispatch('console-status', { state: args[0] });
                    break;
                case 'transfer status':
                    handleTransferStatus(args[0]);
                    break;
                case 'daemon error':
                    handleDaemonErrorOutput(args[0]);
                    break;
                case 'stats':
                    $wire.dispatchSelf('store-stats', { data: args[0] });
                    break;
                case 'auth success':
                    socket.send(JSON.stringify({
                        'event': 'send logs',
                        'args': [null]
                    }));
                    break;
                case 'token expiring':
                case 'token expired':
                    $wire.dispatchSelf('token-request');
                    break;
            }
        };

        socket.onopen = (event) => {
            $wire.dispatchSelf('token-request');
        };

        Livewire.on('setServerState', ({ state, uuid }) => {
            const serverUuid = "{{ $this->server->uuid }}";
            if (uuid !== serverUuid) {
                return;
            }

            socket.send(JSON.stringify({
                'event': 'set state',
                'args': [state]
            }));
        });

        $wire.on('sendAuthRequest', ({ token }) => {
            socket.send(JSON.stringify({
                'event': 'auth',
                'args': [token]
            }));
        });

        $wire.on('sendServerCommand', ({ command }) => {
            socket.send(JSON.stringify({
                'event': 'send command',
                'args': [command]
            }));
        });
    </script>
    @endscript
</x-filament::widget>
