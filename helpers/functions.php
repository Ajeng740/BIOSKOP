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

function sanitize_input(string $value): string
{
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

function normalize_seat_ids(array $seatIds): array
{
    $seatIds = array_map('intval', $seatIds);
    $seatIds = array_filter($seatIds, fn($id) => $id > 0);
    return array_values(array_unique($seatIds));
}

function validate_booking_data(array $data, array &$errors): bool
{
    $jadwalId = isset($data['jadwal_id']) ? (int) $data['jadwal_id'] : 0;
    $kursi = $data['kursi'] ?? [];

    if ($jadwalId <= 0) {
        $errors['jadwal_id'] = 'Jadwal tidak valid.';
    }

    if (empty($kursi) || !is_array($kursi)) {
        $errors['kursi'] = 'Pilih minimal satu kursi.';
    } else {
        $kursi = normalize_seat_ids($kursi);
        if (count($kursi) === 0) {
            $errors['kursi'] = 'Pilih minimal satu kursi yang valid.';
        }
    }

    return empty($errors);
}
