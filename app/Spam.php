<?php

namespace App;

class Spam
{
    public function detect($body)
    {
        $this->detectInvalidKeyword($body);
        $this->detectKeyHeldDown($body);

        return false;
    }

    protected function detectInvalidKeyword($body)
    {
        $invalidKeywords = [
            'yahoo customer support',
        ];

        foreach ($invalidKeywords as $keyword) {
            if (stripos($body, $keyword) !== false) {
                throw new \Exception('Your reply contains spam.');
            }
        }

    }

    protected function detectKeyHeldDown($body)
    {
        if (preg_match('/(.)\\1{4,}/', $body)) {
            throw new \Exception('Your reply contains spam.');
        }
    }
}
