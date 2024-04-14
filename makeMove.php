<?php

$count = 0;
$xValue = "";
$yValue = "";
$validInputs = array('1', '2', '3', '4', '5', '6', '7', '8');

$shipOneX;
$shipOneY;
$shipTwoX;
$shipTwoY;

$shipOneHit;
$shipTwoHit;

$subtitle = "";
$boxId = null;
$boxColour = null;

$doNotIncreaseCounter = false;

function getResponseObject()
{
    $responseObject = (object) [];
    $responseObject->shipOneXResponse = $GLOBALS['shipOneX'];
    $responseObject->shipOneYResponse = $GLOBALS['shipOneY'];
    $responseObject->shipTwoXResponse = $GLOBALS['shipTwoX'];
    $responseObject->shipTwoYResponse = $GLOBALS['shipTwoY'];
    $responseObject->shipOneHitResponse = $GLOBALS['shipOneHit'];
    $responseObject->shipTwoHitResponse = $GLOBALS['shipTwoHit'];
    $responseObject->subtitle = $GLOBALS['subtitle'];
    $responseObject->boxId = $GLOBALS['boxId'];
    $responseObject->boxColour = $GLOBALS['boxColour'];
    $responseObject->doNotIncreaseCounter = $GLOBALS['doNotIncreaseCounter'];

    return $responseObject;
}

function inputsAreInvalid($x, $y)
{
    if ($x == "" || !in_array($x, $GLOBALS['validInputs'], true)) {
        return true;
    };

    if ($y == "" || !in_array($y, $GLOBALS['validInputs'], true)) {
        return true;
    }

    return false;
}

function checkIfWon($shipOne, $shipTwo)
{
    if ($shipOne === 'true' && $shipTwo === 'true') {
        return true;
    }

    return false;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $xValue = $_REQUEST['xValue'];
    $yValue = $_REQUEST['yValue'];
    $count = $_REQUEST['count'];
    $shipOneX = $_REQUEST['shipOneX'];
    $shipOneY = $_REQUEST['shipOneY'];
    $shipTwoX = $_REQUEST['shipTwoX'];
    $shipTwoY = $_REQUEST['shipTwoY'];
    $shipOneHit = $_REQUEST['shipOneHit'];
    $shipTwoHit = $_REQUEST['shipTwoHit'];

    // Validation
    if (checkIfWon($GLOBALS['shipOneHit'], $GLOBALS['shipTwoHit']) === true) {
        $GLOBALS['doNotIncreaseCounter'] = true;
        $GLOBALS['subtitle'] = "I told you, you've already won! Refresh the page to start a new game";
        $responseObject = getResponseObject();
        $result = json_encode($responseObject);
        echo $result;
        return;
    }

    if ($GLOBALS['count'] >= 20) {
        $GLOBALS['doNotIncreaseCounter'] = true;
        $GLOBALS['subtitle'] = "You've run out of guesses... you lose! Refresh the page to start a new game";
        $responseObject = getResponseObject();
        $result = json_encode($responseObject);
        echo $result;
        return;
    }

    if (inputsAreInvalid($xValue, $yValue)) {
        $GLOBALS['doNotIncreaseCounter'] = true;
        $GLOBALS['subtitle'] = "Oooh, that's not a valid input sorry.";
        $responseObject = getResponseObject();
        $result = json_encode($responseObject);
        echo $result;
        return;
    }

    make_move($xValue, $yValue);
}

function getRandomNumber()
{
    return rand(1, 8);
}

function checkIfHit($xValue, $yValue)
{
    $shipOneResult = abs($xValue - $GLOBALS['shipOneX']) + abs($yValue - $GLOBALS['shipOneY']);
    $shipTwoResult = abs($xValue - $GLOBALS['shipTwoX']) + abs($yValue - $GLOBALS['shipTwoY']);

    // Pick the closer result of the two
    $result = null;
    if ($GLOBALS['shipOneHit'] === 'true') {
        $result = $shipTwoResult;
    } elseif ($GLOBALS['shipTwoHit'] === 'true') {
        $result = $shipOneResult;
    } else {
        $result = min($shipOneResult, $shipTwoResult);
    }

    if ($shipOneResult === 0) {
        $GLOBALS['shipOneHit'] = 'true';
    }

    if ($shipTwoResult === 0) {
        $GLOBALS['shipTwoHit'] = 'true';
    }

    return $result;
}

function setResultOfMove($guess, $moveResult)
{
    $hit = array(0);
    $hot = array(1, 2);
    $warm = array(3, 4);

    if (in_array($moveResult, $hit)) {
        $GLOBALS['subtitle'] = "Hit!";
        $GLOBALS['boxId'] = $guess;
        $GLOBALS['boxColour'] = "hit";
    } elseif (in_array($moveResult, $hot)) {
        $GLOBALS['subtitle'] = "Hot, so close...";
        $GLOBALS['boxId'] = $guess;
        $GLOBALS['boxColour'] = "hot";
    } elseif (in_array($moveResult, $warm)) {
        $GLOBALS['subtitle'] = "Warm, almost there...";
        $GLOBALS['boxId'] = $guess;
        $GLOBALS['boxColour'] = "warm";
    } else {
        $GLOBALS['subtitle'] = "Cold, absolutely cold...";
        $GLOBALS['boxId'] = $guess;
        $GLOBALS['boxColour'] = "cold";
    }
}

function make_move($xValue, $yValue)
{
    if ($GLOBALS['count'] == 0) {
        $GLOBALS['shipOneX'] = getRandomNumber();
        $GLOBALS['shipOneY'] = getRandomNumber();
        $GLOBALS['shipTwoX'] = getRandomNumber();
        $GLOBALS['shipTwoY'] = getRandomNumber();
    }

    $guess = $xValue . $yValue;
    $moveResult = checkIfHit($xValue, $yValue);
    setResultOfMove($guess, $moveResult);

    if (checkIfWon($GLOBALS['shipOneHit'], $GLOBALS['shipTwoHit']) === true) {
        $GLOBALS['subtitle'] = "You win! Both ships hit! Refresh the page to start a new game.";
    }

    // return response
    $responseObject = getResponseObject();
    $result = json_encode($responseObject);
    echo $result;
    return;
}
