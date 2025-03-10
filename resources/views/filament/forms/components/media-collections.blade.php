<div x-data="{
    dragging: null,
    dropping: null,
    previewImage: null,
    showPreview: false,
    collections: {
        'jewels/packshots': 'Packshots',
        'jewels/lifestyle': 'Lifestyle'
    },
    dragStart(event, media) {
        console.log('Drag start:', media);
        this.dragging = media;
        event.dataTransfer.effectAllowed = 'move';
        event.target.classList.add('opacity-50', 'scale-95');
    },
    dragEnter(event, collection) {
        console.log('Drag enter:', collection);
        event.preventDefault();
        if (this.dragging && this.dragging.collection_name !== collection) {
            this.dropping = collection;
        }
    },
    dragOver(event) {
        event.preventDefault();
    },
    dragLeave(event) {
        // Vérifie si on quitte réellement la zone de drop et pas juste un élément enfant
        const rect = event.currentTarget.getBoundingClientRect();
        const x = event.clientX;
        const y = event.clientY;

        if (x <= rect.left || x >= rect.right || y <= rect.top || y >= rect.bottom) {
            this.dropping = null;
        }
    },
    dragEnd(event) {
        console.log('Drag end - dragging:', this.dragging, 'dropping:', this.dropping);
        if (event.target) {
            event.target.classList.remove('opacity-50', 'scale-95');
        }
        if (this.dragging && this.dropping && this.dragging.collection_name !== this.dropping) {
            console.log('Moving media from', this.dragging.collection_name, 'to', this.dropping);
            Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id')).moveMedia(this.dragging.id, this.dropping);
        }
        this.dragging = null;
        this.dropping = null;
    },
    deleteMedia(mediaId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette image ?')) {
            const livewireComponent = Livewire.find(
                document.querySelector('[wire\\:id]').getAttribute('wire:id')
            );
            livewireComponent.call('deleteMedia', mediaId);
        }
    },
    openPreview(media) {
        console.log('Open preview:', media);
        this.previewImage = media;
        this.showPreview = true;
    }
}" class="relative">
    <div class="flex flex-col gap-4 mb-4">
        @foreach(['jewels/packshots', 'jewels/lifestyle'] as $collection)
            <div
                class="relative p-4 transition-all duration-200 bg-gray-100 rounded-lg"
                :class="{
                    'ring-2 ring-primary-500 bg-primary-50': dropping === '{{ $collection }}',
                    'hover:bg-gray-50': dragging && dragging.collection_name !== '{{ $collection }}'
                }"
                @dragenter="dragEnter($event, '{{ $collection }}')"
                @dragover="dragOver($event)"
                @dragleave="dragLeave($event)"
                @drop="dragEnd($event)"
            >
                <div
                    class="absolute inset-0 flex items-center justify-center transition-opacity rounded-lg opacity-0 pointer-events-none bg-primary-500/10"
                    :class="{ 'opacity-100': dropping === '{{ $collection }}' }"
                >
                    <div class="font-medium text-primary-600">
                        Déposer ici pour déplacer vers {{ $collection === 'jewels/packshots' ? 'Packshots' : 'Lifestyle' }}
                    </div>
                </div>

                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium">
                        {{ $collection === 'jewels/packshots' ? 'Packshots' : 'Lifestyle' }}
                        <span class="text-sm text-gray-500">
                            ({{ $getRecord() ? $getRecord()->getMedia($collection)->count() : '0' }} images)
                        </span>
                    </h3>
                    <div class="fi-fo-file-upload">
                        <input
                            type="file"
                            wire:model="files.{{ $collection }}"
                            multiple
                            class="hidden"
                            accept="image/*"
                            id="file-{{ $collection }}"
                        />
                        <label
                            for="file-{{ $collection }}"
                            class="flex items-center gap-2 px-4 py-2 text-xs text-gray-500 border-2 border-dashed rounded-lg cursor-pointer hover:bg-gray-50"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            {{ $collection === 'jewels/packshots' ? 'Ajouter des photos sur fond blanc' : 'Ajouter des photos de mise en scène' }}
                        </label>
                    </div>
                </div>
                <div class="relative flex flex-wrap gap-4">
                    @if($getRecord())
                        @foreach($getRecord()->getMedia($collection) as $media)
                            <div
                                draggable="true"
                                @dragstart="dragStart($event, {{ json_encode([
                                    'id' => $media->id,
                                    'collection_name' => $collection
                                ]) }})"
                                @dragend="dragEnd($event)"
                                class="relative w-32 h-32 overflow-hidden group"
                            >
                                <img
                                    src="{{ $media->getUrl('small') }}"
                                    class="object-cover w-full h-full transition-transform duration-200 rounded-lg shadow-sm cursor-move"
                                    :class="{ 'scale-105': dragging && dragging.id === {{ $media->id }} }"
                                    alt="{{ $getRecord()->name }}"
                                >
                                <div class="absolute inset-x-0 top-0 z-30 flex items-center justify-between h-10 transition-transform duration-300 transform -translate-y-full bg-gray-900/75 group-hover:translate-y-0">
                                    <button
                                        type="button"
                                        @click.stop="openPreview({{ json_encode([
                                            'url' => $media->getUrl('large'),
                                            'name' => $getRecord()->name
                                        ]) }})"
                                        @mousedown.stop
                                        class="flex items-center justify-center w-6 h-6 mx-4 transition-all rounded-full shadow-lg cursor-zoom-in bg-primary-500 hover:bg-primary-600 hover:scale-110"
                                        title="Agrandir"
                                    >
                                        <svg class="w-3.5 h-3.5 text-white pointer-events-none" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </button>
                                    <button
                                        type="button"
                                        @click.stop="deleteMedia({{ $media->id }})"
                                        @mousedown.stop
                                        class="flex items-center justify-center w-6 h-6 mx-4 transition-all bg-red-500 rounded-full shadow-lg cursor-pointer hover:bg-red-600 hover:scale-110"
                                        title="Supprimer"
                                    >
                                        <svg class="w-3.5 h-3.5 text-white pointer-events-none" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                                <div
                                    class="absolute inset-0 z-20 flex items-center justify-center transition-opacity duration-200 rounded-lg opacity-0 pointer-events-none group-hover:opacity-100 bg-black/25"
                                    :class="{ 'cursor-grabbing': dragging }"
                                >
                                    <span class="text-xs font-medium text-white">Glisser pour déplacer</span>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal de prévisualisation -->
    <div
        x-show="showPreview"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/75"
        @click="showPreview = false"
        @keydown.escape.window="showPreview = false"
    >
        <div class="relative max-w-4xl max-h-[90vh] p-4" @click.stop>
            <button
                @click="showPreview = false"
                class="absolute p-2 text-white rounded-full top-2 right-2 hover:bg-white/20"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <img
                x-bind:src="previewImage?.url"
                x-bind:alt="previewImage?.name"
                class="max-h-full rounded-lg shadow-xl"
            >
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</div>
