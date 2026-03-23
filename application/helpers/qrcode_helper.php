<?php
/**
 * QR Code Helper
 * 
 * Generates QR codes for shipping labels
 */

if (!function_exists('generate_qr_code')) {
    /**
     * Generate QR code as base64 image
     * 
     * @param string $data The data to encode in QR code
     * @param int $size Size of the QR code (default: 200)
     * @return string Base64 encoded image data URI
     */
    function generate_qr_code($data, $size = 200) {
        // Use Google Charts API to generate QR code (simple), but fetch robustly (allow_url_fopen often disabled on live)
        $encoded_data = rawurlencode($data);
        $qr_url = "https://chart.googleapis.com/chart?chs={$size}x{$size}&cht=qr&chl={$encoded_data}";

        $image_data = _qr_fetch_url($qr_url);

        if ($image_data !== false && $image_data !== null && $image_data !== '') {
            return 'data:image/png;base64,' . base64_encode($image_data);
        }

        // Fallback: return empty string if generation fails
        return '';
    }
}

if (!function_exists('_qr_fetch_url')) {
    /**
     * Fetch URL content using file_get_contents or cURL (with timeouts).
     *
     * @param string $url
     * @return string|false
     */
    function _qr_fetch_url($url) {
        // 1) Try file_get_contents if allowed
        $allow = ini_get('allow_url_fopen');
        if ($allow) {
            $ctx = stream_context_create([
                'http' => ['timeout' => 5],
                'https' => ['timeout' => 5],
            ]);
            $data = @file_get_contents($url, false, $ctx);
            if ($data !== false) {
                return $data;
            }
        }

        // 2) Try cURL
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 8);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            $data = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($data !== false && (int)$httpCode >= 200 && (int)$httpCode < 300) {
                return $data;
            }
        }

        return false;
    }
}

if (!function_exists('generate_qr_code_file')) {
    /**
     * Generate QR code and save to file
     * 
     * @param string $data The data to encode in QR code
     * @param string $file_path Full path where to save the QR code
     * @param int $size Size of the QR code (default: 200)
     * @return bool True on success, false on failure
     */
    function generate_qr_code_file($data, $file_path, $size = 200) {
        // Use Google Charts API to generate QR code
        $encoded_data = rawurlencode($data);
        $qr_url = "https://chart.googleapis.com/chart?chs={$size}x{$size}&cht=qr&chl={$encoded_data}";
        
        // Fetch the QR code image
        $image_data = _qr_fetch_url($qr_url);
        
        if ($image_data !== false) {
            // Save to file
            return file_put_contents($file_path, $image_data) !== false;
        }
        
        return false;
    }
}

