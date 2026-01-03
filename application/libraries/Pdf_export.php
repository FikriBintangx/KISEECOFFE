<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pdf_export
{
    private $ci;

    public function __construct()
    {
        $this->ci = &get_instance();
    }

    /**
     * Export data ke PDF menggunakan HTML to PDF sederhana
     */
    public function export_pdf($filename, $html_content, $title = '')
    {
        // Cek apakah TCPDF tersedia dan valid
        $tcpdf_path = APPPATH . 'third_party/tcpdf/tcpdf.php';
        $tcpdf_available = false;
        
        if (file_exists($tcpdf_path)) {
            $tcpdf_content = file_get_contents($tcpdf_path);
            // Cek apakah file tidak kosong dan berisi class TCPDF
            if (!empty($tcpdf_content) && strpos($tcpdf_content, 'class TCPDF') !== false) {
                $tcpdf_available = true;
            }
        }
        
        if ($tcpdf_available) {
            try {
                require_once($tcpdf_path);
                
                // Cek apakah class TCPDF sudah terdefinisi
                if (!class_exists('TCPDF')) {
                    throw new Exception('TCPDF class not found');
                }
                
                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                $pdf->SetCreator('KiiseCoffee');
                $pdf->SetAuthor('KiiseCoffee System');
                $pdf->SetTitle($title ?: $filename);
                $pdf->SetSubject('Laporan');
                
                $pdf->setPrintHeader(false);
                $pdf->setPrintFooter(false);
                
                $pdf->AddPage();
                $pdf->writeHTML($html_content, true, false, true, false, '');
                
                $pdf->Output($filename . '.pdf', 'D');
                exit;
            } catch (Exception $e) {
                // Jika TCPDF error, fallback ke HTML
                error_log('TCPDF Error: ' . $e->getMessage());
            }
        }
        
        // Fallback: gunakan HTML dengan print CSS yang lebih baik
        header('Content-Type: text/html; charset=utf-8');
        echo '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . htmlspecialchars($title ?: $filename) . '</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        @page { 
            size: A4; 
            margin: 1.5cm; 
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none !important; }
            .print-break { page-break-after: always; }
        }
        body { 
            font-family: "Arial", "Helvetica", sans-serif; 
            font-size: 11pt; 
            line-height: 1.4;
            color: #000;
            background: #fff;
        }
        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #000;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 24pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .header .subtitle {
            font-size: 10pt;
            color: #666;
        }
        .info {
            margin-bottom: 15px;
            font-size: 10pt;
            color: #333;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 15px;
            margin-bottom: 15px;
            font-size: 10pt;
        }
        th, td { 
            border: 1px solid #333; 
            padding: 8px; 
            text-align: left; 
        }
        th { 
            background-color: #333; 
            color: white; 
            font-weight: bold;
            text-align: center;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .title { 
            font-size: 16pt; 
            font-weight: bold; 
            margin-bottom: 10px;
            color: #000;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #000;
            font-size: 10pt;
            text-align: center;
            color: #666;
        }
        .no-print {
            text-align: center;
            margin: 20px 0;
            padding: 20px;
        }
        .btn-print {
            padding: 12px 30px;
            font-size: 14pt;
            background: #6777ef;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .btn-print:hover {
            background: #5568d3;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.history.back()" class="btn-print" style="background: #95a5a6; margin-right: 10px;">
            <i class="fas fa-arrow-left"></i> Kembali
        </button>
        <button onclick="window.print()" class="btn-print">
            <i class="fas fa-print"></i> Print / Save as PDF
        </button>
        <p style="margin-top: 10px; color: #666;">Gunakan fitur Print browser Anda dan pilih "Save as PDF" untuk menyimpan sebagai PDF</p>
    </div>
    <div class="container">
        <div class="header">
            <h1>' . htmlspecialchars($title ?: 'Laporan') . '</h1>
            <div class="subtitle">KIISECOFFEE - Sistem Manajemen Kafe</div>
        </div>
        <div class="info">
            <strong>Tanggal Cetak:</strong> ' . date('d F Y H:i:s') . '
        </div>
        ' . $html_content . '
        <div class="footer">
            <p>Dicetak oleh: ' . htmlspecialchars($this->ci->session->userdata('email') ?: 'System') . '</p>
            <p>&copy; ' . date('Y') . ' KiiseCoffee. All rights reserved.</p>
        </div>
    </div>
    <script>
        // Auto print jika di-mobile atau jika user ingin
        // window.onload = function() {
        //     setTimeout(function() {
        //         window.print();
        //     }, 500);
        // };
    </script>
</body>
</html>';
        exit;
    }

    /**
     * Generate HTML content untuk tabel laporan
     */
    public function generate_table_html($title, $headers, $data, $footer = '')
    {
        $html = '<div class="title">' . htmlspecialchars($title) . '</div>';
        $html .= '<div class="info">Tanggal Cetak: ' . date('d F Y H:i:s') . '</div>';
        
        $html .= '<table>';
        
        // Headers
        if (!empty($headers)) {
            $html .= '<thead><tr>';
            foreach ($headers as $header) {
                $html .= '<th>' . htmlspecialchars($header) . '</th>';
            }
            $html .= '</tr></thead>';
        }
        
        // Data
        $html .= '<tbody>';
        foreach ($data as $row) {
            $html .= '<tr>';
            foreach ($row as $cell) {
                $html .= '<td>' . htmlspecialchars($cell) . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        
        // Footer
        if (!empty($footer)) {
            $html .= '<tfoot><tr><td colspan="' . count($headers) . '">' . htmlspecialchars($footer) . '</td></tr></tfoot>';
        }
        
        $html .= '</table>';
        
        return $html;
    }
}

