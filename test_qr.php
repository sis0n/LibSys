<?php
require __DIR__ . '/vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

$qrCode = new QrCode('Hello, this is a sample QR code!');
$writer = new PngWriter();
$result = $writer->write($qrCode);

header('Content-Type: '.$result->getMimeType());
echo $result->getString();
