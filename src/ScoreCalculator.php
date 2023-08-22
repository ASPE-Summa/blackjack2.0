<?php

class ScoreCalculator
{
    /**
     * calculateTotal
     *
     * @param  mixed $hand
     * @return int
     */
    public function calculateTotal(array $hand): int
    {
        $total = 0;
        $aces = 0;
        foreach ($hand as $card) {
            $firstCharacter = substr($card, 0, 1);
            if (is_numeric($firstCharacter)) {
                $total += $firstCharacter;
            } else if (in_array($firstCharacter, ['t', 'j', 'q', 'k'])) {
                $total += 10;
            } else if ($firstCharacter == 'a') {
                $aces++;
            }
        }

        for ($i = 0; $i < $aces; $i++) {
            if (($total + 11) <= 21) {
                $total += 11;
            } else {
                $total += 1;
            }
        }
        return $total;
    }
}
