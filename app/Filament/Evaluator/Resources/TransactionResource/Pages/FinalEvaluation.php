<?php

namespace App\Filament\Evaluator\Resources\TransactionResource\Pages;

use Filament\Forms;
use Filament\Actions;
use App\Models\Signer;
use App\Models\Profile;
use Filament\Forms\Form;
use App\Models\Transaction;
use Filament\Resources\Pages\Page;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
// use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use App\Filament\Evaluator\Resources\TransactionResource;

class FinalEvaluation extends Page  implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;
    protected static string $resource = TransactionResource::class;

    protected static string $view = 'filament.evaluator.resources.transaction-resource.pages.final-evaluation';
    protected static ?string $title = 'Evaluación Final';

    // Obtener los modelos necesarios
    public Transaction $transaction;
    public Profile $profile;
    public Signer $signer;

    public int $currentStep = 0;


    public ?array $data = []; // Necesario para guardar estado del formulario
    public ?array $advisors = [];

    // Acciones de Encabezado
    protected function getHeaderActions(): array
    {
        return [
            // Acción para Volver
            Actions\Action::make('back')
                ->label('Volver')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(fn() => route('filament.evaluator.resources.transactions.index')),
        ];
    }


    // Pasar datos para pre-cargar en el formulario filament
    public function mount(Transaction $transaction, Profile $profile, Signer $signer): void
    {
        $this->transaction = $transaction;
        $this->profile = $profile;
        $this->signer = $signer;

        // Filtrar perfiles con rol estudiante
        $studentProfiles = $transaction->profiles()
            ->wherePivot('role_id', 1) // 1 = Estudiante
            ->get();

        $this->advisors = $transaction->profiles()
            ->wherePivot('role_id', 2)
            ->get()
            ->pluck('full_name', 'id')
            ->toArray();

        // Pasar datos al formulario
        $this->form->fill([
            'signer_id' => $this->signer->id,
            'advisor_id' => array_key_first($this->advisors),
            'grade_option' => $transaction->option->option,
            'evaluator_name' => $profile->fullname,

            // Informe Final
            'final_report' => [
                ['name' => 'Desarrollo Metodológico', 'weight' => 40],
                ['name' => 'Resultados Obtenidos', 'weight' => 40],
                ['name' => 'Presentación del Documento', 'weight' => 20],
            ],

            // Sustentación del Proyecto
            'projects_support' => [
                ['name' => 'La exposición es clara y demuestra dominio del tema', 'weight' => 20],
                ['name' => 'Se presentan los resultados del proyecto de forma clara y precisa', 'weight' => 35],
                ['name' => 'Las respuestas a las inquietudespropuestas son acertadas y aclaradora', 'weight' => 35],
                ['name' => 'Los medios empleados son adecuados', 'weight' => 10],
            ],
            'students' => $studentProfiles->map(fn($p) => [
                'name' => $p->full_name,
            ])->toArray(),
        ]);
    }


    // Formulario para capturar datos del certificado
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    // ---------------------- SECCIÓN INFORMACIÓN GENERAL ----------------------
                    Wizard\Step::make('Información General')
                        ->schema([
                            Fieldset::make('Estudiantes de la Opción de Grado')
                                ->schema([
                                    // --- Estudiantes de la opción (Precargadados) ---
                                    Repeater::make('students')
                                        ->label('Integrantes')
                                        ->schema([
                                            TextInput::make('name')
                                                ->label('Nombre Completo')
                                                ->disabled(), // No editable, solo mostrar
                                            TextInput::make('code')
                                                ->label('Código Institucional')
                                                ->numeric()
                                                ->maxlength(10)
                                                ->rules(['digits_between:1,10']) // Validación extra en servidor
                                                ->required()
                                        ])
                                        ->columns(2)
                                        ->deletable(false)
                                        ->addable(false)
                                        ->reorderable(false)
                                        ->columnSpanFull()
                                ])->columnSpan(1),
                            Fieldset::make('Información del Proyecto')
                                ->schema([
                                    // ---- Nombre proyecto ---
                                    TextInput::make('project_name')
                                        ->label('Nombre del Proyecto')
                                        ->maxlength(50)
                                        ->required(),

                                    // --- Opción de Grado ---
                                    TextInput::make('grade_option')
                                        ->label('Opción de Grado')
                                        ->live()
                                        ->disabled(),
                                    // --- Asesor ---
                                    Select::make('advisor_id')
                                        ->label('Director del Proyecto')
                                        ->options($this->advisors)
                                        ->searchable()
                                        ->required(),

                                ])->columnSpan(1)->columns(1),

                            // ---------------------- SECCIÓN JURADOS ----------------------
                            Section::make('Jurados')
                                ->description('Selecciona los jurados en este apartado. Solo aparecerán los docentes que tengan su firma registrada en el perfil. Si no encuentras a alguien, es probable que aún no haya cargado su firma')
                                ->schema([
                                    Select::make('jury_1_id')
                                        ->label('Jurado (1)')
                                        ->options(function () {
                                            return Profile::whereHas('user.roles', function ($query) {
                                                $query->whereIn('name', ['Asesor', 'Evaluador', 'Coordinador']);
                                            })
                                            ->whereHas('signature') // Solo perfiles que tengan firma
                                            ->get()
                                            ->pluck('full_name', 'id');
                                        })
                                        ->searchable()
                                        ->required(),

                                    Select::make('jury_2_id')
                                        ->label('Jurado (2)')
                                        ->options(function () {
                                            return Profile::whereHas('user.roles', function ($query) {
                                                $query->whereIn('name', ['Asesor', 'Evaluador', 'Coordinador']);
                                            })
                                            ->whereHas('signature')
                                            ->get()
                                            ->pluck('full_name', 'id');
                                        })
                                        ->searchable()
                                        ->required(),
                                ])
                                ->columns(2)
                                ->columnSpanFull(),



                        ])->columns(2),


                    // ---------------------- SECCIÓN INFORME FINAL 40% ----------------------
                    Wizard\Step::make('Informe Final (40%)')
                        ->schema([
                            //  --- Categorías ---
                            Fieldset::make('Categorías')
                                ->schema([
                                    Repeater::make('final_report')
                                        ->label('Informe Final (40%)')
                                        ->schema([
                                            TextInput::make('name') // Precargado en el mount
                                                ->label('Criterio')
                                                ->disabled()
                                                ->required()
                                                ->columnSpan(2),
                                            TextInput::make('grade')
                                                ->label('Nota')
                                                ->numeric()
                                                ->required()
                                                ->placeholder('entre 0.0 y 5.0')
                                                ->step(0.1)
                                                ->minValue(0)
                                                ->rule('regex:/^(?:[0-4](?:\.\d)?|5(?:\.0)?)$/') //Solo ún decimal
                                                ->maxValue(5)
                                                ->hintIcon(
                                                    'heroicon-m-information-circle',
                                                    tooltip: fn($get) => "Este criterio equivale al {$get('weight')}% de la nota final"
                                                )
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                    // Calcular la nota del informe
                                                    $items = $get('../../final_report');
                                                    $total = 0;
                                                    foreach ($items as $item) {
                                                        if (isset($item['grade']) && is_numeric($item['grade'])) {
                                                            $total += $item['grade'] * ($item['weight'] / 100);
                                                        }
                                                    }
                                                    $set('../../final_report_grade', round($total, 2));

                                                    // Calcular la nota definitiva combinando informe (40%) + sustentación (60%)
                                                    $supportGrade = $get('../../projects_support_grade') ?? 0;
                                                    $finalGrade = ($total * 0.4) + ($supportGrade * 0.6);
                                                    $set('../../final_grade', round($finalGrade, 2));
                                                })
                                                ->columnSpan(1),
                                            TextInput::make('text')
                                                ->label('Observaciones')
                                                ->required()
                                                ->maxLength(150)
                                                ->columnSpan(3),
                                        ])->defaultItems(3)
                                        ->minItems(3)
                                        ->maxItems(3)
                                        ->deletable(false)
                                        ->addable(false)
                                        ->reorderable(false)
                                        ->columns(6)
                                        ->columnSpanFull(),
                                ])->columnSpanFull(),

                            //  --- Aspectos Complementarios ---
                            Fieldset::make('Aspectos complementarios')
                                ->schema([
                                    // Carta de Aprobación de la empresa
                                    Radio::make('company_approval')
                                        ->label('¿El documento viene acompañado de una carta de aprobación de la empresa?')
                                        ->required()
                                        ->options([
                                            true => 'Si',
                                            false => 'No',
                                        ])->columnSpan(1),
                                    Radio::make('company_verification')
                                        ->label('¿Se ha verificado la ejecución del proyecto en la empresa?')
                                        ->required()
                                        ->options([
                                            true => 'Si',
                                            false => 'No',
                                        ])->columnSpan(1)
                                ])->columns(2)->columnSpanFull(),

                            // --- Nota Final Informe ---
                            Section::make()
                                ->schema([
                                    TextInput::make('final_report_grade')
                                        ->label('Nota Final - Informe (40%)')
                                        ->disabled(),
                                ])
                                ->columnSpan(1),


                        ])->columns(4),
                    // ---------------------- SECCIÓN SUSTENTACIÓN 60% ----------------------
                    Wizard\Step::make('Sustentación (60%)')
                        ->schema([
                            Repeater::make('projects_support')
                                ->label('Sustentación Proyecto (60%)')
                                ->schema([
                                    TextInput::make('name') // Precargado en el mount
                                        ->label('Criterio')
                                        ->disabled()
                                        ->required()
                                        ->columnSpan(4),
                                    TextInput::make('grade')
                                        ->label('Nota')
                                        ->numeric()
                                        ->required()
                                        ->placeholder('entre 0.0 y 5.0')
                                        ->step(0.1)
                                        ->minValue(0)
                                        ->rule('regex:/^(?:[0-4](?:\.\d)?|5(?:\.0)?)$/') //Solo ún decimal
                                        ->maxValue(5)
                                        ->hintIcon(
                                            'heroicon-m-information-circle',
                                            tooltip: fn($get) => "Este criterio equivale al {$get('weight')}% de la nota final"
                                        )
                                        ->live(onBlur: true) // recalcular al salir del campo
                                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                            // Calcular la nota de sustentación
                                            $items = $get('../../projects_support');
                                            $total = 0;
                                            foreach ($items as $item) {
                                                if (isset($item['grade']) && is_numeric($item['grade'])) {
                                                    $total += $item['grade'] * ($item['weight'] / 100);
                                                }
                                            }
                                            $set('../../projects_support_grade', round($total, 2));

                                            // Calcular la nota definitiva combinando informe (40%) + sustentación (60%)
                                            $reportGrade = $get('../../final_report_grade') ?? 0;
                                            $finalGrade = ($reportGrade * 0.4) + ($total * 0.6);
                                            $set('../../final_grade', round($finalGrade, 2));
                                        })
                                        ->columnSpan(1),
                                    TextInput::make('text')
                                        ->label('Observaciones')
                                        ->required()
                                        ->maxLength(150)
                                        ->columnSpan(3),
                                ])->defaultItems(4)
                                ->minItems(4)
                                ->maxItems(4)
                                ->deletable(false)
                                ->addable(false)
                                ->reorderable(false)
                                ->columns(8)
                                ->columnSpanFull(),
                            // --- Nota Final Sustentación ---
                            Section::make()
                                ->schema([
                                    TextInput::make('projects_support_grade')
                                        ->label('Nota Final - Sustentación (60%)')
                                        ->disabled(),
                                ])->columnSpan(1),

                        ])->columns(3),
                    // ---------------------- SECCIÓN RESULTADOS FINALES ----------------------
                    Wizard\Step::make('Resultados finales')
                        ->schema([
                            FieldSet::make('Evaluación Final Proyecto De Grado')
                                ->schema([
                                    Placeholder::make('final_report_grade')
                                        ->label('Nota Final - Informe (40%)')
                                        ->content(fn($get) => $get('final_report_grade') ?? 0),

                                    Placeholder::make('projects_support_grade')
                                        ->label('Nota Final - Sustentación (60%)')
                                        ->content(fn($get) => $get('projects_support_grade') ?? 0),

                                    TextInput::make('final_grade')
                                        ->label('Nota Definitiva')
                                        ->disabled(),

                                ])->columns(2)->columnSpan(2),

                            FieldSet::make('Evaluador')
                                ->schema([
                                    // Nombre del evaluador
                                    TextInput::make('evaluator_name')
                                        ->label('Nombre del Evaluador: ')
                                        ->disabled()
                                        ->columnSpanFull(),
                                    // Existe o no una firma
                                    Placeholder::make('signature_status')
                                        ->label('Firma Evaluador')
                                        ->columnSpan(2)
                                        ->content(new HtmlString(Blade::render(
                                            <<<BLADE
                                                @if(\$profile->signature)
                                                    <x-filament::badge color="success">
                                                        Firma Registrada
                                                    </x-filament::badge>
                                                @else
                                                    <x-filament::badge color="danger" tooltip="Debes registrar tu firma en tu perfil para generar el certificado">
                                                        No tiene firma registrada
                                                    </x-filament::badge>
                                                @endif
                                            BLADE,
                                            ['profile' => $this->profile] // <-- pasamos la variable al mismo botón (sin no es asi no se reconoce la variable $profile)
                                        ))),
                                ])->columnSpan(1)->columns(3),

                        ])->columns(3)

                    // Botón de envío
                    ])->submitAction(new HtmlString(Blade::render(
                        <<<BLADE
                        <x-filament::button
                            type="submit"
                            color="success"
                            :disabled="! \$profile->signature">
                            Generar Certificado
                        </x-filament::button>
                        BLADE,
                        ['profile' => $this->profile] // <-- pasamos la variable al mismo botón (sin no es asi no se reconoce la variable $profile)
                    ))),
            ])
            ->statePath('data');  // Sirve para mostrar los datos en la vista en tiempo real
    }
}
