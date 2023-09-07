<?php

declare(strict_types=1);

namespace Aspe\Blackjack\test;

use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use Aspe\Blackjack\ScoreCalculator;
use InvalidArgumentException;

final class ScoreCalculatorTest extends TestCase{

    private ScoreCalculator $scoreCalculator;

    public function setUp(): void
    {
        $this->scoreCalculator = new ScoreCalculator();    
    }

    public function testBlackJack(): void
    {
        $hand = ['ac', 'jc'];
        $result = $this->scoreCalculator->calculateTotal($hand);
        assertEquals(21, $result);
    }


    public function testInvalid(): void
    {
        $hand = ['c6', '2c'];
        $this->expectException(InvalidArgumentException::class);
        $this->scoreCalculator->calculateTotal($hand);
    }
}