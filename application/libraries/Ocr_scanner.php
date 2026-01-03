<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * OCR Scanner Library
 * Untuk membaca nominal dari gambar bukti transfer
 */
class Ocr_scanner
{
    private $ci;
    private $tesseract_path;
    private $use_api;
    private $api_key;

    public function __construct()
    {
        $this->ci = &get_instance();
        
        // Cek apakah Tesseract tersedia
        $this->tesseract_path = $this->find_tesseract();
        
        // Setup API (jika menggunakan Google Cloud Vision)
        $this->use_api = false; // Set true jika ingin menggunakan API
        $this->api_key = ''; // Set API key jika menggunakan API
    }

    /**
     * Cari path Tesseract OCR
     */
    private function find_tesseract()
    {
        // Windows paths
        $windows_paths = [
            'C:\\Program Files\\Tesseract-OCR\\tesseract.exe',
            'C:\\Program Files (x86)\\Tesseract-OCR\\tesseract.exe',
            'C:\\Program Files\\Tesseract-OCR\\tesseract.exe',
            'C:\\Program Files (x86)\\Tesseract-OCR\\tesseract.exe',
            // 'C:\\xampp\\tesseract\\tesseract.exe', // REMOVED FOR COMPLIANCE
        ];

        // Cek di Windows
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            foreach ($windows_paths as $path) {
                if (file_exists($path)) {
                    return $path;
                }
            }
        }

        // Cek di system PATH
        $tesseract = shell_exec('which tesseract 2>nul') ?: shell_exec('where tesseract 2>nul');
        if ($tesseract) {
            return trim($tesseract);
        }

        return null;
    }

    /**
     * Scan nominal dari gambar menggunakan Tesseract OCR
     */
    public function scan_with_tesseract($image_path)
    {
        if (!$this->tesseract_path) {
            return false;
        }

        // Buat file output temporary
        $output_file = sys_get_temp_dir() . '/ocr_output_' . time() . '.txt';
        $output_path = $output_file;

        // Escape paths untuk Windows
        $image_path_escaped = escapeshellarg($image_path);
        $output_path_escaped = escapeshellarg($output_path);

        // Jalankan Tesseract
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $command = '"' . $this->tesseract_path . '" ' . $image_path_escaped . ' ' . $output_path_escaped . ' 2>&1';
        } else {
            $command = $this->tesseract_path . ' ' . $image_path_escaped . ' ' . $output_path_escaped . ' 2>&1';
        }

        exec($command, $output, $return_var);

        // Baca hasil
        if ($return_var == 0 && file_exists($output_file . '.txt')) {
            $text = file_get_contents($output_file . '.txt');
            unlink($output_file . '.txt');

            // Extract nominal
            $nominal = $this->extract_nominal($text);
            return $nominal;
        }

        return false;
    }

    /**
     * Scan nominal menggunakan Google Cloud Vision API
     */
    public function scan_with_vision_api($image_path)
    {
        if (!$this->use_api || empty($this->api_key)) {
            return false;
        }

        // Encode image to base64
        $image_data = file_get_contents($image_path);
        $base64_image = base64_encode($image_data);

        // Prepare request
        $url = 'https://vision.googleapis.com/v1/images:annotate?key=' . $this->api_key;
        $data = [
            'requests' => [
                [
                    'image' => [
                        'content' => $base64_image
                    ],
                    'features' => [
                        [
                            'type' => 'TEXT_DETECTION'
                        ]
                    ]
                ]
            ]
        ];

        // Send request
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code == 200) {
            $result = json_decode($response, true);
            if (isset($result['responses'][0]['textAnnotations'][0]['description'])) {
                $text = $result['responses'][0]['textAnnotations'][0]['description'];
                $nominal = $this->extract_nominal($text);
                return $nominal;
            }
        }

        return false;
    }

    /**
     * Extract nominal dari teks yang di-scan
     */
    private function extract_nominal($text)
    {
        // Patterns untuk mencari nominal
        $patterns = [
            '/Rp\s*([\d.,]+)/i',
            '/([\d.,]+)\s*rupiah/i',
            '/transfer\s*([\d.,]+)/i',
            '/jumlah\s*([\d.,]+)/i',
            '/total\s*([\d.,]+)/i',
        ];

        $nominal = null;

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                $nominal_str = $matches[1];
                // Hapus semua karakter non-digit kecuali titik dan koma
                $nominal_str = preg_replace('/[^0-9.,]/', '', $nominal_str);
                // Hapus titik (ribuan separator) dan ganti koma dengan titik
                $nominal_str = str_replace('.', '', $nominal_str);
                $nominal_str = str_replace(',', '.', $nominal_str);
                $nominal = (int) $nominal_str;
                
                if ($nominal > 0) {
                    return $nominal;
                }
            }
        }

        // Fallback: cari semua angka dalam teks
        preg_match_all('/\d+/', $text, $numbers);
        if (!empty($numbers[0])) {
            // Ambil angka terbesar (kemungkinan nominal)
            $numbers = array_map('intval', $numbers[0]);
            $max_number = max($numbers);
            
            // Filter angka yang masuk akal (minimal 1000)
            if ($max_number >= 1000) {
                return $max_number;
            }
        }

        return false;
    }

    /**
     * Scan nominal dari gambar (auto-detect method)
     */
    public function scan_nominal($image_path, $expected_amount = null)
    {
        // Coba dengan Tesseract dulu
        $nominal = $this->scan_with_tesseract($image_path);
        
        if ($nominal === false && $this->use_api) {
            // Fallback ke Vision API
            $nominal = $this->scan_with_vision_api($image_path);
        }

        return $nominal;
    }

    /**
     * Cek apakah OCR tersedia
     */
    public function is_available()
    {
        return $this->tesseract_path !== null || $this->use_api;
    }
}


