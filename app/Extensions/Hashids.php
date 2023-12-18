<?php

namespace Pteranodon\Extensions;

use Hashids\Hashids as VendorHashids;
use Pteranodon\Contracts\Extensions\HashidsInterface;

class Hashids extends VendorHashids implements HashidsInterface
{
    /**
     * {@inheritdoc}
     */
    public function decodeFirst(string $encoded, string $default = null): mixed
    {
        $result = $this->decode($encoded);
        if (!is_array($result)) {
            return $default;
        }

        return array_first($result, null, $default);
    }
}
