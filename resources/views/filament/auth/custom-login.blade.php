<div class="flex items-center justify-center ">
    <div class="w-full max-w-md p-6 bg-white">
        {{-- Logo dan Judul Sistem --}}
        <div class="flex items-center gap-4 mb-4 justify-center">
            <img src="{{ asset('img/logo-bengkel.png') }}" alt="Logo Bengkel" class="h-16">
        </div>

        {{-- Heading dan Subheading dari getHeading() --}}
        <h2 class="text-2xl font-bold text-center text-gray-800">
            {{ $this->getHeading() }}
        </h2>

        @if ($subheading = $this->getSubheading())
            <p class="mt-1 text-sm text-center text-gray-600">
                {{ $subheading }}
            </p>
        @endif

        <form wire:submit.prevent="authenticate" class="mt-6 space-y-4">
            {{ $this->form }}

            @if (filled($this->getFormActions()))
                <div class="mt-6 flex flex-col space-y-4">
                    @foreach ($this->getFormActions() as $action)
                        {{ $action }}
                    @endforeach
                </div>
            @endif
        </form>
    </div>
</div>
