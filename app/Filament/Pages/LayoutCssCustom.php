<?php

namespace App\Filament\Pages;

use App\Models\CustomLayout;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Filament\Forms\Components\Actions\Action;
use Creagia\FilamentCodeField\CodeField;

class LayoutCssCustom extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.layout-css-custom';

    protected static ?string $navigationLabel = 'Customização Layout';

    protected static ?string $modelLabel = 'Customização Layout';

    protected static ?string $title = 'Customização Layout';

    protected static ?string $slug = 'custom-layout';

    public ?array $data = [];
    public CustomLayout $custom;

    /**
     * @return void
     */
    public function mount(): void
    {
        $this->custom = CustomLayout::first();
        $this->form->fill($this->custom->toArray());
    }

    /**
     * @param array $data
     * @return array
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {

        return $data;
    }

    /**
     * @param Form $form
     * @return Form
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Customização')
                    ->schema([
                        ColorPicker::make('primary_color')
                            ->label('Primary Color')
                            ->required(),
                        ColorPicker::make('primary_opacity_color')
                            ->label('Primary Opacity Color')
                            ->required(),
                        ColorPicker::make('secundary_color')
                            ->label('Secundary Color')
                            ->required(),
                        ColorPicker::make('gray_dark_color')
                            ->label('Gray Dark Color')
                            ->required(),
                        ColorPicker::make('gray_light_color')
                            ->label('Gray Light Color')
                            ->required(),
                        ColorPicker::make('gray_medium_color')
                            ->label('Gray Medium Color')
                            ->required(),
                        ColorPicker::make('gray_over_color')
                            ->label('Gray Over Color')
                            ->required(),
                        ColorPicker::make('title_color')
                            ->label('Title Color')
                            ->required(),
                        ColorPicker::make('text_color')
                            ->label('Text Color')
                            ->required(),
                        ColorPicker::make('sub_text_color')
                            ->label('Sub Text Color')
                            ->required(),
                        ColorPicker::make('placeholder_color')
                            ->label('Placeholder Color')
                            ->required(),
                        ColorPicker::make('background_color')
                            ->label('Background Color')
                            ->required(),
                        TextInput::make('border_radius')
                            ->label('Border Radius')
                            ->required(),
                    ])->columns(4),
               Section::make()
                ->schema([
                    CodeField::make('custom_css')
                        ->setLanguage(CodeField::CSS)
                        ->withLineNumbers()
                        ->minHeight(400),
                    CodeField::make('custom_js')
                        ->setLanguage(CodeField::JS)
                        ->withLineNumbers()
                        ->minHeight(400),
                ])

            ])
            ->statePath('data');
    }

    /**
     * @return void
     */
    public function submit(): void
    {
        try {
            $custom = CustomLayout::first();

            if(!empty($custom)) {
                if($custom->update($this->data)) {

                    Cache::put('custom', $custom);

                    Notification::make()
                        ->title('Dados alterados')
                        ->body('Dados alterados com sucesso!')
                        ->success()
                        ->send();
                }
            }


        } catch (Halt $exception) {
            Notification::make()
                ->title('Erro ao alterar dados!')
                ->body('Erro ao alterar dados!')
                ->danger()
                ->send();
        }
    }
}
