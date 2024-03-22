<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubAffiliateResource\Pages;
use App\Filament\Resources\SubAffiliateResource\Widgets\SubAffiliateOverview;
use App\Models\SubAffiliate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;

class SubAffiliateResource extends Resource
{
    protected static ?string $model = SubAffiliate::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Sub. Afiliados';

    protected static ?string $modelLabel = 'Sub. Afiliados';

    /**
     * @param Form $form
     * @return Form
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Usuários')
                    ->placeholder('Selecione um usuário')
                    ->relationship(name: 'user', titleAttribute: 'name')
                    ->searchable()
                    ->live()
                    ->columnSpanFull(),
            ]);
    }

    /**
     * @param Table $table
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuário')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime()
                    ->sortable(),
                IconColumn::make('status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualização')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([

            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /**
     * @return string[]
     */
    public static function getWidgets(): array
    {
        return [
            SubAffiliateOverview::class,
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubAffiliates::route('/'),
        ];
    }
}
