<?php
/**
 * SmallMetric4PHP - Performance-Timer
 *
 * Copyright (c) 2013 Sebastian Krüger
 *
 * @version 0.1.0
 * @package SmallMetric4PHP
 * @author Sebastian Krüger
 * @file SmallMetric4PHP/SmallMetric4PHP.class.php
 *
 */

namespace SmallMetric4PHP;

require_once __DIR__.'/SmallMetricDatastructure.class.php';

class SmallMetric4PHP {

    private $collectionname;
    private $fixpoints;

    /**
     * Constructor init the object and start messuring
     * when no label is added, start is implied
     *
     * @param string $name Name of the Metricollection to differ metrics
     * @param string $label Add some Lable to find the Startpoint
     *
     * @return SmallMetric4PHP
     */
    public function __construct($name,$label='Start') {
        $this->collectionname = $name;

        // Clear the Fixpoint DataArray
        $this->fixpoints = array();

        // Make initial Fixpoint for Start
        $this->Fixpoint($label);
    }

    /**
     * Fix values for metric at this point
     *
     * @param string $label Name of the Fixpoint
     */
    public function Fixpoint($label='') {
        // Stop Time first
        $wct = $this->getWallclockMicrotime();

        // Store in object
        $fixp = new SmallMetricDatastructure($label, $wct);

        // Collect the Objekt
        array_push($this->fixpoints,$fixp);
    }

    /**
     * End messuring
     *
     * @param String $label optional label for endpoint name
     *
     * @return null
     */
    public function Endpoint($label='End') {

    }

    public function PrintResult() {

    }

    /**
     * Retrun now timestamp in seconds with mircseconds
     *
     * @return double Timestamp with seconds and mircoseconds
     */
    private function getWallclockMicrotime() {
        $probetime = microtime();
        $timecomponents = explode(" ",$probetime);
        return $timecomponents[0] + $timecomponents[1];
    }

    /**
     * fix the raw CPU usage time untill now separate the
     * value for system and usertime
     * u - usertime // s - systemtime
     *
     * @return array CPU-Usage time for system and user
     */
    private function getRawCputime() {
        $ru = getrusage();
        // The time ist given separte in seconds and microseconds add them
        $ret['u'] = $ru['ru_utime.tv_sec']+$ru['ru_utime.tv_usec']/1000000;
        $ret['s'] = $ru['ru_stime.tv_sec']+$ru['ru_stime.tv_usec']/1000000;
        return $ret;
    }
}