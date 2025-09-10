<?php

declare(strict_types=1);

namespace Koneko\KonekoVuexyAdmin\Support\Traits\Geolocation;

trait HasGeolocation
{
    public function getCoordinates(): ?array
    {
        return ($this->lat && $this->lng) ? [$this->lat, $this->lng] : null;
    }
}
