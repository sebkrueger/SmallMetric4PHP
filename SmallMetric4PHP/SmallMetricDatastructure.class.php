<?php
/**
 * SmallMetric4PHP - Performance-Timer
 *
 * Copyright (c) 2013 Sebastian Krüger
 *
 * @package SmallMetric4PHP
 * @author Sebastian Krüger
 * @file SmallMetric4PHP/SmallMetricDatastructure.class.php
 */

namespace SmallMetric4PHP;

class SmallMetricDatastructure {

    private $label;
    private $wallclocktime;

    /**
     * Constructor simple store the values in private vars
     *
     * @param string $label Name of the Point
     * @param double $wallclocktime Simple timestamp with microtime precision
     *
     * @return SmallMetricDatastructure
     */
    public function __construct($label, $wallclocktime) {
        $this->label=$label;
        $this->wallclocktime=$wallclocktime;
    }

    /**
     * Getterfunction for label
     *
     * @return string
     */
    public function getLabel() {
        return $this->label;
    }

    /**
     * Getterfunction for wallclocktime
     *
     * @return double
     */
    public function getWallclocktime() {
        return $this->wallclocktime;
    }
}