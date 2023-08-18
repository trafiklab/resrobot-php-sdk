<?php
/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */

namespace Trafiklab\ResRobot;

use PHPUnit\Framework\TestCase as DefaultTestCase;

class TestCase extends DefaultTestCase
{
    public function readTestResource(string $path): string
    {
        return file_get_contents(__DIR__ . '/../../Resources/' . $path);
    }
}
