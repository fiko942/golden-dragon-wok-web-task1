<?php
declare(strict_types=1);

require_once __DIR__ . '/MenuItem.php';
require_once __DIR__ . '/Beverage.php';
require_once __DIR__ . '/MainCourse.php';

use Gdw\Menu\Beverage;
use Gdw\Menu\MainCourse;
use Gdw\Menu\MenuItem;

date_default_timezone_set('Asia/Jakarta');

function rupiah(float $value): string
{
    return 'Rp ' . number_format($value, 0, ',', '.');
}

function formatMenuItemLine(MenuItem $item): string
{
    $tags = $item->tags ? '[' . implode(', ', $item->tags) . ']' : '[]';
    return sprintf(
        'Produk: %s | Disajikan: %s | Harga: %s | Tag: %s',
        $item->name,
        $item->servedIn,
        rupiah((float) $item->price),
        $tags
    );
}

// 1. Membuat MenuItem (Beverage)
$latte = new Beverage('Latte Gumai', 35000);
$latte->servedIn = 'cangkir batu';
$latte->serveWithIce(); // object operator pada method public

// 2. Menggunakan Method pada Beverage
$brewInfo = $latte->brew('kopi Sumatra');
$tastingNote = $latte->tastingNote();

// 3. Inheritance (MainCourse extends MenuItem)
$noodle = new MainCourse('Mie Dragon Wok', 48000);
$noodle->addSide('pangsit goreng');
$noodle->addSide('acar nenas');
$noodle->upgradePortion('mangkuk porsi sharing');
$noodle->applyDiscount(10);

// Menu tambahan untuk daftar
$tea = new Beverage('Teh Rosella', 18000, false);

$daftarMenu = [$latte, $noodle, $tea];

echo "=== DEMO OOP MENU RESTO ===" . PHP_EOL . PHP_EOL;

echo "1. Membuat MenuItem (Beverage):" . PHP_EOL;
echo "   " . formatMenuItemLine($latte) . PHP_EOL . PHP_EOL;

echo "2. Menggunakan Method:" . PHP_EOL;
echo "   Nama: {$latte->name}" . PHP_EOL;
echo "   Brewing: {$brewInfo}" . PHP_EOL;
echo "   Catatan Rasa: {$tastingNote}" . PHP_EOL . PHP_EOL;

echo "3. Inheritance (MainCourse extends MenuItem):" . PHP_EOL;
echo "   " . formatMenuItemLine($noodle) . PHP_EOL;
echo "   " . $noodle->serve() . PHP_EOL . PHP_EOL;

echo "4. Daftar Menu:" . PHP_EOL;
echo "=== DAFTAR MENU ===" . PHP_EOL . PHP_EOL;
foreach ($daftarMenu as $idx => $item) {
    $nomor = $idx + 1;
    echo "{$nomor}. " . formatMenuItemLine($item) . PHP_EOL;
}
echo PHP_EOL . "Total produk: " . count($daftarMenu) . PHP_EOL;
