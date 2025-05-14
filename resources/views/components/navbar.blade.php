
    <nav x-data="{ menuOpen: false, scrolled: false }"
        x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 20 })"
        :class="scrolled ? 'bg-[#1e1e1e] text-white shadow-md' : 'bg-transparent'"
        class="fixed top-0 left-0 right-0 z-50 transition-all duration-500 ease-in-out ">
        <div :class="scrolled ? 'py-4' : 'py-10'"
            class="flex items-center justify-between max-w-screen-xl px-4 mx-auto transition-all duration-500 ease-in-out">
            {{-- Logo --}}
            <div class="text-2xl font-bold">Universidad Libre</div>

            {{-- Menú Desktop --}}
            <div class="hidden lg:block">
                <ul class="flex items-center gap-3">
                    @foreach([
                    ['id' => 1, 'title' => 'Inicio', 'path' => '#inicio'],
                    ['id' => 2, 'title' => 'Proceso', 'path' => '#banner'],
                    ['id' => 3, 'title' => 'Documentos', 'path' => '#documents'],
                    ['id' => 4, 'title' => 'Programas', 'path' => '#programs'],
                    ['id' => 5, 'title' => 'Contacto', 'path' => '#footer'],
                    ] as $menu)
                    <li>
                        <a href="{{ $menu['path'] }}"
                            class="relative inline-block px-3 py-2 hover:text-yellow-600 group">
                            <div
                                class="absolute bottom-0 hidden w-2 h-2 mt-3 -translate-x-1/2 bg-yellow-600 rounded-full left-1/2 top-1/2 group-hover:block">
                            </div>
                            {{ $menu['title'] }}
                        </a>
                    </li>
                    @endforeach
                    <a href="login" class="primary-btn">Iniciar Sesión</a>
                    <a href="register" class="primary-btn">Registro</a>
                </ul>
            </div>

            {{-- Ícono Menú Móvil --}}
            <div class="z-30 lg:hidden" @click="menuOpen = !menuOpen">
                <template x-if="menuOpen">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </template>
                <template x-if="!menuOpen">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </template>
            </div>
        </div>

        {{-- Sidebar Móvil --}}
        <div x-show="menuOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="fixed top-0 right-0 z-20 flex flex-col w-64 h-screen gap-6 p-6 shadow-md lg:hidden"
            :class="scrolled ? 'bg-[#1e1e1e] text-white' : 'bg-white text-black'">
            @foreach([
            ['id' => 1, 'title' => 'Inicio', 'path' => '#inicio'],
            ['id' => 2, 'title' => 'Proceso', 'path' => '#banner'],
            ['id' => 3, 'title' => 'Documentos', 'path' => '#documents'],
            ['id' => 4, 'title' => 'Programas', 'path' => '#programs'],
            ['id' => 5, 'title' => 'Contacto', 'path' => '#footer'],
            ] as $menu)
            <a href="{{ $menu['path'] }}" class="text-lg font-medium hover:text-yellow-600" @click="menuOpen = false">
                {{ $menu['title'] }}
            </a>
            @endforeach
                <a href="login" class="primary-btn">Iniciar Sesión</a>
                <a href="register" class="primary-btn">Registro</a>
        </div>

        {{-- Fondo oscuro --}}
        <div x-show="menuOpen" @click="menuOpen = false" class="fixed inset-0 bg-[#1e1e1e] opacity-50 z-10"></div>
    </nav>


