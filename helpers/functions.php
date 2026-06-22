<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function url(string $path = ''): string
{
    return BASE_URL . ltrim($path, '/');
}

function redirect(string $path): void
{
    header('Location: ' . url($path));
    exit;
}

function set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function get_flash(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function rupiah($angka): string
{
    return 'Rp' . number_format((float) $angka, 0, ',', '.');
}

function tanggal_indonesia($tanggal): string
{
    if (!$tanggal) {
        return '-';
    }

    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    $time = strtotime($tanggal);
    return date('d', $time) . ' ' . $bulan[(int) date('n', $time)] . ' ' . date('Y', $time);
}

function badge_status(string $status): string
{
    return match ($status) {
        'Dibayar' => 'success',
        'Dibatalkan' => 'danger',
        default => 'warning',
    };
}

function generate_booking_code(): string
{
    return 'BK-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
}
