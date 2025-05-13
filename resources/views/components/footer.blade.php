<footer id="footer" class="py-7 px-10 bg-[#1e1e1e] text-white">
    <motion.div
        initial="{{ json_encode(['opacity' => 0, 'y' => 50]) }}"
        whileInView="{{ json_encode(['opacity' => 1, 'y' => 0]) }}"
        class="mx-auto container-custom"
    >
        <div class="grid justify-center grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-14 md:gap-4">
            <!-- Primera sección -->
            <div class="space-y-4 max-w-[300px] mx-auto">
                <h1 class="text-2xl font-bold">Universidad Libre</h1>
                <p class="text-dark2">
                    Dirección <br/>
                    Campus La Candelaria:
                    Calle 8 n.º 5-80 <br/>
                    Campus El Bosque Popular:<br/>
                    Carrera 70 n.º 53-40<br/>
                </p>
            </div>

            <!-- Segunda sección -->
            <div class="grid grid-cols-1 gap-10 mx-auto sm:grid-cols-2">
                <div class="space-y-4">
                    <h1 class="text-2xl font-bold">Teléfono:</h1>
                    <div class="text-dark2">
                        <ul class="space-y-2">
                            <li class="duration-200 cursor-pointer hover:text-secondary">
                                PBX: (601) 382 1000
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="space-y-4">
                    <h1 class="text-2xl font-bold">Links</h1>
                    <div class="text-dark2">
                        <ul class="space-y-2">
                            <li class="duration-200 cursor-pointer hover:text-secondary">
                                <a href="#inicio">Inicio</a>
                            </li>
                            <li class="duration-200 cursor-pointer hover:text-secondary">
                                <a href="#banner">Proceso</a>
                            </li>
                            <li class="duration-200 cursor-pointer hover:text-secondary">
                                <a href="#documents">Documentos</a>
                            </li>
                            <li class="duration-200 cursor-pointer hover:text-secondary">
                                <a href="#footer">Contacto</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Tercera sección -->
            <div class="space-y-4 max-w-[300px] mx-auto">
                <h1 class="text-2xl font-bold">Contacto</h1>
                <div class="flex items-center justify-center">
                    <a  href="https://www.unilibre.edu.co/" class="px-6 py-4 font-semibold text-white primary-btn rounded-xl">
                        Unilibre.edu
                    </a>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="pt-4 mt-8 text-sm text-center text-gray-400 border-t border-gray-700">
            © {{ now()->year }} Universidad Libre - Todos los derechos reservados.
        </div>
    </motion.div>
</footer>
