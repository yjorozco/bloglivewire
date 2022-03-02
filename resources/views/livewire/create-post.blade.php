<div>
    <x-jet-danger-button wire:click="$set('open', true)">
        crear Nuevo Post
    </x-jet-danger-button>
    <x-jet-dialog-modal wire:model="open">
        <x-slot name="title">
        </x-slot>
        <x-slot name="content">
            <div wire:loading wire:target="image"
                class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Imagen Cargando!</strong>
                <span class="block sm:inline">Espere un momento hasta que la imagen se alla procesado.</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </span>
            </div>
            @if ($image)
                <img class="mb-4" src="{{ $image->temporaryUrl() }}">
            @endif
            <div class="mb-4">
                <x-jet-label value="Titulo del post" />
                <x-jet-input type="text" class="w-full" wire:model="title" />
                <x-jet-input-error for='title' />
            </div>
            <div class="mb-4">
                <x-jet-label value="Contenido del Post" />
                <div  wire:ignore>
                    <textarea id="editor" wire:model="content" class="form-control w-full" rows="6"></textarea>
                </div>
                <x-jet-input-error for='content' />
            </div>
            <div>
                <input type="file" wire:model="image" id="{{ $identificador }}" />
                <x-jet-input-error for='image' />
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$set('open', false)">
                Cancelar
            </x-jet-secondary-button>
            <x-jet-danger-button wire:click="save" wire:loading.attr="disabled" wire:target="save, image"
                class="disable:opacity-25">
                Crear Post
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>
    @push('js')
        <script src="https://cdn.ckeditor.com/ckeditor5/32.0.0/classic/ckeditor.js"></script>
        <script>
            ClassicEditor
                .create(document.querySelector('#editor'))
                .then(function(editor) {
                    editor.model.document.on('change:data', () => {
                        @this.set('content', editor.getData())
                    })
                    livewire.on('resetCKEditor', () => {
                        editor.setData('')
                    })
                })
                .catch(error => {
                    console.error(error);
                });
        </script>
    @endpush
</div>
