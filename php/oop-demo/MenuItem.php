<?php
declare(strict_types=1);

namespace Gdw\Menu;

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
