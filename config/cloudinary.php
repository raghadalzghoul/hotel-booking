
<?php

$envPath = __DIR__ . "/../.env";

if (!is_readable($envPath)) {
    throw new RuntimeException("Environment file not found.");
}

$env = parse_ini_file($envPath, false, INI_SCANNER_RAW);

if ($env === false) {
    throw new RuntimeException("Unable to read environment file.");
}

return [
    "cloud_name" => $env["CLOUDINARY_CLOUD_NAME"] ?? "",
    "api_key" => $env["CLOUDINARY_API_KEY"] ?? "",
    "api_secret" => $env["CLOUDINARY_API_SECRET"] ?? ""
];
