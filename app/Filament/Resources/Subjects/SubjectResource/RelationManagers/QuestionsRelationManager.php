<?php

namespace App\Filament\Resources\Subjects\SubjectResource\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    protected static ?string $recordTitleAttribute = 'content';
    protected static ?string $title = 'Daftar Soal';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\FileUpload::make('image_path')
                    ->label('Gambar (Opsional)')
                    ->image()
                    ->directory('questions')
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('content')
                    ->label('Pertanyaan')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('option_a')
                    ->label('Pilihan A')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('option_b')
                    ->label('Pilihan B')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('option_c')
                    ->label('Pilihan C')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('option_d')
                    ->label('Pilihan D')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('option_e')
                    ->label('Pilihan E')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('correct_option')
                    ->label('Jawaban Benar')
                    ->options([
                        'A' => 'A',
                        'B' => 'B',
                        'C' => 'C',
                        'D' => 'D',
                        'E' => 'E',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('explanation')
                    ->label('Pembahasan Singkat')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Gambar')
                    ->circular(),
                Tables\Columns\TextColumn::make('content')
                    ->label('Pertanyaan')
                    ->limit(50)
                    ->html(),
                Tables\Columns\TextColumn::make('correct_option')
                    ->label('Jawaban Benar')
                    ->badge()
                    ->color('success'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                \Filament\Actions\CreateAction::make()->label('Tambah Soal Baru'),
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
