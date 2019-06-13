<?php
/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */

namespace Trafiklab\ResRobot\Model\Enum;


abstract class ResRobotTransportType
{
    public const TRAIN_HIGH_SPEED = 2;
    public const TRAIN_REGIONAL_INTERCITY = 4;
    public const BUS_LONG_DISTANCE = 8;
    public const TRAIN_LOCAL = 16;
    public const SUBWAY = 32;
    public const TRAM_LIGHT_RAIL = 64;
    public const BUS_LOCAL = 128;
    public const FERRIES_BOATS_CRUISES = 256;
}
