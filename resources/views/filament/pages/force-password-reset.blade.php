<x-filament-panels::page.simple>
    <div class="flex flex-col gap-y-2 text-center mb-8">
        <h1 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl">
            Redefinição de Senha
        </h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Escolha uma senha forte que você não tenha usado anteriormente para garantir a proteção total dos seus dados.
        </p>
    </div>

    <x-filament-panels::form wire:submit="submit">
        {{ $this->form }}

        <div class="mt-6">
            <x-filament-panels::form.actions
                :actions="$this->getFormActions()"
                full-width
            />
        </div>
    </x-filament-panels::form>
</x-filament-panels::page.simple>
