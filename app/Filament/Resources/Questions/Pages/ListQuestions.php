<?php

namespace App\Filament\Resources\Questions\Pages;

use App\Filament\Resources\Questions\QuestionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListQuestions extends ListRecords
{
    protected static string $resource = QuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('deleteAll')
                ->label('Hapus Semua Soal')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Hapus Seluruh Data Soal')
                ->modalDescription('Apakah Anda yakin ingin menghapus SEMUA soal yang ada di database? Tindakan ini tidak dapat dibatalkan.')
                ->modalSubmitActionLabel('Ya, Hapus Semua')
                ->action(function () {
                    \App\Models\Question::query()->delete();
                    \Filament\Notifications\Notification::make()
                        ->title('Semua soal berhasil dihapus!')
                        ->success()
                        ->send();
                }),
            \Filament\Actions\Action::make('importSoal')
                ->label('Import Soal (PDF / TXT)')
                ->icon('heroicon-o-document-arrow-up')
                ->color('success')
                ->form([
                    \Filament\Forms\Components\Select::make('subject_id')
                        ->label('Pilih Mata Pelajaran')
                        ->options(\App\Models\Subject::pluck('name', 'id'))
                        ->required(),
                    \Filament\Forms\Components\FileUpload::make('file_soal')
                        ->label('File Soal (.pdf, .txt, .docx, .doc)')
                        ->acceptedFileTypes(['application/pdf', 'text/plain', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/msword'])
                        ->disk('local')
                        ->directory('imports')
                        ->required(),
                ])
                ->action(function (array $data) {
                    $path = storage_path('app/private/' . $data['file_soal']);
                    $extension = pathinfo($path, PATHINFO_EXTENSION);
                    
                    if (strtolower($extension) === 'txt') {
                        $parser = new \App\Services\TxtQuestionParser();
                    } elseif (strtolower($extension) === 'docx' || strtolower($extension) === 'doc') {
                        $parser = new \App\Services\DocxQuestionParser();
                    } else {
                        $parser = new \App\Services\PdfQuestionParser();
                    }
                    
                    try {
                        $count = $parser->parseAndImport($path, $data['subject_id']);
                        \Filament\Notifications\Notification::make()
                            ->title("Berhasil mengimpor $count soal!")
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        \Filament\Notifications\Notification::make()
                            ->title('Gagal mengimpor file: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            CreateAction::make(),
        ];
    }
}
