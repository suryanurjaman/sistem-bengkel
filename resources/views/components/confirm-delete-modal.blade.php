@props([
    'id',
    'modalId' => 'modal_' . $id,
    'action',
    'method' => 'POST',
    'buttonLabel' => 'Batalkan',
    'modalTitle' => 'Konfirmasi Tindakan',
    'modalDescription' => 'Apakah kamu yakin ingin melanjutkan tindakan ini?',
    'confirmLabel' => 'Ya, Lanjutkan',
    'confirmColor' => 'bg-green-600 hover:bg-green-700 text-white',
])

<!-- Tombol Trigger -->
<button data-modal-target="{{ $modalId }}" data-modal-toggle="{{ $modalId }}"
    class="text-red-600 hover:text-red-800 underline transition duration-150 ease-in-out">
    {{ $buttonLabel }}
</button>

<!-- Modal -->
<div id="{{ $modalId }}" tabindex="-1" aria-hidden="true"
    class="fixed inset-0 z-50 hidden items-center m-0 p-0 justify-center overflow-y-auto overflow-x-hidden bg-black bg-opacity-40"
    style="margin: 0; padding: 0;">
    <div class="relative w-full max-w-md p-4">
        <div class="rounded-lg bg-white shadow-xl dark:bg-gray-800">
            <div class="flex items-center justify-center p-4 border-b rounded-t dark:border-gray-700">
                <svg class="w-8 h-8 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002
                        2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1
                        1 0 0011 2H9zM7 8a1 1 0 012 0v6a1
                        1 0 11-2 0V8zm5-1a1 1 0 00-1
                        1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>

            <div class="p-6 text-center">
                <h3 class="mb-2 text-lg font-semibold text-gray-800 dark:text-white">
                    {{ $modalTitle }}
                </h3>
                <p class="mb-4 text-gray-600 dark:text-gray-300">
                    {{ $modalDescription }}
                </p>

                <form method="POST" action="{{ $action }}">
                    @csrf
                    @method($method)
                    <div class="flex justify-center gap-3">
                        <button type="button" data-modal-toggle="{{ $modalId }}"
                            class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-800 transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium rounded-lg transition {{ $confirmColor }}">
                            {{ $confirmLabel }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
