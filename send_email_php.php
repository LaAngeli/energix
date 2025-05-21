<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=UTF-8');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

header('Content-Type: application/json');

$response = [
    'success' => false,
    'error' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Inițializare PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Activare debugging (opțional, pentru a depana problemele)
        $mail->SMTPDebug = 0; // Nivelul de debug (0 = off, 1 = client messages, 2 = client and server messages, 3 = verbose)
        $mail->Debugoutput = 'html';

        // Configurarea serverului SMTP
        $mail->isSMTP();                                      // Folosește SMTP
        $mail->Host = 'smtp.hostinger.com';                   // Adresa serverului SMTP (Hostinger)
        $mail->SMTPAuth = true;                               // Activează autentificarea SMTP
        $mail->Username = 'contact@energix.md';               // Numele de utilizator SMTP (adresa de email)
        $mail->Password = 'Dima.Sandulescu1';                // Parola pentru email (trebuie înlocuită cu parola reală)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;      // Tipul de criptare (SSL)
        $mail->Port = 465;                                    // Portul SMTP (465 pentru SSL)

        // Setări expeditor și destinatar
        $mail->setFrom('contact@energix.md', 'Energix');   // Adresa de email a expeditorului
        $mail->addAddress('contact@energix.md');             // Adresa destinatarului (înlocuiește cu emailul real)
        
        // Setează codificarea emailului la UTF-8
        $mail->CharSet = 'UTF-8';

        // Conținut email
        $mail->isHTML(true);                                  // Setează emailul în format HTML
        $mail->Subject = 'Mesaj de la ' . htmlspecialchars($_POST['name']);
        $mail->Body    = 'Nume: ' . htmlspecialchars($_POST['name']) . '<br>' .
                         'Prenume: ' . htmlspecialchars($_POST['prenume']) . '<br>' .
                         'Număr de telefon: ' . htmlspecialchars($_POST['phone']) . '<br>' .
                         'Email: ' . htmlspecialchars($_POST['email']) . '<br>' .
                         'Mesaj: ' . nl2br(htmlspecialchars($_POST['message']));

        // Trimiterea emailului
        if ($mail->send()) {
            $response['success'] = true;
        } else {
            $response['error'] = 'Emailul nu a putut fi trimis.';
        }
    } catch (Exception $e) {
        $response['error'] = "Emailul nu a putut fi trimis. Eroare: {$mail->ErrorInfo}";
    }
} else {
    $response['error'] = 'Metodă neacceptată';
}

echo json_encode($response);
?>
