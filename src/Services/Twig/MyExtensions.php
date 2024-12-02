<?php 

namespace App\Services\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MyExtensions extends AbstractExtension {
    public function getFilters(): array {
        return [
            new TwigFilter('getInitials', [$this, 'getInitials']),
            new TwigFilter('showStars', [$this, 'showStars']),
            new TwigFilter('cutString', [$this, 'cutString'])
        ];
    }

    public function getInitials($firstname, $lastname) : string {
        return $firstname[0].$lastname[0];
    }

    public function showStars($note): string {
        $fullStars = $note;
        $emptyStars = 5 - $note;

        $str = '';

        for ($i = 0; $i < $fullStars; $i++) {
            $str .= '★';
        }

        for ($i = 0; $i < $emptyStars; $i++) {
            $str .= '☆';
        }

        return $str;
    }

    public function cutString($str, $length): string {
       return substr($str, 0, $length) . '...';
    }
}