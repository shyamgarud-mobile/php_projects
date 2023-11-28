<?php
include('FixedLengthFileFormat.php');


try {
    $fixedLengthFileFormat = new FixedLengthFileFormat();
    // print_r($fixedLengthFileFormat->createTestArray());exit;

    /**
     * Convert csv/data to fixed lenght txt file
     */

    $fixedLengthFileFormat->setCscfilePath('csv_files/test1.csv');
    $fixedLengthFileFormat->addDataToFields($fixedLengthFileFormat->createTestArray());

    $file = 'test.txt';
    file_put_contents($file, $fixedLengthFileFormat->finalString);

    // echo $fixedLengthFileFormat->finalString;

    /**
     * Conver fixed lenght txt file to data
     */

    $fixedLengthFileFormat->convertDataToArray($file);
    print_r($fixedLengthFileFormat->finalArray);
} catch (\Throwable $th) {
    print_r($th);
}

exit;
