<?php

class FixedLengthFileFormat
{

    public $csvData = array();
    public $csvFile = '';
    public $finalString = '';
    public $finalArray = array();


    public function setCscfilePath($path)
    {
        $this->csvFile = $path;
        $this->csvToArray();
    }
    public function csvToArray()
    {
        if (isset($this->csvFile) && ($handle = fopen($this->csvFile, 'r')) !== FALSE) {
            $headers = fgetcsv($handle, 1000, ',');
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                $row = array_combine($headers, $data);
                $this->csvData[] = $row;
            }
            fclose($handle);
        } else {
            die("Could not find/open the CSV file.");
        }
    }
    public function createTestArray()
    {
        for ($i = 0; $i < 10; $i++) {
            $testArray[$i]['Record Type'] = 'Record Type' . $i;
            $testArray[$i]['Filler'] = 'Filler' . $i;
            $testArray[$i]['LockboxDda'] = 'LockboxDda' . $i;
            $testArray[$i]['Origin Code'] = 'Origin Code' . $i;
            $testArray[$i]['BatchProcessDate'] = 'BatchProcessDate' . $i;
            $testArray[$i]['CurrentDate'] = '11';
        }
        return $testArray;
    }
    public function addDataToFields($data)
    {
        foreach ($data as $key => $dataValue) {
            foreach ($this->csvData as $key => $csvValue) {
                if ($dataValue[$csvValue['Field']]) {
                    $this->csvData[$key]['Value'] =  $dataValue[$csvValue['Field']];
                }
            }
            $this->arrayToPaddingData($this->csvData);
        }
    }
    public function arrayToPaddingData($finalArray)
    {
        foreach ($finalArray as $key => $value) {
            $length = (int)$value['Length'];
            $pad_string = $value['Pad'];
            $pad_type_str = $value['Align'];
            $string = substr($value['Value'], 0, (int)$value['Length']);
            $pad_type = 0;
            if (str_contains(strtolower($pad_type_str), 'right')) {
                $pad_type = 1;
            } else {
                $pad_type = 0;
            }
            $pad_string = $pad_string == ''  || $pad_string == 'Blank'  ? ' ' : $pad_string;
            $output =  str_pad(
                $string,
                $length,
                $pad_string,
                $pad_type
            );
            $this->finalString  .= $output;
        }
        $this->finalString .= PHP_EOL;
    }
    private function paddingDataToArray($row)
    {
        $outputArray = array();
        foreach ($this->csvData as $key => $value) {
            $string = substr($row, (int)$value['Start'] - 1, (int)$value['Length']);
            $this->csvData[$key]['Value_from'] = trim($string);
            $arrKey = $value['Field'];
            $outputArray[$arrKey] = $string;
        }
        array_push($this->finalArray, $outputArray);
    }
    public function convertDataToArray($file)
    {
        $array = explode(PHP_EOL, file_get_contents($file));
        foreach ($array  as $key => $value) {
            if (!empty($value))
                $this->paddingDataToArray($value);
        }
    }
}
