<?php

namespace App\Filament\Resources\Questions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class QuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('subject_id')
                    ->required()
                    ->numeric(),
                Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make('image_path')
                    ->label('Gambar Soal (Opsional)')
                    ->image()
                    ->disk('public')
                    ->directory('question-images')
                    ->columnSpanFull(),
                TextInput::make('option_a')
                    ->required(),
                TextInput::make('option_b')
                    ->required(),
                TextInput::make('option_c')
                    ->required(),
                TextInput::make('option_d')
                    ->required(),
                TextInput::make('option_e')
                    ->required(),
                TextInput::make('correct_option')
                    ->required(),
                Textarea::make('explanation')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
