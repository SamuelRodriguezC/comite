@php
    $documents = [
        [
            'title' => 'Reglamento',
            'subtitle' => 'v4.0 Anterior',
            'icon' => 'graduation-cap',
            'type' => 'fas',
            'link' => '#',
        ],
        [
            'title' => 'MANUAL DE OPCIONES DE GRADO FACULTAD DE INGENIERÍA',
            'subtitle' => '2022',
            'icon' => 'graduation-cap',
            'type' => 'fas',
            'link' => '#',
        ],
        [
            'title' => 'Formato presentación Propuesta',
            'icon' => 'file-word',
            'type' => 'fas',
            'link' => '#',
        ],
        [
            'title' => 'Guía Elaboracion Anteproyecto',
            'icon' => 'file-pdf',
            'type' => 'fas',
            'link' => '#',
        ],
        [
            'title' => 'Guía Elaboración documento Final',
            'icon' => 'file-pdf',
            'type' => 'fas',
            'link' => '#',
        ],
        [
            'title' => 'Rúbrica - Presentacion de Póster',
            'icon' => 'file-word',
            'type' => 'fas',
            'link' => '#',
        ],
    ];
@endphp

<section id="documents" class="py-20 bg-gray-100">
    <div class="max-w-6xl px-4 mx-auto">
        <h2 class="mb-10 text-3xl font-bold text-center text-gray-900">Documentos</h2>

        {{-- Línea animada (puedes usar AOS u otro para animarla si deseas) --}}
        <div class="h-1 mb-10 origin-center scale-x-0 bg-red-600 rounded-full animate-grow-line"></div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3">
            @foreach ($documents as $doc)
                <a
                    href="{{ $doc['link'] }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="flex flex-col gap-2 p-10 text-gray-800 transition-all duration-300 bg-white shadow-sm rounded-2xl hover:bg-red-200 hover:text-gray-800 hover:scale-125 hover:shadow-2xl animate-fade-up"
                >
                    <i class="{{ $doc['type'] }} fa-{{ $doc['icon'] }} text-red-700 text-3xl"></i>
                    <h3 class="font-semibold">{{ $doc['title'] }}</h3>
                    @if (!empty($doc['subtitle']))
                        <p class="text-sm text-blue-600">{{ $doc['subtitle'] }}</p>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</section>
