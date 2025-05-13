<section id="inicio" class="relative overflow-hidden bg-gray-100">
    {{-- Navbar (puedes incluirlo con un componente o directamente) --}}
    @include('components.navbar')

    <div class="container-custom grid grid-cols-1 md:grid-cols-2 min-h-[650px] mt-10">
        {{-- Texto de bienvenida --}}
        <div class="relative z-20 flex flex-col justify-center px-10 py-14 md:py-0">
            <div class="text-center md:text-left space-y-10 lg:max-w-[400px]">
                {{-- Título principal --}}
                <h1
                    class="text-2xl font-bold leading-snug translate-y-12 opacity-0 lg:text-5xl animate-fade-up animation-delay-500">
                    Hola, Bienvenid@ al <span class="text-red-700">Comité</span> Proyectos de Grado
                </h1>

                {{-- Descripción --}}
                <p class="translate-x-12 opacity-0 animate-fade-right animation-delay-700">
                    Espacio creado para el manejo y colaboración de Proyectos de grado en la Facultad de Ingeniería de
                    la Universidad Libre.
                </p>

                {{-- Botón --}}
                <div
                    class="flex justify-center translate-y-12 opacity-0 md:justify-start animate-fade-up animation-delay-900">
                    <a class="flex flex-row items-center gap-2 mt-3 primary-btn group" href="login">
                        Ver más
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="text-xl transition-transform duration-300 group-hover:translate-x-2 group-hover:-rotate-12"
                            width="16px" height="16px" viewBox="0 0 16 16" version="1.1"
                            xmlns:xlink="http://www.w3.org/1999/xlink">
                            <path fill="#fff"
                                d="M15.090 12.79c0.235-0.185 0.385-0.469 0.385-0.789 0-0.358-0.188-0.672-0.471-0.849l-0.004-5.822-1 0.67v5.15c-0.283 0.18-0.468 0.492-0.468 0.847 0 0.316 0.147 0.598 0.376 0.782l-0.378 0.502c-0.323 0.41-0.521 0.931-0.53 1.498l-0 1.222h0.81c0.002 0 0.004 0 0.005 0 0.411 0 0.757-0.282 0.853-0.664l0.331-1.336v2h1v-1.21c-0.009-0.569-0.207-1.090-0.534-1.505z">
                            </path>
                            <path fill="#fff" d="M8 0l-8 4 8 5 8-5-8-4z"></path>
                            <path fill="#fff" d="M8 10l-5-3.33v1.71c0 0.91 2.94 3.62 5 3.62s5-2.71 5-3.62v-1.71z">
                            </path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- Imagen principal + decorativa --}}
        <div class="relative flex items-center justify-around">
            <img src="{{ asset('images/LogoUniversidad.png') }}" alt="Logo" class="w-[250px] sm:w-[300px] md:w-[400px] xl:w-[400px]
           relative z-10 drop-shadow
           ml-0 sm:ml-10 md:ml-20 lg:ml-28" />
            <img src="{{ asset('images/Blob.svg') }}" alt="Blob"
    class="absolute -top-32 w-[1200px] md:w-[2000px] z-[1] hidden md:block scale-150" />


        </div>
    </div>
</section>
