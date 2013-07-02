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

    private $fixpoints;         // Array for all fixpoint objects
    private $metricsum=null;    // Contain alle Values from start to end

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
     * End messuring and calculate values
     *
     * @param String $label optional label for endpoint name
     *
     * @return bool
     */
    public function Endpoint($label='End') {
        if($this->metricsum!=null) {
            echo "ERROR: Messuring allready ended!";
            return false;
        }

        // First Step fix the endtime
        $this->Fixpoint($label);

        // Deltacounter
        $wctdelta = null;

        // Go thru an sum things up
        foreach($this->fixpoints AS $fixpointobjekt) {
            if($wctdelta!=null) {
                // Not the first run, calculate different
                $tempwctdelta = $this->MillisecondDelta($wctdelta,$fixpointobjekt->getWallclocktime());
            } else {
                // first point, setzt delta to zero
                $tempwctdelta = 0;
            }

            // store delta in object
            $fixpointobjekt->setDeltaValues($tempwctdelta);

            // save time for next run in var
            $wctdelta = $fixpointobjekt->getWallclocktime();
        }

        // Sum times UP
        $first_fixpoint_obj=reset($this->fixpoints);
        $last_fixpoint_obj=end($this->fixpoints);

        // Delta first to last
        $wctdelta = $this->MillisecondDelta($first_fixpoint_obj->getWallclocktime(),
                                            $last_fixpoint_obj->getWallclocktime());

        // Store in special last Objekt
        $this->metricsum = new SmallMetricDatastructure('Sum',null);
        $this->metricsum->setDeltaValues($wctdelta);

        // Everything works fine
        return true;
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
     *  Print a small table with calculatet values of the run
     *
     *  @return string Table html
     */
    public function PrintResult() {
        // Check if mesuring has end
        if($this->metricsum==null) {
            // TODO: Maybe implicit endcall here
            echo "ERROR: Mesuring still running!";
            return false;
        }

        // TODO: Better HTML CSS
        $html  = "<table style='border:#000 1px solid; border-collapse: collapse;'>";
        $html .= "<tr style='border:#000 1px solid; border-collapse: collapse;'>";
        $html .= "<td style='border-right:#000 1px solid; border-collapse: collapse;'><b><u>".$this->collectionname."</u></b></td>";
        $html .= "<td><b>Deltatime</b></td>";
        $html .= "</tr>";
        foreach($this->fixpoints AS $fixpointobjekt) {
            $html .= "<tr style='border:#000 1px solid; border-collapse: collapse;'>";
            $html .= "<td style='border-right:#000 1px solid; border-collapse: collapse;'>".$fixpointobjekt->getLabel()."</td>";
            $html .= "<td>".$fixpointobjekt->getWallclocktimeDelta()." s</td>";
            $html .= "</tr>";
        }
        $html .= "<tr style='border-top:#000 1px double; border-collapse: collapse;'>";
        $html .= "<td style='border-right:#000 1px solid; border-collapse: collapse;'><b>".$this->metricsum->getLabel()."</b></td>";
        $html .= "<td><b>".$this->metricsum->getWallclocktimeDelta()." s</b></td>";
        $html .= "</tr>";
        $html .= "</table>";

        return $html;
    }

    /**
     * Calculate the differenz between two millisecond timestamps
     *
     * @param $starttime
     * @param $endtime
     * @return string
     */
    private function MillisecondDelta($starttime,$endtime) {
        $deltatime = $endtime-$starttime;
        $deltatime = substr($deltatime,0,8);

        return $deltatime;
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