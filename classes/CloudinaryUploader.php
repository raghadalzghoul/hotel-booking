
<?php

class CloudinaryUploader
{
    private $config;

    public function __construct()
    {
        $this->config = require __DIR__ . "/../config/cloudinary.php";
    }

    public function uploadImage($file)
    {
        // Check upload errors
        if (
            !isset($file["error"]) ||
            $file["error"] !== UPLOAD_ERR_OK
        ) {
            throw new Exception("Image upload failed.");
        }

        // Maximum size: 5 MB
        if ($file["size"] > 5 * 1024 * 1024) {
            throw new Exception("Image must be smaller than 5 MB.");
        }

        if (!is_uploaded_file($file["tmp_name"])) {
            throw new Exception("Invalid uploaded file.");
        }

        // Validate actual image MIME type
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file["tmp_name"]);

        $allowedTypes = [
            "image/jpeg",
            "image/png",
            "image/webp"
        ];

        if (!in_array($mime, $allowedTypes, true)) {
            throw new Exception("Only JPG, PNG and WebP images are allowed.");
        }

        $cloudName = $this->config["cloud_name"];
        $apiKey = $this->config["api_key"];
        $apiSecret = $this->config["api_secret"];

        if (!$cloudName || !$apiKey || !$apiSecret) {
            throw new Exception("Cloudinary configuration is missing.");
        }

        // Parameters used for the Cloudinary signature
        $timestamp = time();
        $folder = "hotel-booking";

        $signatureString = "folder={$folder}&timestamp={$timestamp}{$apiSecret}";
        $signature = sha1($signatureString);

        $url = "https://api.cloudinary.com/v1_1/"
            . rawurlencode($cloudName)
            . "/image/upload";

        // Prepare image for upload
        $postData = [
            "file" => new CURLFile(
                $file["tmp_name"],
                $mime,
                basename($file["name"])
            ),
            "api_key" => $apiKey,
            "timestamp" => $timestamp,
            "folder" => $folder,
            "signature" => $signature
        ];

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 30
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);

        curl_close($ch);

        if ($response === false) {
            error_log("Cloudinary cURL error: " . $curlError);
            throw new Exception("Unable to upload image.");
        }

        $result = json_decode($response, true);

       
if (
    $httpCode !== 200 ||
    !is_array($result) ||
    empty($result["secure_url"])
) {
    error_log("Cloudinary upload failed: " . $response);
    throw new Exception("Cloudinary image upload failed.");
}

        return $result["secure_url"];
    }
}
