<?php

namespace App\Dto;

class RoundDto
{
    public string $name;

    public function __construct(string $name)
    {
        $this->name = $this->formatRoundName($name);
    }

    private function formatRoundName(string $name)
    {
        // Get only number from string
        preg_match_all('!\d+!', $name, $matches);

        if (count($matches[0]) > 0) {
            return $matches[0][0] . '<sup>ème</sup> de finale';
        }

        // switch case for other round names
        switch ($name) {
            case 'Quarterfinals':
                return 'Quart de finale';
            case 'Semifinals':
                return 'Demi-finale';
            case 'Final':
                return 'Finale';
            default:
                return $name;
        }
    }
}
