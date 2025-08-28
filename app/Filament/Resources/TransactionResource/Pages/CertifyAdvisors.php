<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Models\Signer;
use Filament\Actions;
use App\Models\Profile;
use App\Models\Transaction;
use Filament\Resources\Pages\Page;
use App\Filament\Resources\TransactionResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;

class CertifyAdvisors extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string $resource = TransactionResource::class;
    protected static string $view = 'filament.resources.transaction-resource.pages.certify-advisors';
    protected static ?string $title = 'Certificar Asesor';


    // Obtener los modelos necesarios
    public Transaction $transaction;
    public Profile $profile;
    public Signer $signer;

    // Arreglo para almacenar los datos del formulario
    public ?array $data = [];

    // Acciones de Encabezado
    protected function getHeaderActions(): array
    {
        return [
            // Acción para Volver a la edición de la transacción
            Actions\Action::make('back')
                ->label('Volver')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(fn () => route(
                    'filament.coordinator.resources.transactions.edit',
                    [
                        'record' => $this->transaction,
                        'activeRelationManager' => '1',
                    ]
                )),
        ];
    }

    // Pasar datos a la vista Blade
    public function mount(Transaction $transaction, Profile $profile, Signer $signer): void
    {
        $this->transaction = $transaction;
        $this->profile = $profile;
        $this->signer = $signer;

        $this->form->fill([
            'signer_id' => $this->signer->id,
        ]);
    }

    // Formulario para capturar datos del certificado
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Titulo Academico
                Forms\Components\Select::make('academic_title')
                    ->label('Título académico de mayor grado (Asesor)')
                    ->live()
                    ->searchable()
                    ->options(\App\Enums\AcademicTitle::class)
                    ->required(),
                // Título del trabajo de grado
                Forms\Components\TextInput::make('thesis_title')
                    ->label('Título del trabajo de grado')
                    ->live()
                    ->maxlength(50)
                    ->required(),
                // Fecha de sustentación
                Forms\Components\DatePicker::make('defense_date')
                    ->label('Fecha de sustentación')
                    ->live()
                    ->required(),
                // Rol del asesor
                Forms\Components\Select::make('advisor_role')
                    ->label('Rol')
                    ->live()
                    ->options([
                        'Director' => 'Director',
                        'Codirector' => 'Codirector',
                    ])
                    ->required(),
                // Distinción
                Forms\Components\TextInput::make('distinction')
                    ->label('Distinción (opcional)')
                    ->maxlength(50)
                    ->live()
                    ->nullable(),
            ])
            ->statePath('data');  // Sirve para mostrar los datos en la vista en tiempo real
    }

    // Lógica al enviar el formulario
    public function submit()
    {
        $state = $this->form->getState(); //Obtener los datos del formulario
        $state['signer_id'] = $this->signer->id; // agregamos signer_id

        // Usar $state en la request
        app(\App\Http\Controllers\CertificateAdvisorController::class)
            ->storeAdvisor(
                new \Illuminate\Http\Request($state),
                $this->transaction,
                $this->profile
        );

        // Mostrar mensaje de éxito
        Notification::make()
            ->title('Certificado generado correctamente')
            ->success()
            ->send();

        // Redirigir a la edición de la transacción luego de enviar el formulario
        return redirect()->route('filament.coordinator.resources.transactions.edit', [
            'record' => $this->transaction,
            'activeRelationManager' => '1',
        ]);
    }
}
