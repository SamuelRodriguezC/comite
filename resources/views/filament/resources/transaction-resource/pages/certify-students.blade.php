<x-filament-panels::page>
    {{-- MENSAJE INFORMATIVO --}}
    <div class="flex items-center justify-between p-4 mb-4">
        <div>
            <strong>Importante:</strong> Antes de certificar, asegúrese de que el formato cumpla con los datos correctos de los estudiantes, el curso, y el firmante seleccionado. Una vez generada la certificación, los datos no podrán ser modificados en este documento.
        </div>
        <form method="GET" action="{{ route('certificate.pdf', $record->id) }}" target="_blank">
            <x-filament::button type="submit" color="success">
                {{ $record->certificate ? 'Generar Nuevo' : 'Generar Certificación' }}
            </x-filament::button>
        </form>
    </div>

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
            Los(as) estudiante(s) mencionado(s) a continuación han finalizado satisfactoriamente la modalidad de opción de
            grado titulada <strong>{{ $this->getViewData()['grade_option'] }}</strong>, cumpliendo con los requisitos académicos
            establecidos por la institución.
        </p>

        {{-- TABLA ESTUDIANTES --}}
        <table class="w-full mt-2 border-collapse" style="border: 1px solid black;">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-2 py-1 border">Nombre Completo</th>
                    <th class="px-2 py-1 border">Tipo de Documento</th>
                    <th class="px-2 py-1 border">Número de Documento</th>
                    <th class="px-2 py-1 border">Nivel Universitario</th>
                    <th class="px-2 py-1 border">Programa</th>
                </tr>
            </thead>
            <tbody>
                @foreach($this->getViewData()['students'] as $student)
                <tr>
                    <td class="px-2 py-1 border">{{ $student['nombres'] }}</td>
                    <td class="px-2 py-1 border">{{ $student['document_type'] }}</td>
                    <td class="px-2 py-1 border"> {{ $student['document_number'] }}</td>
                    <td class="px-2 py-1 border">{{ $student['level'] ? \App\Enums\Level::from($student['level'])->getLabel() : '' }}</td>
                    <td class="px-2 py-1 border">{{ $student['course'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <p class="mt-2">
            Se firma en <strong>Bogotá</strong> a los <strong>{{ \Carbon\Carbon::now()->format('d') }}</strong> días del mes de
            <strong>{{ \Carbon\Carbon::now()->locale('es')->isoFormat('MMMM') }}</strong> del <strong>{{ \Carbon\Carbon::now()->year }}</strong>.
        </p>

    {{-- FIRMA --}}
    <div class="mt-6" style="text-align: left;">
        @if($this->getViewData()['signer'])
            <img src="{{ asset($this->getViewData()['signer']->signature) }}"
                alt="Firma" width="120" style="margin-bottom: 10px;">
            <p>
               {{ $this->getViewData()['signer']->first_name }} {{ $this->getViewData()['signer']->last_name }}<br>
                Director(a) Centro de Investigación<br>
                Facultad de {{ $this->getViewData()['signer']->faculty }}<br>
                {{ \App\Enums\Seccional::from($this->getViewData()['signer']->seccional)->getLabel() }}<br>
                Universidad Libre
            </p>
        @else
            <p><em>No se seleccionó firmador</em></p>
        @endif
    </div>
    </div>

</x-filament-panels::page>
