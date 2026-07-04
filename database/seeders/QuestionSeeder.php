<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Subject;
use App\Models\Question;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $subject = Subject::create([
            'name' => 'OSN IPA SMP Tingkat Kabupaten',
            'description' => 'Kumpulan Soal OSN IPA SMP Tahun 2025 Tingkat Kabupaten'
        ]);

        Question::create([
            'subject_id' => $subject->id,
            'content' => 'Eksperimen yang menjelaskan asal usul kehidupan yang diujicobakan oleh Harold Urey adalah….',
            'option_a' => 'Membandingkan struktur tubuh manusia dan primata modern',
            'option_b' => 'Menyintesis senyawa organik dari gas-gas yang menyerupai atmosfer purba',
            'option_c' => 'Mengamati pembelahan sel bakteri di laboratorium',
            'option_d' => 'Membandingkan fosil-fosil manusia purba di lapisan batuan terdalam',
            'option_e' => '-',
            'correct_option' => 'B',
            'explanation' => 'Bersama Stanley Miller, Urey melakukan percobaan dengan menciptakan atmosfer buatan yang terdiri atas metana, amonia, hidrogen, dan uap air, lalu diberi muatan listrik untuk mensimulasikan petir di Bumi awal. Hasilnya, terbentuk senyawa organik seperti asam amino yang merupakan komponen dasar kehidupan, sehingga eksperimen ini mendukung teori abiogenesis—bahwa kehidupan bisa muncul dari zat-zat tak hidup melalui proses kimia. Pilihan lainnya tidak tepat karena lebih berkaitan dengan evolusi manusia atau pengamatan biologis, bukan asal usul kehidupan secara kimiawi.'
        ]);

        Question::create([
            'subject_id' => $subject->id,
            'content' => 'Di padang rumput, interaksi antara makhluk hidup dan lingkungannya berlangsung secara kompleks. Manakah dari pernyataan berikut yang menunjukkan interaksi biotik dengan abiotik?',
            'option_a' => 'Rumput menyerap air dari tanah untuk fotosintesis.',
            'option_b' => 'Dua ekor banteng jantan bertarung untuk memperebutkan betina.',
            'option_c' => 'Kelembapan udara menurun akibat peningkatan suhu siang hari.',
            'option_d' => 'Ular memangsa tikus yang melintas di rerumputan.',
            'option_e' => '-',
            'correct_option' => 'A',
            'explanation' => 'Karena rumput (makhluk hidup/komponen biotik) berinteraksi dengan air dan tanah (komponen abiotik). Pilihan B dan D merupakan interaksi antar makhluk hidup (biotik dengan biotik), sedangkan pilihan C adalah interaksi antara dua komponen abiotik (kelembapan dan suhu udara), bukan melibatkan makhluk hidup.'
        ]);

        Question::create([
            'subject_id' => $subject->id,
            'content' => "Di sebuah taman nasional, terdapat beragam jenis tumbuhan dan hewan serta berbagai tipe habitat, mulai dari hutan hujan, padang rumput, hingga sungai. Perhatikan pernyataan berikut:\n1. Populasi jagung di lahan pertanian memiliki berbagai varietas dengan sifat tahan kekeringan dan tahan hama.\n2. Berbagai tipe habitat seperti hutan, sungai, dan rawa memberikan kondisi lingkungan yang berbeda bagi makhluk hidup.\n3. Di taman nasional tersebut ditemukan lebih dari 200 jenis burung yang berbeda.\nPernyataan manakah yang menunjukkan keanekaragaman pada tingkat gen, spesies, dan ekosistem secara berurutan?",
            'option_a' => '1, 2, 3',
            'option_b' => '2, 3, 1',
            'option_c' => '3, 2, 1',
            'option_d' => '1, 3, 2',
            'option_e' => '-',
            'correct_option' => 'D',
            'explanation' => 'Keanekaragaman hayati terdiri atas tiga tingkat, yaitu genetik, spesies, dan ekosistem. Pernyataan nomor 1 menunjukkan keanekaragaman genetik. Pernyataan nomor 3 menggambarkan keanekaragaman spesies. Sedangkan pernyataan nomor 2 menunjukkan keanekaragaman ekosistem.'
        ]);
    }
}
