<?php


namespace Trafiklab\Resrobot\Model;


abstract class TransportType
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