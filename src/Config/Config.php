<?php

/**
 * config.php
 * * Naglo-load ng environment variables mula sa .env file.
 */

// 1. Magsimula ng Session kung wala pa
// Mahalaga ito kung gagamitin mo ang $_SESSION sa iyong application.
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// 2. Simple function para mag-load ng .env file
function loadEnv($filePath)
{
  // Tiyakin na mayroon ang .env file
  if (!file_exists($filePath)) {
    return;
  }

  // Basahin ang buong content ng file
  $content = file_get_contents($filePath);
  if ($content === false) {
    return;
  }

  // Hatiin ang content sa bawat linya
  $lines = explode("\n", $content);

  foreach ($lines as $line) {
    $trimmed_line = trim($line);

    // Laktawan ang mga walang laman na linya at mga comment (#)
    if (empty($trimmed_line) || strpos($trimmed_line, '#') === 0) {
      continue;
    }

    // Hatiin ang linya sa 'name' at 'value'
    list($name, $value) = explode('=', $trimmed_line, 2);

    $name = trim($name);
    // Alisin ang whitespace at ang anumang quotes (single o double) sa value
    $value = trim($value, " \t\n\r\0\x0B\"'");

    // I-set ang variable sa environment
    if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
      putenv(sprintf('%s=%s', $name, $value));
      $_ENV[$name] = $value;
      $_SERVER[$name] = $value;
    }
  }
}

// 3. I-load ang .env file
// Siguraduhin na ang '/.env' ay tama ang path base sa kung nasaan ang config.php
loadEnv(__DIR__ . '/.env');

// 4. I-define ang APP_BASE_PATH bilang PHP Constant
// Ginagamit ito para maging accessible sa buong app mo ang base path
if (getenv('APP_BASE_PATH')) {
  define('APP_BASE_PATH', getenv('APP_BASE_PATH'));
} else {
  // Optional: Mag-set ng default value kung wala sa .env
  // define('APP_BASE_PATH', '/'); 
}
