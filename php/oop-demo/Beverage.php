<?php
declare(strict_types=1);

namespace Gdw\Menu;

require_once __DIR__ . '/MenuItem.php';

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
