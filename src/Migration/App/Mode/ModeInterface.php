<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Migration\App\Mode;

/**
 * Interface ModeInterface
 */
interface ModeInterface
{
    /**
     * Run tool in particular mode
     *
     * @return bool
     */
    public function run();
}
