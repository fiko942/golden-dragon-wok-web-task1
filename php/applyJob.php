<?php
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

header('Content-Type: application/json; charset=utf-8');

session_start();

function respond(bool $success, string $message, int $status = 200): void
{
    http_response_code($status);
    echo json_encode(['success' => $success, 'message' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}

function logMessage(string $message): void
{
    $logDir = __DIR__ . '/logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0775, true);
    }
    $line = sprintf("[%s] %s\n", date('c'), $message);
    file_put_contents($logDir . '/apply.log', $line, FILE_APPEND | LOCK_EX);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, 'Metode tidak diperbolehkan.', 405);
}

if (!isset($_SESSION['csrf_token'], $_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], (string) $_POST['csrf_token'])) {
    respond(false, 'Token keamanan tidak valid.', 403);
}

$now = time();
if (isset($_SESSION['last_submission']) && ($now - (int) $_SESSION['last_submission']) < 30) {
    respond(false, 'Silakan tunggu sebelum mengirim ulang formulir.', 429);
}

if (!isset($_SESSION['captcha_answer'])) {
    respond(false, 'Captcha tidak ditemukan. Muat ulang halaman.', 400);
}

$captchaAnswer = (int) ($_POST['captcha_answer'] ?? 0);
if ($captchaAnswer !== (int) $_SESSION['captcha_answer']) {
    respond(false, 'Jawaban captcha tidak sesuai.', 400);
}

$position = trim((string) ($_POST['position'] ?? ''));
$fullname = trim((string) ($_POST['fullname'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$phone = trim((string) ($_POST['phone'] ?? ''));
$experience = trim((string) ($_POST['experience'] ?? ''));
$agree = isset($_POST['agree']);

$allowedPositions = ['Server', 'Cook', 'Barista', 'Cashier', 'Dishwasher'];
if (!in_array($position, $allowedPositions, true)) {
    respond(false, 'Posisi yang dipilih tidak valid.', 400);
}

if ($fullname === '') {
    respond(false, 'Nama lengkap wajib diisi.', 400);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(false, 'Format email tidak valid.', 400);
}

$emailDomain = strtolower(substr(strrchr($email, '@') ?: '', 1));
if ($emailDomain !== 'gmail.com') {
    respond(false, 'Saat ini kami hanya memproses alamat Gmail.', 400);
}

if (!preg_match('/^\+?62\d{8,13}$/', $phone)) {
    respond(false, 'Nomor telepon harus menggunakan format Indonesia.', 400);
}

if (mb_strlen($experience) < 20) {
    respond(false, 'Pengalaman kerja minimal 20 karakter.', 400);
}

if (!$agree) {
    respond(false, 'Mohon setujui persyaratan penggunaan data.', 400);
}

$cvPath = null;
$cvOriginalName = null;

if (!empty($_FILES['cv']['name'])) {
    $file = $_FILES['cv'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        respond(false, 'Gagal mengunggah CV. Coba kembali.', 400);
    }

    if ($file['size'] > 2 * 1024 * 1024) {
        respond(false, 'Ukuran CV melebihi 2MB.', 400);
    }

    $allowedMime = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    if (!in_array($mime, $allowedMime, true)) {
        respond(false, 'Format CV harus PDF atau DOCX.', 400);
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $safeName = 'GDW-CV-' . bin2hex(random_bytes(8)) . '.' . strtolower($extension ?: 'pdf');
    $destination = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $safeName;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        respond(false, 'Tidak dapat menyimpan file sementara.', 500);
    }

    $cvPath = $destination;
    $cvOriginalName = $file['name'];
}

$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    logMessage('Autoload PHPMailer tidak ditemukan.');
    respond(false, 'Server belum siap memproses email. Hubungi kami via email langsung.', 500);
}

require $autoloadPath;

$config = require __DIR__ . '/config-smtp.php';

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = $config['host'];
    $mail->Port = (int) $config['port'];
    $mail->SMTPAuth = true;
    $mail->Username = $config['username'];
    $mail->Password = $config['password'];
    $mail->CharSet = 'UTF-8';
    $secureConfig = $config['secure'] ?? 'tls';
    if ($secureConfig === true) {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    } elseif ($secureConfig === 'tls') {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    } elseif ($secureConfig === 'ssl') {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    } else {
        $mail->SMTPSecure = $secureConfig;
    }

    $mail->setFrom($config['from_email'], $config['from_name']);
    $mail->addAddress($email, $fullname);

    if ($cvPath && $cvOriginalName) {
        $mail->addAttachment($cvPath, $cvOriginalName);
    }

    $mail->isHTML(true);
    $mail->Subject = sprintf('[GDW Careers] Ringkasan Aplikasi %s', $position);

    $body = sprintf(
        '<h2>Halo %s,</h2><p>Terima kasih telah melamar posisi <strong>%s</strong> di Golden Dragon Wok.</p>' .
        '<h3>Ringkasan Aplikasi</h3><ul>' .
        '<li><strong>Nama:</strong> %s</li>' .
        '<li><strong>Email Kontak:</strong> %s</li>' .
        '<li><strong>Telepon:</strong> %s</li>' .
        '</ul><h3>Pengalaman</h3><p>%s</p><p>Tim HR kami akan meninjau aplikasi Anda dan menghubungi melalui email atau WhatsApp jika cocok.</p>',
        htmlspecialchars($fullname, ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($position, ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($fullname, ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($email, ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'),
        nl2br(htmlspecialchars($experience, ENT_QUOTES, 'UTF-8'))
    );
    $mail->Body = $body;
    $mail->AltBody = strip_tags($body);

    $mail->send();

    $_SESSION['last_submission'] = $now;
    unset($_SESSION['captcha_answer']);

    if ($cvPath && file_exists($cvPath)) {
        unlink($cvPath);
    }

    respond(true, 'Ringkasan lamaran berhasil dikirim ke email Gmail Anda.');
} catch (Exception $e) {
    logMessage('Gagal mengirim email: ' . $e->getMessage());
    if ($cvPath && file_exists($cvPath)) {
        unlink($cvPath);
    }
    respond(false, 'Mohon maaf, terjadi kendala saat mengirim email. Coba lagi nanti.', 500);
}
