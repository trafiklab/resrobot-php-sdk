<?php

namespace Trafiklab\ResRobot\Model;


use DateTime;
use PHPUnit_Framework_TestCase;
use Trafiklab\Common\Model\Enum\RoutePlanningSearchType;
use Trafiklab\ResRobot\Model\Enum\ResRobotTransportType;

class ResRobotRoutePlanningRequestTest extends PHPUnit_Framework_TestCase
{

    function testGetOriginStopId()
    {
        $request = new ResRobotRoutePlanningRequest();
        $request->setOriginStopId("1234");
        self::assertEquals("1234", $request->getOriginStopId());
    }

    function testGetDestinationStopId()
    {
        $request = new ResRobotRoutePlanningRequest();
        $request->setDestinationStopId("1234");
        self::assertEquals("1234", $request->getDestinationStopId());
    }

    function testGetViaStopId()
    {
        $request = new ResRobotRoutePlanningRequest();
        $request->setViaStopId("1234");
        self::assertEquals("1234", $request->getViaStopId());
    }

    function testSetDateTime()
    {
        $request = new ResRobotRoutePlanningRequest();
        $now = new DateTime();
        $request->setDateTime($now);
        self::assertEquals($now, $request->getDateTime());
    }

    function testGetLanguage()
    {
        $request = new ResRobotRoutePlanningRequest();
        self::assertEquals("sv", $request->getLang());
        $request->setLang("en");
        self::assertEquals("en", $request->getLang());
        $request->setLang("sv");
        self::assertEquals("sv", $request->getLang());
    }

    function testGetRouteplanningType()
    {
        $request = new ResRobotRoutePlanningRequest();

        self::assertEquals(RoutePlanningSearchType::DEPART_AT_SPECIFIED_TIME, $request->getRoutePlanningSearchType());

        $request->setRoutePlanningSearchType(RoutePlanningSearchType::ARRIVE_AT_SPECIFIED_TIME);
        self::assertEquals(RoutePlanningSearchType::ARRIVE_AT_SPECIFIED_TIME, $request->getRoutePlanningSearchType());

        $request->setRoutePlanningSearchType(RoutePlanningSearchType::DEPART_AT_SPECIFIED_TIME);
        self::assertEquals(RoutePlanningSearchType::DEPART_AT_SPECIFIED_TIME, $request->getRoutePlanningSearchType());
    }

    function testSetProductFilter()
    {
        $request = new ResRobotRoutePlanningRequest();
        $request->addTransportTypeToFilter(ResRobotTransportType::TRAIN_LOCAL);
        self::assertEquals(ResRobotTransportType::TRAIN_LOCAL, $request->getVehicleFilter());

        $request->addTransportTypeToFilter(ResRobotTransportType::BUS_LOCAL);
        self::assertEquals(ResRobotTransportType::TRAIN_LOCAL + ResRobotTransportType::BUS_LOCAL,
            $request->getVehicleFilter());
    }

    function testSetOperatorFilter()
    {
        $request = new ResRobotRoutePlanningRequest();
        $request->addOperatorToFilter(253);
        self::assertEquals([253], $request->getOperatorFilter());

        $request->addOperatorToFilter(256);
        self::assertEquals([253, 256], $request->getOperatorFilter());
    }

}
