<section id="banner" class="grid grid-cols-1 gap-8 space-y-6 container-custom py-14 md:py-24 md:grid-cols-2 md:space-y-0">
    {{-- Imagen --}}
    <div class="flex items-center justify-center">
        <img src="{{ asset('images/calidad.png') }}" class="w-[350px] md:max-w-[450px] object-cover drop-shadow" alt="Calidad">
    </div>

    {{-- Texto del Banner --}}
    <div class="flex flex-col justify-center">
        <div class="space-y-12 text-center md:text-left">
            <h1 class="text-2xl font-bold leading-snug md:text-3xl animate-fade-in-scale">
                Para comenzar con el proceso de grado, el estudiante deberá:
            </h1>
            <div class="flex flex-col gap-6">
                {{-- Punto 1 --}}
                <div class="flex items-center gap-4 p-6 bg-[#f4f4f4] rounded-2xl hover:bg-white duration-300 hover:shadow-2xl animate-fade-up delay-200">
                    <x-heroicon-o-book-open class="w-6 h-6 text-gray-700" />
                    <p class="text-lg capitalize">haber cursado mínimo el 60% de su pensúm.</p>
                </div>

                {{-- Punto 2 --}}
                <div class="flex items-center gap-4 p-6 bg-[#f4f4f4] rounded-2xl hover:bg-white duration-300 hover:shadow-2xl animate-fade-up delay-400">
                    <x-heroicon-o-clipboard-document-check class="w-6 h-6 text-gray-700" />
                    <p class="text-lg capitalize">cumplir con los requisitos de la opción de grado</p>
                </div>

                {{-- Punto 3 --}}
                <div class="flex items-center gap-4 p-6 bg-[#f4f4f4] rounded-2xl hover:bg-white duration-300 hover:shadow-2xl animate-fade-up delay-600">
                    <x-heroicon-o-arrow-up-tray class="w-6 h-6 text-gray-700" />
                    <p class="text-lg capitalize">realizar solicitud adjuntando documentos requeridos</p>
                </div>
            </div>
        </div>
    </div>
</section>
