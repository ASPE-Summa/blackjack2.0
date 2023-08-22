<?php

declare(strict_types=1);

namespace Aspe\Blackjack\Interfaces;

interface ScoreCalculatorInterface{
    
    /**
     * calculateTotal
     *
     * @param  mixed $hand
     * @return int
     */
    public function calculateTotal(array $hand): int;
}