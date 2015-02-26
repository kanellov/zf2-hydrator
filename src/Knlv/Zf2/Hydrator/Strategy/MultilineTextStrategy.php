<?php

/**
 * Knlv\Zf2\Hydrator\Strategy\MultilineTextStrategy
 *
 * @link https://github.com/kanellov/zf2-hydrator
 * @copyright Copyright (c) 2015 Vassilis Kanellopoulos - contact@kanellov.com
 * @license https://raw.githubusercontent.com/kanellov/zf2-hydrator/master/LICENSE
 */

namespace Knlv\Zf2\Hydrator\Strategy;

use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;

class MultilineTextStrategy implements StrategyInterface
{
    public function extract($value)
    {
        return implode(PHP_EOL, $value);
    }

    public function hydrate($value)
    {
        if (is_string($value)) {
            return array_filter(
                preg_split('/$\R?^/m', $value, -1, PREG_SPLIT_NO_EMPTY),
                function ($line) {
                    return '' !== trim($line);
                }
            );
        }

        return $value;
    }
}
