<?php
declare(strict_types=1);

namespace Gdw\Menu;

require_once __DIR__ . '/MenuItem.php';

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
