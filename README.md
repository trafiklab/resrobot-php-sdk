# ResRobot PHP SDK

[![Build status](https://travis-ci.com/trafiklab/resrobot-php-sdk.svg?branch=master)](https://travis-ci.com/trafiklab/resrobot-php-sdk)
[![Latest Stable Version](https://poser.pugx.org/trafiklab/resrobot-php-sdk/v/stable)](https://packagist.org/packages/trafiklab/resrobot-php-sdk)
[![codecov](https://codecov.io/gh/trafiklab/resrobot-php-sdk/branch/master/graph/badge.svg)](https://codecov.io/gh/trafiklab/resrobot-php-sdk)
[![License: MPL 2.0](https://img.shields.io/badge/License-MPL%202.0-brightgreen.svg)](https://opensource.org/licenses/MPL-2.0)

ResRobot offers realtime data about Sweden's public transport. Show all departures and arrivals for a stop, or easily
plan a route from A to B.
More information can be found
at [Trafiklab - ResRobot routeplanner](https://www.trafiklab.se/api/trafiklab-apis/resrobot-v21/route-planner/)
and [Trafiklab - ResRobot timetables](https://www.trafiklab.se/api/trafiklab-apis/resrobot-v21/timetables/).

This repository contains a PHP SDK to easily use the ResRobot APIs. This way you don't need to worry about making
requests, caching,
or parsing responses. All responses are parsed and returned as PHP classes.

## Installation

Installing can be done by using Composer:

`composer require trafiklab/resrobot-php-sdk`

#### Versioning

This package follows [Semantic versioning](https://semver.org/):
> Given a version number MAJOR.MINOR.PATCH, we increment the:
> - MAJOR version when we make incompatible API changes,
> - MINOR version when we add functionality in a backwards-compatible manner, and
> - PATCH version when we make backwards-compatible bug fixes.

Additional labels for pre-release and build metadata are available as extensions to the MAJOR.MINOR.PATCH format.

## Requirements

The following software is required to use this SDK:

- PHP 7.4 or higher
- PHP Curl extension
- PHP JSON extension

## Usage

In order to use the Resrobot Timetable and Routeplanning APIs,
you need to obtain an API key from [Trafiklab](https://trafiklab.se) first.

**Important:** You only need 1 API key, which can be used for all Resrobot API calls. The multiple API-key setters are
only there for compatibility with our other API wrappers, allowing you to easily switch between APIs.

### Getting Timetables (departures or arrivals from a stop)

#### Timetables

##### Request

The following code example illustrates how you can retrieve a timetable for a certain stop.

```
$wrapper = new ResRobotWrapper();

// Create a new routeplanning object. The wrapper will instantiate an object of the interface type.
$wrapper = $wrapper->createTimeTableRequestObject();
$wrapper->setStopId("740000001");
$wrapper->setTimeTableType(TimeTableType::DEPARTURES);

$wrapper->setUserAgent("<YOUR_USER_AGENT>");
$wrapper->setTimeTablesApiKey("<YOUR_API_KEY>");
$response = $resRobotWrapper->getTimeTable($departuresRequest);
```

`<YOUR_API_KEY>` is obtained from [Trafiklab](https://trafiklab.se). `<YOUR_USER_AGENT>` is a string which identifies
your application.
While this is not enforced in any way, it is good practice to use a clear user agent.
An example could be `MyDemoApp/1.0.0 (mail@example.com) `.
If you don't want to send a user agent, you can just leave out this line.

Detailed information about ResRobot request parameters can be found at
the [ResRobot departures/arrivals API page](https://www.trafiklab.se/api/resrobot-reseplanerare).
Only the most important/most used request parameters are implemented in the SDK, in order to reduce clutter, and to
ensure that we can keep the SDK unchanged in case of changes to the API.
If you believe we have missed an important field, please create an issue so we can review this.

##### Response

In order to use the data returned by your request, you can simply call getTimeTable() on the response object.
This method returns an array of TimeTableEntry instances, each of which describes one departure or arrival.
You can look at the code and PHPDoc in order to get up-to-date information on which fields are available.
Detailed information about ResRobot responses can be found at
the [ResRobot departures/arrivals API page](https://www.trafiklab.se/api/resrobot-reseplanerare).

The following code gives a quick idea on how the SDK is used.

```
$entry = $response->getTimetable()[0]; // Get the first result
// Type of transport, one of the constants in Trafiklab\Common\Model\Enum\TransportType
$entry->getTransportType(); 
// The name of the stop location
$stop = $timeTableEntry->getStopName()
// The number of the line
$lineNumber = $timeTableEntry->getLineNumber();
// The direction of the vehicle
$direction = $timeTableEntry->getDirection();
// The scheduled departure time at the stop
$scheduledStopTime = $timeTableEntry->getScheduledStopTime();   
```

#### Routeplanning

##### Request

The following code example illustrates how you can plan a route from A to B

```    
$queryTime = new DateTime();
$queryTime->setTime(18, 0);

$wrapper = new ResRobotWrapper();

// Create a new routeplanning object. The wrapper will instantiate an object of the interface type.
$wrapper = $wrapper->createRoutePlanningRequestObject();
$wrapper->setOriginId("740000001");
$wrapper->setDestinationId("740000002");
$wrapper->setDateTime($queryTime);

$wrapper->setUserAgent(("<YOUR_USER_AGENT>");
$wrapper->setRoutePlanningApiKey("<YOUR_API_KEY>");
$response = $resRobotWrapper->getRoutePlanning($routePlanningRequest);
```

##### Response

In order to use the data returned by your request, you can simply call getTrips() on the response object.
This method returns an array of Trip instances, each of which describes one departure or arrival.
You can look at the code and PHPDoc in order to get up-to-date information on which fields are available.
Detailed information about ResRobot responses can be found at
the [ResRobot departures/arrivals API page](https://www.trafiklab.se/api/resrobot-reseplanerare).

The following code gives a quick idea on how the SDK is used.

```
$trip = $response->getTrips()[0]; // Get the first result

// Tell the user about every leg in their journey.
foreach ($trip->getLegs() as $leg) {

   // There are two types of legs (at this moment): Vehicle journeys, where a vehicle is used, or walking parts
   // where a user walks between two stations. Not all fields are available for walking parts, so we need to handle them differently.

   if ($leg->getType() == RoutePlanningLegType::VEHICLE_JOURNEY) {
       $leg->getVehicle()->getType();
       $leg->getVehicle()->getNumber();
       $leg->getDirection();
       $leg->getDeparture()->getStopName();
       $leg->getDeparture()->getScheduledDepartureTime()->format("H:i");
       $leg->getDeparture()->getScheduledDepartureTime()->getTimestamp();
       $leg->getArrival()->getScheduledArrivalTime()->getTimestamp(); 
       $leg->getArrival()->getStopName();
       // More fields are available
   } else if ($leg->getType() == RoutePlanningLegType::WALKING) {
       // Limited fields when walking!
       $leg->getDeparture()->getStopName(); // origin
       $leg->getArrival()->getStopName(); // destination
   }
}
```

## Contributing

We accept pull requests, but please create an issue first in order to discuss the addition or fix.
If you would like to see a new feature added, you can also create a feature request by creating an issue.

## Help

If you're stuck with a question, feel free to ask help through the Issue tracker.

- Need help with API keys? Please read [our getting started guide](https://www.trafiklab.se/docs/using-trafiklab/getting-started/) first.
- Do you want to check the current systems status? Service disruptions
  are published on the [Trafiklab homepage](https://www.trafiklab.se/)
