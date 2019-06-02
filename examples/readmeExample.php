<?php

require_once '../vendor/autoload.php';

use GusApi\Exception\InvalidUserKeyException;
use GusApi\Exception\NotFoundException;
use GusApi\GusApi;
use GusApi\ReportTypes;
use GusApi\BulkReportTypes;

$gus = new GusApi('your api key here');
//for development server use:
//$gus = new GusApi('abcde12345abcde12345', 'dev');

try {
    $nipToCheck = 'xxxxxxxxxx'; //change to valid nip value
    $gus->login();

    $gusReports = $gus->getByNip($nipToCheck);

    var_dump($gus->dataStatus());
    var_dump($gus->getBulkReport(
        new DateTimeImmutable('2019-05-31'),
        BulkReportTypes::REPORT_DELETED_LOCAL_UNITS));

    foreach ($gusReports as $gusReport) {
        //you can change report type to other one
        $reportType = ReportTypes::REPORT_ACTIVITY_PHYSIC_PERSON;
        echo $gusReport->getName();
        echo 'Address: '. $gusReport->getStreet(). ' ' . $gusReport->getPropertyNumber() . '/' . $gusReport->getApartmentNumber();

        $fullReport = $gus->getFullReport($gusReport, $reportType);
        var_dump($fullReport);
    }
} catch (InvalidUserKeyException $e) {
    echo 'Bad user key';
} catch (NotFoundException $e) {
    echo 'No data found <br>';
    echo 'For more information read server message below: <br>';
    echo $gus->getResultSearchMessage();
}
