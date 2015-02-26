<?php
require __DIR__ . '/../vendor/autoload.php';

use FUnit as fu;
use Zend\Stdlib\Hydrator\ObjectProperty;
use Knlv\Zf2\Hydrator\Strategy\MultilineTextStrategy;

fu::setup(function () {
    $hydrator = new ObjectProperty();
    $strategy = new MultilineTextStrategy();
    $hydrator->addStrategy('items', $strategy);
    fu::fixture('strategy', $strategy);
    fu::fixture('hydrator', $hydrator);
});

fu::test('Testing hydrate/extract methods', function () {
    $hydrator        = fu::fixture('hydrator');
    $object          = new stdClass();
    $object->name    = 'test';
    $object->items    = array('item1', 'item2', 'item3', 'item4', 'item5');

    $expected = array(
        'name'    => 'test',
        'items'    => <<< EOL
item1
item2
item3
item4
item5
EOL
    ,);
    fu::equal($expected, $hydrator->extract($object), 'Assert extract works');
    fu::equal($object, $hydrator->hydrate($expected, new stdClass()), 'Assert hydrate works');
});
