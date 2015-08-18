<?php

class UNL_Knowledge_Driver_REST implements UNL_Knowledge_DriverInterface
{
    /**
     * The address to the webservice
     *
     * @var string
     */
    public $service_url = 'https://www.digitalmeasures.com/login/service/v4/SchemaData/INDIVIDUAL-ACTIVITIES-University/';

    public static $service_user;

    public static $service_pass;

    function __construct($options = array())
    {
        if (isset($options['service_url'])) {
            $this->service_url = $options['service_url'];
        }
    }

    function getCategory($category, $uid)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL             => $this->service_url . 'USERNAME:' . $uid . '/' . $category,
            CURLOPT_USERPWD         => UNL_Knowledge_Driver_REST::$service_user . ':' . UNL_Knowledge_Driver_REST::$service_pass,
            CURLOPT_ENCODING        => 'gzip',
            CURLOPT_FOLLOWLOCATION  => true,
            CURLOPT_RETURNTRANSFER  => true,
        ));

        $responseData = curl_exec($curl);

        if (curl_errno($curl)) {
            $errorMessage = curl_error($curl);
        } else {
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if ($statusCode === 200) {
                $xml = simplexml_load_string($responseData, "SimpleXMLElement", LIBXML_NOCDATA);
                $json = json_encode($xml);
                $array = json_decode($json,TRUE);
            }
        }

        curl_close($curl);

        return isset($array['Record'][$category]) ? $array['Record'][$category] : null;
    }

    function getRecords($uid)
    {
        $records = new UNL_Knowledge();

        $records->public_web = $this->getCategory('PUBLIC_WEB', $uid);

        if ($records->public_web) {
            $records->bio           = $this->cleanRecords($records->public_web['BIO']);
            $records->courses       = $this->cleanRecords($records->public_web['SCHTEACH']);
            $records->education     = $this->cleanRecords($records->public_web['EDUCATION']);
            $records->grants        = $this->cleanRecords($records->public_web['CONGRANT']);
            $records->honors        = $this->cleanRecords($records->public_web['AWARDHONOR']);
            $records->papers        = $this->cleanRecords($records->public_web['INTELLCONT']);
            $records->presentations = $this->cleanRecords($records->public_web['PRESENT']);
        }

        return $records;
    }

    function cleanRecords($records)
    {
        if (is_array($records)) {
            foreach ($records as $key => $value) {
                if (isset($value['REF']) && $value['REF'] == false) {
                    // Clear empty record within an array that has a blank REF value
                    unset($records[$key]);
                }
            }

            if (isset($records['REF']) && $records['REF'] == false) {
                // Clear empty record that has a blank REF value
                $records = null;
            } else if (isset($records['REF'])) {
                // Convert single record to indexed array at key 0
                $temp = $records;
                $records = array();
                $records[0] = $temp;
            }
        }



        return $records;
    }
}
