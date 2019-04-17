<?php

namespace Trafiklab\Resrobot\Model;


use DateTime;

class TimeTableRequestTest extends \PHPUnit_Framework_TestCase
{

    function testSetType()
    {
        $request = new TimeTableRequest();
        $request->setTimeTableType(TimeTableType::DEPARTURES);
        self::assertEquals(TimeTableType::DEPARTURES, $request->getTimeTableType());

        $request->setTimeTableType(TimeTableType::ARRIVALS);
        self::assertEquals(TimeTableType::ARRIVALS, $request->getTimeTableType());
    }

    function testSetStopId()
    {
        $request = new TimeTableRequest();
        $request->setStopId("ABC012");
        self::assertEquals("ABC012", $request->getStopId());

        $request->setStopId("");
        self::assertEquals("", $request->getStopId());
    }

    function testSetDateTime()
    {
        $request = new TimeTableRequest();
        $now = new DateTime();
        $request->setDateTime($now);
        self::assertEquals($now, $request->getDateTime());
    }

    function testSetProductFilter()
    {
        $request = new TimeTableRequest();
        $request->addVehicleToFilter(TransportType::TRAIN_LOCAL);
        self::assertEquals(TransportType::TRAIN_LOCAL, $request->getVehicleFilter());

        $request->addVehicleToFilter(TransportType::BUS_LOCAL);
        self::assertEquals(TransportType::TRAIN_LOCAL + TransportType::BUS_LOCAL, $request->getVehicleFilter());
    }

    function testSetOperatorFilter()
    {
        $request = new TimeTableRequest();
        $request->addOperatorToFilter(253);
        self::assertEquals([253], $request->getOperatorFilter());

        $request->addOperatorToFilter(256);
        self::assertEquals([253,256], $request->getOperatorFilter());
    }

}
