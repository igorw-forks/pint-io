<?php

$steps->Given('/^a value of (\d+)$/', function($world, $value) {
    $world->value = $value;
});

$steps->When('/^I add (\d+)$/', function($world, $x) {
    $world->value += $x;
});

$steps->Then('/^I have a value of (\d+)$/', function($world, $value) {
    assertEquals($value, $world->value);
});
