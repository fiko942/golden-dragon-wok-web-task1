<?php
declare(strict_types=1);

namespace Gdw\Menu {
    
    class MenuItem
    {
        protected string $name;
        protected float $price;
        public string $servedIn;
        private array $tags;

        public function __construct(string $name, float $price, string $servedIn, array $tags = [])
        {
            $this->name = $name;
            $this->price = $price;
            $this->servedIn = $servedIn;
            $this->tags = $tags;
        }

        public function __get(string $prop)
        {
            return $this->$prop ?? null;
        }

        public function __set(string $prop, $value): void
        {
            if ($prop === 'servedIn') {
                $this->servedIn = (string) $value;
            }
        }

        public function __toString(): string
        {
            return $this->describe();
        }

        public function describe(): string
        {
            $tagLine = $this->tags ? ' [' . implode(', ', $this->tags) . ']' : '';
            return sprintf(
                '%s disajikan di %s seharga %s%s',
                $this->name,
                $this->servedIn,
                $this->formatRupiah($this->price),
                $tagLine
            );
        }

        public function applyDiscount(float $percentage): float
        {
            $cut = max(0, min($percentage, 100));
            $discounted = $this->price * (1 - ($cut / 100));
            $this->price = round($discounted, 2);
            return $this->price;
        }

        private function formatRupiah(float $value): string
        {
            return 'Rp' . number_format($value, 0, ',', '.');
        }
    }

    class Beverage extends MenuItem
    {
        private bool $iced;

        public function __construct(string $name, float $price, bool $iced = false)
        {
            parent::__construct($name, $price, 'gelas tinggi', ['minuman']);
            $this->iced = $iced;
        }

        public function serveWithIce(): self
        {
            $this->iced = true;
            $this->servedIn = 'gelas penuh es';
            return $this;
        }

        public function brew(string $bean): string
        {
            $style = $this->iced ? 'iced pour-over' : 'hot drip';
            return sprintf('%s menggunakan %s (%s)', $this->name, $bean, $style);
        }

        public function tastingNote(): string
        {
            return $this->iced ? 'Segar dan ringan.' : 'Hangat dan bold.';
        }
    }

    class MainCourse extends MenuItem
    {
        protected array $sides = [];

        public function __construct(string $name, float $price)
        {
            parent::__construct($name, $price, 'mangkuk keramik', ['hidangan utama']);
        }

        public function addSide(string $side): void
        {
            if (!in_array($side, $this->sides, true)) {
                $this->sides[] = $side;
            }
        }

        public function upgradePortion(string $note): void
        {
            $this->servedIn = $note;
        }

        public function serve(): string
        {
            $sides = $this->sides ? ' dengan pendamping: ' . implode(', ', $this->sides) : '';
            return sprintf('%s siap diantar%s.', $this->name, $sides);
        }
    }
}

namespace {
    use Gdw\Menu\Beverage;
    use Gdw\Menu\MainCourse;

    date_default_timezone_set('Asia/Jakarta');

    function rupiah(float $value): string
    {
        return 'Rp ' . number_format($value, 0, ',', '.');
    }

    function formatMenuItemLine(\Gdw\Menu\MenuItem $item): string
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
}
