<?php

/**
 * SearchTest.php
 */


include '../Search.php';
include '../Database.php';

/*!@class SearchTest
 * @brief Test module for Search module.
 */
class SearchTest extends PHPUnit_Framework_TestCase{
    /**
     * @class Search
     * @test Given user u, 
     *       If u.class is doctor, then u can view:
     *       Select[doctor_id=u.person_id](Radiology_Record)
     *       
     *       If u.class is patient, then u can view:
     *       Select[patient_id=u.person_id](Radiology_Record)
     *
     *       If u.class is radiologist
     *       Select[radiologist_id=u.person_id](Radiology_Record)
     * 
     *       If u.class is admin
     *       Select[*](Radiology_Record)
     */
    public function securityModuleFilterTest(){
        connect();
    }

    /**
     * @class Search
     * @test Given a list of keywords K, and records R (any records), filter tuple
     *       that contains all keywords in K. i.e.
     *       For all k in K, x in R, there exist a column j in x, s.t. x[j] = k.
     *       
     *       Note that x[j] = k, is actually EditDistance[x[j], k] <= 2. This is 
     *       to allow some mistakes.
     */
    public function searchConditionFilterTest(){

    }
    
    /**
     * @class Search
     * @test Ensure that the ranking is:
     *       Rank(record_id) = 6*frequency(patient_name) + 3*frequency(diagnosis) + frequency(description)
     */
    public function searchDefaultRankingTest(){
    }
    
    /**
     * @class Search
     * @test Ensure that the ranking is most recent first.
     */
    public function mostRecentFirstRankingTest(){
    }

    /**
     * @class Search
     * @test Ensure that the ranking is most recent last.
     */
    public function mostRecentLastRankingTest(){
    }
}

?>