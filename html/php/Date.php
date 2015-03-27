<?php
/**
 * Date.php
 */

include_once 'Const.php';

/*!@class Month
 * @brief 
 * 
 * @see http://infolab.stanford.edu/~ullman/fcdb/oracle/or-time.html for
 *      Oracle Time conventions.
 */
class Month {
    private $_val = self::January;
    
    /**
     * @param m ORACLE MM format, such as 00, 01, or use Month::Janurary, Month::February, etc.     
     */
    public function __construct($m){
        if($m >= 1 && $m <= 12){
            $this->_val = $m;
        }else{        
            throw new Exception('Month index: '.$m.' is out of bound.');
        }
    }
    
    const January = 1;
    const February = 2;
    const March = 3;
    const April = 4;
    const May = 5;
    const June = 6;
    const July = 7;
    const August = 8;
    const September = 9;
    const October = 10;
    const November = 11;
    const December = 12;

    /**
     * @return MM string of the month.
     */
    function toMM(){
        switch($this->_val){
        case Month::January:
            return '01';
        case Month::February:
            return '02';
        case Month::March:
            return '03';
        case Month::April:
            return '04';
        case Month::May:
            return '05';
        case Month::June:
            return '06';
        case Month::July:
            return '07';
        case Month::August:
            return '08';
        case Month::September:
            return '09';
        case Month::October:
            return '10';
        case Month::November:
            return '11';
        case Month::December:
            return '12';
        }
    }

    /**
     * @return MON format string.
     */
    function toMON(){
        switch($this->_val){
        case Month::January:
            return 'JAN';
        case Month::February:
            return 'FEB';
        case Month::March:
            return 'MAR';
        case Month::April:
            return 'APR';
        case Month::May:
            return 'MAY';
        case Month::June:
            return 'JUN';
        case Month::July:
            return 'JUL';
        case Month::August:
            return 'AUG';
        case Month::September:
            return 'SEPT';
        case Month::October:
            return 'OCT';
        case Month::November:
            return 'NOV';
        case Month::December:
            return 'DEC';
        }
    }

    /**
     * @param $mm 
     * @return MON version of MM format.
     * @throw Exception if mm is not exactly of MON format.
     */
    static function MONtoMM($mm){
        switch($mm){
        case 'JAN':
            return Month::January;
        case 'FEB':
            return Month::February;
        case 'MAR':
            return Month::March;
        case 'APR':
            return Month::April;
        case 'MAY':
            return Month::May;
        case 'JUN':
            return Month::June;
        case 'JUL':
            return Month::July;
        case 'AUG':
            return Month::August;
        case 'SEP':
            return Month::September;
        case 'OCT':
            return Month::October;
        case 'NOV':
            return Month::November;
        case 'DEC':
            return Month::December;
        default:
            throw new Exception('MON format string not recognized.');
        }
    }
}


/*!@class Date
 * @brief Abstraction to Oracle Date data type.
 */
class Date{    
    protected $_month = NULL;
    protected $_day = 1;
    protected $_year = 2015;

    /**
     * Two way to initialize. Provide ORACLE DATE format string or
     * provide Month, Date, Year.
     * @param ORACLE Date format string DD-MM-YYYY, e.g. 05-02-2015 or
     *
     * @param Month
     * @param Day
     * @param Year
     */
    public function __construct(){
        $a = func_get_args();
        $i = func_num_args();
        if($i == 1 || $i == 3){
            call_user_func_array(array($this, '__construct'.$i), $a);
        }else{
            throw new Exception('Number of arguments matches no constructor.');
        }
    }

    private function __construct1($a1){
        $args = explode('-', $a1);
        $this->__construct3($args[0], $args[1], $args[2]);
    }

    private function __construct3($month, $day, $year){
        $this->_month = new Month($month);
                
        if($day >= 1 && $day <= 31){
            $this->_day = intval($day);
        }else{            
            throw new Exception('Day is out of bound.');
        }
        
        $this->_year = $year;
    }
    
    /**
     * Overloaded toString so treating Date object as a string will dereference
     * an Oracle Date formatted string.
     */
    public function __toString(){
        $dayStr = ($this->_day < 10? '0'.$this->_day : (string)$this->_day);
        $dateStr = "TO_DATE('".$this->_month->toMM()."-".$dayStr."-".$this->_year."', '".
                 DATE_FORMAT."')";
        //print $dateStr;
        return $dateStr;
    }

    public function getMonth(){ return $this->_month; }
    public function getDay(){ return $this->_day; }
    public function getYear(){ return $this->_year; }
}

?>