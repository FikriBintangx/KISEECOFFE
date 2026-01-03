<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * QRIS Generator Library
 * Generate dynamic QRIS QR Code sesuai standar EMV QR Code Indonesia
 */
class Qris_generator {
    
    private $merchant_name = 'KIISECOFFEE';
    private $merchant_city = 'JAKARTA';
    private $merchant_category_code = '5812'; // Makanan & Minuman
    private $country_code = 'ID';
    private $currency_code = '360'; // IDR
    private $merchant_account = '901495146739'; // Nomor rekening/merchant ID
    
    /**
     * Generate QRIS EMV QR Code string
     * Format sesuai standar EMV QR Code untuk QRIS Indonesia
     */
    public function generate_qris_string($amount) {
        // Format amount dengan 2 desimal
        $amount_formatted = number_format($amount, 2, '.', '');
        
        // Build EMV QR Code string
        $qr_string = '';
        
        // ID 00: Payload Format Indicator (01 = EMV QR Code)
        $qr_string .= $this->build_tlv('00', '01');
        
        // ID 01: Point of Initiation Method (12 = Dynamic QR Code)
        $qr_string .= $this->build_tlv('01', '12');
        
        // ID 26: Merchant Account Information
        $merchant_info = '';
        // ID 00: GUID (Global Unique Identifier) - Merchant ID
        $merchant_info .= $this->build_tlv('00', 'ID.CO.QRIS.WWW');
        // ID 01-03: Merchant ID (bisa menggunakan nomor rekening atau merchant ID)
        $merchant_info .= $this->build_tlv('01', $this->merchant_account);
        // ID 02: Merchant Name (optional)
        $merchant_info .= $this->build_tlv('02', $this->merchant_name);
        
        $qr_string .= $this->build_tlv('26', $merchant_info);
        
        // ID 52: Merchant Category Code
        $qr_string .= $this->build_tlv('52', $this->merchant_category_code);
        
        // ID 53: Transaction Currency
        $qr_string .= $this->build_tlv('53', $this->currency_code);
        
        // ID 54: Transaction Amount (Dynamic)
        $qr_string .= $this->build_tlv('54', $amount_formatted);
        
        // ID 58: Country Code
        $qr_string .= $this->build_tlv('58', $this->country_code);
        
        // ID 59: Merchant Name
        $qr_string .= $this->build_tlv('59', $this->merchant_name);
        
        // ID 60: Merchant City
        $qr_string .= $this->build_tlv('60', $this->merchant_city);
        
        // ID 62: Additional Data Field Template (Optional)
        // ID 01: Bill Number (bisa menggunakan kode transaksi)
        // $additional_data = $this->build_tlv('01', $transaction_code);
        // $qr_string .= $this->build_tlv('62', $additional_data);
        
        return $qr_string;
    }
    
    /**
     * Build TLV (Tag-Length-Value) format
     */
    private function build_tlv($tag, $value) {
        $length = str_pad(strlen($value), 2, '0', STR_PAD_LEFT);
        return $tag . $length . $value;
    }
    
    /**
     * Generate QR Code image using phpqrcode library
     * Returns base64 encoded image or file path
     */
    public function generate_qr_image($amount, $size = 300, $output_file = null) {
        $qr_string = $this->generate_qris_string($amount);
        
        // Check if phpqrcode library exists
        $qrcode_path = APPPATH . '../assets/third_party/phpqrcode/qrlib.php';
        
        if (file_exists($qrcode_path)) {
            require_once $qrcode_path;
            
            if ($output_file) {
                // Save to file
                QRcode::png($qr_string, $output_file, QR_ECLEVEL_M, $size / 25, 2);
                return $output_file;
            } else {
                // Return base64
                ob_start();
                QRcode::png($qr_string, null, QR_ECLEVEL_M, $size / 25, 2);
                $image_data = ob_get_contents();
                ob_end_clean();
                return 'data:image/png;base64,' . base64_encode($image_data);
            }
        } else {
            // Fallback: Use online QR code API or return QR string
            // Using qr-server.com API as fallback
            $qr_url = 'https://api.qrserver.com/v1/create-qr-code/?size=' . $size . 'x' . $size . '&data=' . urlencode($qr_string);
            return $qr_url;
        }
    }
    
    /**
     * Generate QR Code using Google Charts API (fallback)
     */
    public function generate_qr_google($amount, $size = 300) {
        $qr_string = $this->generate_qris_string($amount);
        $encoded = urlencode($qr_string);
        // Menggunakan qr-server.com yang lebih reliable
        return "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data=" . $encoded . "&format=png";
    }
    
    /**
     * Get QR Code data URL for direct embedding
     */
    public function get_qr_data_url($amount, $size = 300) {
        return $this->generate_qr_image($amount, $size);
    }
    
    /**
     * Set merchant account (rekening)
     */
    public function set_merchant_account($account) {
        $this->merchant_account = $account;
    }
    
    /**
     * Set merchant name
     */
    public function set_merchant_name($name) {
        $this->merchant_name = $name;
    }
    
    /**
     * Set merchant city
     */
    public function set_merchant_city($city) {
        $this->merchant_city = $city;
    }
}

