@php
    $date = \Carbon\Carbon::now()->locale('es');
    // Filtrar perfiles con rol estudiante
    $students = $transaction->profiles->filter(fn($profile) => $profile->pivot->role_id == 1);
@endphp

<x-filament-panels::page>
    <form wire:submit.prevent="submit" class="space-y-6">
        {{ $this->form }}

        <x-filament::button type="submit" color="success">
            Generar certificado
        </x-filament::button>
    </form>

        <div class="p-6 bg-white rounded shadow" style="font-family: DejaVu Sans, sans-serif; font-size: 12px; line-height: 3.4;">
        {{-- HEADER --}}
        <table class="w-full mt-2 mb-4 border-collapse" style="border: 1px solid black;">
            <tr>
                <td style="border: none; display: flex; justify-content: center; align-items: center;">
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}" alt="Logo" width="100">
                </td>
                <td class="font-bold text-center" style="font-size: 11px; border: 1px solid black; vertical-align: center;">
                    FORMA DE CERTIFICACIÓN EN FORMACIÓN DE RECURSO HUMANO EN CTel_Dirección Trabajos de Grado_Pregrado
                </td>
                <td style="border: 1px solid black; font-size: 11px; vertical-align: top;" class="text-center">
                    <strong>ST-INV-02-P-06-F16</strong><br>
                    Versión 1<br>
                    26/06/2024
                </td>
            </tr>
        </table>

        <h2 class="font-bold text-center">UNIVERSIDAD LIBRE</h2>

        <p class="text-center uppercase">
            LA DIRECCIÓN DE CENTRO DE INVESTIGACIÓN DE LA FACULTAD DE INGENIERÍA SECCIONAL BOGOTÁ D.C.
        </p>

        <p><strong>Hace constar que:</strong></p>

        <p>
            El (La) {{ isset($data['academic_title'])
            ? \App\Enums\AcademicTitle::from($data['academic_title'])->getLabel()
            : '"Título académico de mayor grado"' }}  {{ $profile->full_name }}, identificado(a) con {{ $profile->document->type }}: {{ $profile->document_number }} dirigió a cabalidad y llevó a buen término los siguientes trabajos de grado de pregrado del programa de {{ $profile->courseInTransaction($transaction)?->course ?? 'Curso no asignado' }} de la Facultad de Ingeniería.
        </p>

        {{-- TABLA ESTUDIANTES --}}
        <table class="w-full mt-2 text-center border-collapse" style="border: 1px solid black;">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-2 py-1 border">Título del trabajo de grado</th>
                    <th class="px-2 py-1 border">Autor(es)</th>
                    <th class="px-2 py-1 border">Identificación Autor(es)</th>
                    <th class="px-2 py-1 border">Fecha Sustentación</th>
                    <th class="px-2 py-1 border">Rol(Director o Cordirector)</th>
                    <th class="px-2 py-1 border">Distinción (si aplica)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-2 py-1 border">{{ $data['thesis_title'] ?? 'Aún no has completado este campo' }}</td>
                        <td class="px-2 py-1 border">
                            @foreach ($students as $student)
                                {{ $student->full_name }}
                                {{-- Si hay mas de un estudiante poner un separador --}}
                                @if (!$loop->last)
                                    -
                                @endif
                            @endforeach
                        </td>
                        <td class="px-2 py-1 border">
                            @foreach ($students as $student)
                                {{ $student->document->type }}: {{ $student->document_number }}
                                {{-- Si hay mas de un estudiante poner un separador --}}
                                @if (!$loop->last)
                                    -
                                @endif
                            @endforeach
                        </td>
                    <td class="px-2 py-1 border">{{ $data['defense_date'] ?? 'Aún no has completado este campo' }}</td>
                    <td class="px-2 py-1 border">{{ $data['advisor_role'] ?? 'Sin Rol Seleccionado'}}</td>
                    <td class="px-2 py-1 border">{{ $data['distinction'] ?? '' }}</td>
                </tr>
            </tbody>
        </table>

        <p class="mt-2">
            Se firma en <strong>Bogotá</strong> a los <strong>{{ $date->day }}</strong> días del mes de
            <strong>{{ $date->isoFormat('MMMM') }}</strong> del <strong>{{ $date->year }}</strong>.
        </p>

    {{-- FIRMA --}}
    <div class="mt-6" style="text-align: left;">
        @if($signer)
            <img src="{{ asset($signer->signature) }}"
                alt="Firma" width="120" style="margin-bottom: 10px;">
            <p>
               {{  $signer->full_name }}<br>
                Director(a) Centro de Investigación<br>
                Facultad de {{ $signer->faculty }}<br>
                {{ $signer->seccional->getlabel() }}<br>
                Universidad Libre
            </p>
        @else
            <p><em>No se seleccionó firmador</em></p>
        @endif
    </div>
    </div>
</x-filament-panels::page>

