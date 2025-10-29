<?php
require __DIR__ . '/vendor/autoload.php';
use Dompdf\Dompdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Test Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml('<h1>Hello PDF</h1>');
$dompdf->render();
file_put_contents('test.pdf', $dompdf->output());
echo "PDF generated.";

// Test PHPMailer
$mail = new PHPMailer(true);
echo "PHPMailer class works!";
