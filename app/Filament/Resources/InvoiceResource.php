<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PhpParser\Node\Stmt\Label;


class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $label = 'Invoice';
    protected static ?string $pluralLabel = 'Invoices';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(2)
            ->schema([
                Forms\Components\Select::make('company_id')
                    ->label('Client')
                    ->relationship('company', 'name')
                    ->required(),

                Forms\Components\TextInput::make('invoice_number')
                    ->label('Nomor Invoice')
                    ->disabled()
                    ->dehydrated()
                    ->default(function () {
                        return Invoice::generateInvoiceNumber();
                    }),

                Forms\Components\DatePicker::make('invoice_date')
                    ->label('Tanggal Invoice')
                    ->default(now())
                    ->required(),

                Forms\Components\DatePicker::make('due_date')
                    ->label('Jatuh Tempo'),


                Forms\Components\TextInput::make('recipient')
                    ->label('Penerma'),

                Forms\Components\TextInput::make('recipient_address')
                    ->label('Alamat'),

                Forms\Components\Repeater::make('items')
                    ->relationship('items')
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul Item')
                            ->placeholder('Contoh: VPS, Domain, Hosting, dll')
                            ->required(),
                        Forms\Components\TextInput::make('nominal')
                            ->label('Nominal (Rp)')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('quantity')
                            ->label('Kuantitas')
                            ->numeric()
                            ->default(1)
                            ->required(),
                    ])
                    ->minItems(1)
                    ->addActionLabel('Tambah Item'),

                Forms\Components\Toggle::make('use_ppn')
                    ->label('Gunakan PPN')
                    ->default(true),
                Forms\Components\TextInput::make('ppn_percentage')
                    ->label('Persentase PPN (%)')
                    ->numeric()
                    ->default(11)
                    ->visible(fn(Forms\Get $get) => $get('use_ppn')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('No. Invoice')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->label('From')
                    ->searchable(),

                Tables\Columns\TextColumn::make('recipient')
                    ->label('To')
                    ->searchable(),

                Tables\Columns\TextColumn::make('invoice_date')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date('d/m/Y'),

                Tables\Columns\ToggleColumn::make('use_ppn')
                    ->label('PPN'),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->summarize(Sum::make()
                        ->numeric()
                        ->prefix('Rp. ')
                        ->label('Total'))
                    ->numeric()
                    ->prefix('Rp. '),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('cetak')
                    ->label('')
                    ->tooltip('Print')
                    ->button()
                    ->color('success')
                    // ->outlined()
                    ->icon('heroicon-o-printer')
                    ->url(fn(Invoice $record) => route('invoice.pdf', $record))
                    ->openUrlInNewTab(),
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageInvoices::route('/'),
        ];
    }
}
