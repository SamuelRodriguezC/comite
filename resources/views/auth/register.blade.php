<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nombre')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmación de Contraseña')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- -----------Información De Perfil ----------- --}}

        <!-- Name -->
        <div class="mt-4">
            <x-input-label for="profile_name" :value="__('Nombre de Perfil')" />
            <x-text-input id="profile_name" class="block w-full mt-1" type="text" name="profile_name" :value="old('profile_name')" required />
            <x-input-error :messages="$errors->get('profile_name')" class="mt-2" />
        </div>

        <!-- Last Name -->
        <div class="mt-4">
            <x-input-label for="last_name" :value="__('Apellidos')" />
            <x-text-input id="last_name" class="block w-full mt-1" type="text" name="last_name" :value="old('last_name')" required />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>

        <!-- Document Type -->
        <div class="mt-4">
            <x-input-label for="document_id" :value="__('Tipo de Documento')" />
            <select id="document_id" name="document_id" class="block w-full mt-1 rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Seleccione una opción</option>
                @foreach ($documents as $document)
                    <option value="{{ $document->id }}" {{ old('document_id') == $document->id ? 'selected' : '' }}>
                        {{ $document->type }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('document_id')" class="mt-2" />
        </div>

        <!-- Document Number -->
        <div class="mt-4">
            <x-input-label for="document_number" :value="__('Número de Documento')" />
            <x-text-input id="document_number" class="block w-full mt-1" type="text" name="document_number" :value="old('document_number')" required />
            <x-input-error :messages="$errors->get('document_number')" class="mt-2" />
        </div>

         <!-- Phone Number -->
         <div class="mt-4">
            <x-input-label for="phone_number" :value="__('Número de Telefono')" />
            <x-text-input id="phone_number" class="block w-full mt-1" type="text" name="phone_number" :value="old('phone_number')" required />
            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
        </div>

         <!-- Level -->
        <div class="mt-4">
            <x-input-label for="level" :value="__('Nivel Universitario')" />
            <select id="level" name="level" class="block w-full mt-1 rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                @foreach (\App\Enums\Level::cases() as $level)
                    <option value="{{ $level->value }}" {{ old('level') == $level->value ? 'selected' : '' }}>
                        {{ $level->getLabel() }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('level')" class="mt-2" />
        </div>


        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
