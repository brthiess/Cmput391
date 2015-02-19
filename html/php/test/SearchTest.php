<?php

/**
 * SearchTest.php
 */


include_once '../Search.php';
include_once '../Database.php';
include_once '../Date.php';

/*!@class SearchTest
 * @brief Test module for Search module.
 */
class SearchTest extends PHPUnit_Framework_TestCase{
    private $PEOPLE = array();
    private $USERS = array(); 
    private $FAMILY_DOCTORS = array();
    private $RECORDS = array();
    
    protected function setUp(){
        $db = Database::instance();  // Acquire database instance.
        
        // Add Person objects.
        $this->PEOPLE[] = new Person(
            1, 
            "Joe", 
            'Shmoe', 
            '666 Helldrive, Hell Prairie', 
            'shmoe@hell.h', '677-8081', '02-FEB-2015');
        $this->PEOPLE[] = new Person(
            2, 
            "Sterling", 
            'Archer', 
            'CIA branch', 
            'casablumpkin@archer.com', '345-344', 'CURRENT_DATE');
        $this->PEOPLE[] = new Person(
            3, 
            "Cyril", 
            'Figgis', 
            'CIA branch', 
            'chetmanly@yahoo.com', '345-344', 'CURRENT_DATE');
        $this->PEOPLE[] = new Person(
            4, 
            "Borat", 
            'Borat', 
            'Kazak', 
            'borat@yahoo.kz', '123-432', 'CURRENT_DATE');
        $this->PEOPLE[] = new Person(
            5, 
            "Larry", 
            'David', 
            'LA', 
            'larry@david.com', '123-432', 'CURRENT_DATE');
        $this->PEOPLE[] = new Person(
            6, 
            "Jerry", 
            'Seinfeld', 
            'New York', 
            'jerry@seinfeld.com', '123-432', 'CURRENT_DATE');
        $this->PEOPLE[] = new Person(
            7, 
            "Bill", 
            'Burr', 
            'New York', 
            'bill@burr.com', '123-432', 'CURRENT_DATE');        
        
        foreach ($this->PEOPLE as $person){
            $db->addPerson($person);
        }

        // Add User objects.
        $this->USERS["joeShmoe"] = new User(
            "joeShmoe", '1234', 'a', 1, new Date(Month::February, 2, 2015));
        $this->USERS["bertReynolds"] = new User(
            "bertReynolds", '1234', 'd', 2, new Date(Month::February, 2, 2015));
        $this->USERS["chetManly"] = new User(
            "chetManly", '1234', 'p', 3, new Date(Month::February, 2, 2015));
        $this->USERS["Ilike"] = new User(
            "Ilike", '1234', 'p', 4, new Date(Month::February, 2, 2015));
        $this->USERS["purtygood"] = new User(
            "purtygood", '1234', 'p', 5, new Date(Month::February, 2, 2015));
        $this->USERS["billionaireCommedian"] = new User(
            "billionaireCommedian", '1234', 'r', 6, new Date(Month::February, 2, 2015));
        $this->USERS["irishNonDrunk"] = new User(
            "irishNonDrunk", '1234', 'r', 7, new Date(Month::February, 2, 15));

        foreach ($this->USERS as $user){
            $db->addUser($user);
        }

        // Add Family doctors.
        $this->FAMILY_DOCTORS[] = new FamilyDoctor(2, 3);
        $this->FAMILY_DOCTORS[] = new FamilyDoctor(2, 4);
        $this->FAMILY_DOCTORS[] = new FamilyDoctor(2, 5);       
        
        foreach ($this->FAMILY_DOCTORS as $fd){
            $db->addFamilyDoctor($fd);
        }

        $this->RECORDS[] = new RadiologyRecord(
            1, 
            3,
            2, 
            7, 
            'aids', 
            new Date(Month::February, 2, 2015),
            new Date(Month::February, 7, 2015), 'positive', 
            'not hiv but full blown aids.');
        $this->RECORDS[] = new RadiologyRecord(
            2, 
            4, 
            2, 
            6, 
            'hiv', 
            new Date(Month::February, 5, 2015), 
            new Date(Month::February, 21, 2015), 'negative', 
            'brought to you buy durex.');
        $this->RECORDS[] = new RadiologyRecord(
            3, 
            5, 
            2, 
            6, 
            'statically discharge', 
            new Date(Month::February, 10, 2015), 
            new Date(Month::April, 21, 2015), 
            'positive', 'you have been statically discharge of service.');

        foreach ($this->RECORDS as $r){
            $db->addRadiologyRecord($r);
        }
    }

    protected function tearDown(){
        $db = Database::instance();  // Acquire database instance.
        
        // Deallocate database on reverse order of intialization.        

        foreach ($this->RECORDS as $r){
            $db->removeRadiologyRecord($r->recordID);
        }

        foreach ($this->FAMILY_DOCTORS as $fd){
            $db->removeFamilyDoctor($fd);
        }

        foreach ($this->USERS as $user){
            $db->removeUser($user->userName);
        }
        
        foreach ($this->PEOPLE as $person){
            $db->removePerson($person->personID);
        }
    }

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
        // Ensure that the admin record, $this->USERS[0], can access all the recordsd, 
        // $this->RECORDS.
        $search = new Search('joeShmoe', '1234');
        $this->assertEquals(
            True, arraysetCompare($this->RECORDS, 
            $search->searchWithSecurityFilter()));
                

        // Ensure that each patients can only access records they are in.       
        $search = new Search('chetManly', '1234');
        $this->assertEquals(
            True, arraySetCompare(
                $search->searchWithSecurityFilter(), 
                array($this->RECORDS[0])));

        $search = new Search('Ilike', '1234');
        $this->assertEquals(
            True, arraySetCompare(
                $search->searchWithSecurityFilter(),
                array($this->RECORDS[1])));

        $search = new Search('purtygood', '1234');
        $this->assertEquals(
            True, arraySetCompare(
                $search->searchWithSecurityFilter(),                
                array($this->RECORDS[2])));
        
        // Ensure that each doctor can only access records of their patients.
        $search = new Search('bertReynolds', '1234');
        $this->assertEquals(
            True, arraySetCompare(
                $search->searchWithSecurityFilter(),
                array($this->RECORDS[0], $this->RECORDS[1], $this->RECORDS[2])));

        // Ensure that each that radiologist can access the records they took.
        $search = new Search('billionaireCommedian', '1234');
        $this->assertEquals(
            True, arraySetCompare($search->searchWithSecurityFilter(),
            array($this->RECORDS[1], $this->RECORDS[2])));

        $search = new Search('irishNonDrunk', '1234');
        $this->assertEquals(
            True, arraySetCompare($search->searchWithSecurityFilter(), 
            array($this->RECORDS[0])));
    }

    /**
     * @class Search
     * @test Given a list of keywords K, and records R (any records), filter tuple
     *       that contains all keywords in K. i.e.
     *       For all k in K, x in R, there exist a column j in x, s.t. x[j] = k.
     *       
     *       Note that x[j] = k, is actually EditDistance[x[j], k] <= 1. This is 
     *       to allow some mistakes.
     */
    public function searchWithKeywordFilter(){
        $search = new Search('joeShmoe', '1234');
        $this->assertEquals(
            True, 
            arraySetCompare(
                $search->searchWithKeywordFilter(array('durex')), array($this->RECORDS[1])));
    }
    
    /**
     * @class Search
     * @test Ensure that the ranking is:
     *       Rank(record_id) = 6*frequency(patient_name) + 
     *                         3*frequency(diagnosis) + 
     *                         frequency(description)
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