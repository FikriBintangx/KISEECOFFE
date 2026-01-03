<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Excel_export
{
    private $ci;

    public function __construct()
    {
        $this->ci = &get_instance();
    }

    /**
     * Export data ke Excel (CSV format)
     */
    public function export_csv($filename, $data, $headers = [])
    {
        // Set headers
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Create output stream
        $output = fopen('php://output', 'w');

        // Add BOM for UTF-8
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // Add headers if provided
        if (!empty($headers)) {
            fputcsv($output, $headers);
        }

        // Add data rows
        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }

    /**
     * Export data ke Excel dengan format yang lebih baik
     */
    public function export_excel($filename, $data, $headers = [], $title = '')
    {
        // Set headers untuk Excel
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '.xls"');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo '<html><head><meta charset="utf-8"></head><body>';
        echo '<table border="1">';

        // Add title if provided
        if (!empty($title)) {
            echo '<tr><th colspan="' . count($headers) . '" style="background-color:#4CAF50;color:white;font-size:16px;padding:10px;">' . $title . '</th></tr>';
        }

        // Add headers
        if (!empty($headers)) {
            echo '<tr>';
            foreach ($headers as $header) {
                echo '<th style="background-color:#2196F3;color:white;padding:8px;font-weight:bold;">' . htmlspecialchars($header) . '</th>';
            }
            echo '</tr>';
        }

        // Add data rows
        foreach ($data as $row) {
            echo '<tr>';
            foreach ($row as $cell) {
                echo '<td style="padding:5px;">' . htmlspecialchars($cell) . '</td>';
            }
            echo '</tr>';
        }

        echo '</table>';
        echo '</body></html>';
        exit;
    }
}


