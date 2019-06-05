<?php

namespace App\Inspections;

interface SpamInterface
{
    public function detect($body);
}
