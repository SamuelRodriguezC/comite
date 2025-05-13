@php
    $programs = [
        [
            'title' => 'Ingeniería Ambiental',
            'link' => 'https://comunicacionesbogota.unilibre.edu.co/home/pregrado/pregrado-en-ingenieria-ambiental/',
            'icon' => 'globe-alt',
        ],
        [
            'title' => 'Ingeniería en Ciencia de Datos',
            'link' => 'https://comunicacionesbogota.unilibre.edu.co/home/pregrado/ingenieria-en-ciencias-de-datos/',
            'icon' => 'circle-stack',
        ],
        [
            'title' => 'Ingeniería Industrial',
            'link' => 'https://comunicacionesbogota.unilibre.edu.co/home/pregrado/pregrado-en-ingenieria-industrial/',
            'icon' => 'building-office-2',
        ],
        [
            'title' => 'Ingeniería Mecánica',
            'link' => 'https://comunicacionesbogota.unilibre.edu.co/home/pregrado/pregrado-en-ingenieria-mecanica/',
            'icon' => 'cog-6-tooth',
        ],
        [
            'title' => 'Ingeniería en Sistemas',
            'link' => 'https://comunicacionesbogota.unilibre.edu.co/home/pregrado/pregrado-en-ingenieria-de-sistemas/',
            'icon' => 'cpu-chip',
        ],
        [
            'title' => 'Especialización en Gerencia Ambiental',
            'link' => 'https://comunicacionesbogota.unilibre.edu.co/home/posgrado/especializacion-en-gerencia-ambiental/',
            'icon' => 'sun',
        ],
        [
            'title' => 'Especialización en Gerencia de Calidad de Productos y Servicios',
            'link' => 'https://comunicacionesbogota.unilibre.edu.co/home/posgrado/especializacion-en-gerencia-en-calidad-de-productos-y-servicios/',
            'icon' => 'globe-europe-africa',
        ],
        [
            'title' => 'Especialización en Gerencia de Mercadeo y Estrategia de Ventas',
            'link' => 'https://www.unilibre.edu.co/bogota/ingenieria/especializacion-en-gerencia-de-mercadeo-y-estrategia-de-ventas',
            'icon' => 'chart-bar',
        ],
        [
            'title' => 'Maestría en Ingeniería',
            'link' => 'https://www.unilibre.edu.co/posgrados/maestria-en-ingenieria-bogota/',
            'icon' => 'wrench-screwdriver',
        ],
    ];
@endphp

<section id="programs" class="bg-white">
    <div class="container px-4 pt-16 mx-auto pb-14">
        <h1 class="pb-10 text-4xl font-bold text-left">Programas</h1>
        <div class="grid grid-cols-2 gap-8 sm:grid-cols-3 md:grid-cols-6">
            @foreach ($programs as $program)
                <div class="bg-[#f4f4f4] rounded-2xl flex flex-col items-center justify-center gap-4 p-4 py-7 transform transition-all duration-300 hover:bg-white hover:scale-105 hover:shadow-2xl animate-fade-up">
                  <div class="mb-4 text-3xl text-gray-700">
                        @php
                            $icon = "heroicon-o-{$program['icon']}";
                        @endphp
                        <x-dynamic-component :component="$icon" class="w-10 h-10" />
                    </div>
                    <a href="{{ $program['link'] }}" target="_blank" rel="noopener noreferrer" class="px-3 text-center hover:text-red-700">
                        <h1 class="text-lg font-semibold">{{ $program['title'] }}</h1>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
