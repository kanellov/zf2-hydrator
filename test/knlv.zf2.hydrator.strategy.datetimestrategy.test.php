<?php
require __DIR__ . '/../vendor/autoload.php';

use FUnit as fu;
use Zend\Stdlib\Hydrator\ObjectProperty;
use Knlv\Zf2\Hydrator\Strategy\DateTimeStrategy;

fu::setup(function () {
    $hydrator = new ObjectProperty();
    $strategy = new DateTimeStrategy();
    $hydrator->addStrategy('created', $strategy);
    fu::fixture('strategy', $strategy);
    fu::fixture('hydrator', $hydrator);
});

fu::test('Testing hydrate/extract methods', function () {
    $hydrator        = fu::fixture('hydrator');
    $object          = new stdClass();
    $object->name    = 'test';
    $object->data    = array('data1', 'data2');
    $object->created = new DateTime('2015-01-02 10:10:10');

    $expected = array(
        'name'    => 'test',
        'data'    => array('data1', 'data2'),
        'created' => '2015-01-02 10:10:10',
    );
    fu::equal($expected, $hydrator->extract($object), 'Assert extract works');
    fu::equal($object, $hydrator->hydrate($expected, new stdClass()), 'Assert hydrate works');
});

fu::test('Test set/get format methods', function () {
    $strategy = fu::fixture('strategy');
    fu::equal($strategy::DEFAULT_FORMAT, $strategy->getFormat(), 'Assert default format if no other set');

    $strategy->setFormat(DateTime::ATOM);
    fu::equal(DateTime::ATOM, $strategy->getFormat(), 'Assert format changed');
});

fu::test('Test set/get timzone methods', function () {
    $strategy = fu::fixture('strategy');
    fu::equal(new DateTimeZone(date_default_timezone_get()), $strategy->getTimezone(), 'Assert default timezone if no other set');

    $timezone = new DateTimeZone('Europe/London');
    $strategy->setTimezone($timezone);
    fu::equal($timezone, $strategy->getTimezone(), 'Assert timezone set as object');

    $strategy->setTimezone('Europe/Athens');
    fu::equal($timezone, $strategy->getTimezone(), 'Assert timezone set as string');
});
