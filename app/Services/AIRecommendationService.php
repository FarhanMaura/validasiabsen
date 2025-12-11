<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIRecommendationService
{
    private string $apiKey;
    private string $model;
    private string $provider;

    public function __construct()
    {
        $this->provider = config('services.ai.provider', 'openai');
        $this->apiKey = config('services.ai.api_key', '');
        $this->model = config('services.ai.model', 'gpt-3.5-turbo');
    }

    /**
     * Generate AI recommendations based on attendance data
     *
     * @param array $data Attendance analytics data
     * @return array
     */
    public function generateRecommendations(array $data): array
    {
        if (empty($this->apiKey)) {
            return $this->getFallbackRecommendations($data);
        }

        try {
            $prompt = $this->buildPrompt($data);
            
            if ($this->provider === 'openai') {
                return $this->getOpenAIRecommendations($prompt);
            }
            
            // Fallback if provider not supported
            return $this->getFallbackRecommendations($data);
            
        } catch (\Exception $e) {
            Log::error('AI Recommendation Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            
            return $this->getFallbackRecommendations($data);
        }
    }

    /**
     * Build prompt for AI based on attendance data
     */
    private function buildPrompt(array $data): string
    {
        $prompt = "Sebagai konsultan pendidikan, berikan rekomendasi untuk meningkatkan kedisiplinan siswa berdasarkan data berikut:\n\n";
        
        $prompt .= "STATISTIK KEHADIRAN:\n";
        $prompt .= "- Total Siswa: {$data['total_siswa']}\n";
        $prompt .= "- Kehadiran Hari Ini: {$data['hadir_hari_ini']} siswa\n";
        $prompt .= "- Rata-rata Kehadiran Bulanan: {$data['rata_rata_kehadiran']}%\n";
        $prompt .= "- Total Keterlambatan Bulan Ini: {$data['total_terlambat']}\n\n";
        
        if (!empty($data['siswa_sering_terlambat'])) {
            $prompt .= "SISWA YANG SERING TERLAMBAT:\n";
            foreach ($data['siswa_sering_terlambat'] as $siswa) {
                $prompt .= "- {$siswa['nama']} ({$siswa['kelas']}): {$siswa['jumlah_terlambat']} kali\n";
            }
            $prompt .= "\n";
        }
        
        if (!empty($data['kelas_stats'])) {
            $prompt .= "STATISTIK PER KELAS:\n";
            foreach ($data['kelas_stats'] as $kelas) {
                $prompt .= "- {$kelas['nama_kelas']}: Kehadiran {$kelas['persentase_hadir']}%, Terlambat {$kelas['total_terlambat']} siswa\n";
            }
            $prompt .= "\n";
        }
        
        $prompt .= "Berikan 5 rekomendasi konkret dan actionable dalam format:\n";
        $prompt .= "1. [Judul Rekomendasi]\n   Penjelasan singkat dan langkah implementasi.\n\n";
        $prompt .= "Fokus pada solusi praktis yang dapat diterapkan kepala sekolah.";
        
        return $prompt;
    }

    /**
     * Get recommendations from OpenAI
     */
    private function getOpenAIRecommendations(string $prompt): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Anda adalah konsultan pendidikan yang ahli dalam meningkatkan kedisiplinan dan kehadiran siswa di sekolah.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'temperature' => 0.7,
            'max_tokens' => 1000,
        ]);

        if ($response->successful()) {
            $result = $response->json();
            $recommendations = $result['choices'][0]['message']['content'] ?? '';
            
            return [
                'success' => true,
                'recommendations' => $recommendations,
                'source' => 'AI (OpenAI)',
            ];
        }

        throw new \Exception('OpenAI API request failed: ' . $response->body());
    }

    /**
     * Get fallback recommendations when AI is not available
     */
    private function getFallbackRecommendations(array $data): array
    {
        $recommendations = [];
        
        // Recommendation based on late students
        if (!empty($data['siswa_sering_terlambat'])) {
            $count = count($data['siswa_sering_terlambat']);
            $recommendations[] = "**1. Program Pendampingan Siswa Terlambat**\n   Terdapat {$count} siswa yang sering terlambat. Lakukan konseling individual untuk memahami penyebab keterlambatan dan berikan solusi yang sesuai (misalnya: koordinasi dengan orang tua, penyesuaian jadwal transportasi).";
        }
        
        // Recommendation based on attendance rate
        if ($data['rata_rata_kehadiran'] < 85) {
            $recommendations[] = "**2. Peningkatan Monitoring Kehadiran**\n   Rata-rata kehadiran {$data['rata_rata_kehadiran']}% masih di bawah standar. Implementasikan sistem reward untuk kelas dengan kehadiran terbaik dan komunikasi rutin dengan orang tua siswa yang sering tidak hadir.";
        } else {
            $recommendations[] = "**2. Pertahankan Kehadiran yang Baik**\n   Rata-rata kehadiran {$data['rata_rata_kehadiran']}% sudah baik. Pertahankan dengan memberikan apresiasi kepada siswa dan kelas dengan kehadiran sempurna.";
        }
        
        // Recommendation for late arrivals
        if ($data['total_terlambat'] > 10) {
            $recommendations[] = "**3. Evaluasi Jam Masuk Sekolah**\n   Dengan {$data['total_terlambat']} keterlambatan bulan ini, pertimbangkan untuk mengevaluasi jam masuk sekolah atau mengidentifikasi kendala transportasi yang dihadapi siswa.";
        }
        
        // Class-specific recommendations
        if (!empty($data['kelas_stats'])) {
            $worstClass = collect($data['kelas_stats'])->sortBy('persentase_hadir')->first();
            if ($worstClass && $worstClass['persentase_hadir'] < 80) {
                $recommendations[] = "**4. Fokus pada Kelas {$worstClass['nama_kelas']}**\n   Kelas ini memiliki tingkat kehadiran terendah ({$worstClass['persentase_hadir']}%). Lakukan pertemuan dengan wali kelas untuk mengidentifikasi masalah dan membuat action plan perbaikan.";
            }
        }
        
        // General recommendation
        $recommendations[] = "**5. Sistem Notifikasi Otomatis**\n   Pastikan sistem notifikasi WhatsApp kepada orang tua berjalan optimal. Notifikasi real-time dapat meningkatkan awareness orang tua terhadap kehadiran anak mereka.";
        
        $text = implode("\n\n", $recommendations);
        
        return [
            'success' => true,
            'recommendations' => $text,
            'source' => 'Rule-based (AI tidak tersedia)',
        ];
    }
}
