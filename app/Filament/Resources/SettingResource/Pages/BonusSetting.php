<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use App\Models\Setting;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Cache;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class BonusSetting extends Page implements HasForms
{
    use HasPageSidebar, InteractsWithForms;

    protected static string $resource = SettingResource::class;

    protected static string $view = 'filament.resources.setting-resource.pages.bonus-setting';

    /**
     * @return string|Htmlable
     */
    public function getTitle(): string | Htmlable
    {
        return __('Bônus');
    }

    public Setting $record;
    public ?array $data = [];

    /**
     * @dev victormsalatiel - Meu instagram
     * @return void
     */
    public function mount(): void
    {
        $setting = Setting::first();
        $this->record = $setting;
        $this->form->fill($setting->toArray());
    }

    /**
     * @return void
     */
    public function save()
    {
        try {
            if(env('APP_DEMO')) {
                Notification::make()
                    ->title('Atenção')
                    ->body('Você não pode realizar está alteração na versão demo')
                    ->danger()
                    ->send();
                return;
            }

            $setting = Setting::find($this->record->id);

            if($setting->update($this->data)) {
                Cache::put('setting', $setting);

                Notification::make()
                    ->title('Dados alterados')
                    ->body('Dados alterados com sucesso!')
                    ->success()
                    ->send();

                redirect(route('filament.admin.resources.settings.bonus', ['record' => $this->record->id]));

            }
        } catch (Halt $exception) {
            return;
        }
    }

    /**
     * @dev victormsalatiel - Meu instagram
     * @param Form $form
     * @return Form
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Ajuste de Bônus')
                    ->description('Formulário ajustar o Bônus plataforma')
                    ->schema([
                        TextInput::make('min_deposit')
                            ->label('Min Deposito')
                            ->numeric()
                            ->maxLength(191),
                        TextInput::make('max_deposit')
                            ->label('Max Deposito')
                            ->numeric()
                            ->maxLength(191),
                        TextInput::make('min_withdrawal')
                            ->label('Min Saque')
                            ->numeric()
                            ->maxLength(191),
                        TextInput::make('max_withdrawal')
                            ->label('Max Saque')
                            ->numeric()
                            ->maxLength(191),
                        TextInput::make('rollover')
                            ->label('Rollover Bônus')
                            ->numeric()
                            ->default(1)
                            ->suffix('x')
                            ->helperText('Coloque a quantidade de multiplicação do Bônus')
                            ->maxLength(191),
                        TextInput::make('rollover_deposit')
                            ->label('Rollover Deposito')
                            ->numeric()
                            ->default(1)
                            ->suffix('x')
                            ->helperText('Coloque a quantidade de multiplicação do Deposito')
                            ->maxLength(191),
                        TextInput::make('bonus_vip')
                            ->label('Bônus Vip')
                            ->placeholder('Defina o Bônus vip, quanto de bônus ganha a cada 1 real/dolar depositado.')
                            ->numeric()
                            ->maxLength(191),
                        Toggle::make('activate_vip_bonus')
                            ->label('Ativar/Desativar Bônus Vip'),
                    ])->columns(2)
            ])
            ->statePath('data') ;
    }
}
