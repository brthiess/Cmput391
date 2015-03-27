<?php

/**
 * SearchTest.php
 */

include_once 'TestConstants.php';

include_once '../Search.php';
include_once '../Database.php';
include_once '../Date.php';
include_once '../PacsImages.php';

/*!@class Search
 * @test Unit tests for the Search module. @see Search
 * 
 */
class SearchTest extends PHPUnit_Framework_TestCase{
    private $_people = null;
    private $_users = null;
    private $_familyDoctors = null;
    private $_records = null;
    private $_images = null;
    
    protected function setUp(){
        $db = Database::instance();  // Acquire database instance

        global $PEOPLE, $USERS, $FAMILY_DOCTORS, $RECORDS, $IMAGES;
        $this->_people = $PEOPLE;
        $this->_users = $USERS;
        $this->_familyDoctors = $FAMILY_DOCTORS;
        $this->_records = $RECORDS;
        $this->_images = $IMAGES;
        
        // Add Person objects.
        foreach ($this->_people as $person){
            $db->addPerson($person);
        }

        // Add User objects.
        foreach ($this->_users as $user){
            $db->addUser($user);
        }

        // Add Family doctors.
        foreach ($this->_familyDoctors as $fd){
            $db->addFamilyDoctor($fd);
        }
        
        // Add records.
        foreach ($this->_records as $r){
            $db->addRadiologyRecord($r);
        }

        // Add images.
        foreach ($this->_images as $r){
            $db->insertImage($r);
        }
    }

    protected function tearDown(){
        $db = Database::instance();  // Acquire database instance.
        
        // Deallocate database on reverse order of intialization.         
        foreach ($this->_images as $r){
            $db->removeImage($r->imageID);
        }

        foreach ($this->_records as $r){
            $db->removeRadiologyRecord($r->recordID);
        }

        foreach ($this->_familyDoctors as $fd){
            $db->removeFamilyDoctor($fd);
        }
        
        foreach ($this->_users as $user){
            $db->removeUser($user->userName);
        }

        foreach ($this->_people as $person){
            $db->removePerson($person->personID);
        }
    }

    public function testGetRadiologyRecords(){
        // Admin test.
        $adminSearch = new Search("joeShmoe");
        $this->assertEquals(
            arraySetCompare($adminSearch->getRadiologyRecords(),
                            $this->_records), true);

        // Doctor Test.
        $doctorSearch = new Search("bertReynolds");
        $this->assertEquals(
            arraySetCompare($doctorSearch->getRadiologyRecords(),
                            $this->_records), true);

        // Radiologist Test
        $radiologistSearch01 = new Search("billionaireCommedian");
        $this->assertEquals(
            arraySetCompare($radiologistSearch01->getRadiologyRecords(),
                            array($this->_records[1], $this->_records[2])), true);        
        $radiologistSearch02 = new Search("irishNonDrunk");
        $this->assertEquals(
            arraySetCompare($radiologistSearch02->getRadiologyRecords(),
                            array($this->_records[0])), true);
        
        // Patient Test
        $patientSearch = new Search("chetManly");
        $this->assertEquals(array($this->_records[0]),
                            $patientSearch->getRadiologyRecords());
        $this->assertNotEquals(array($this->_records[0], $this->_records[1]),
                               $patientSearch->getRadiologyRecords());
        
    }
    
    public function testSearchWithKeywordsByRankFullHit(){
        $adminSearch = new Search("joeShmoe");
        // All record hit.
        $this->assertEquals(
            arraySetCompare($adminSearch->searchWithKeywordsByRank("positive|negative|super"),
                            $this->_records), true);
    }

    public function testSearchWithKeywordsByRankPatientNameHit(){
        // Admin test.
        $adminSearch = new Search("joeShmoe");
        
        // First record hit via patient name keyword.
        $this->assertEquals($adminSearch->searchWithKeywordsByRank("Cyril|Figgis"),
                            array($this->_records[0]));
        // Second record hit via patient name keyword.
        $this->assertEquals($adminSearch->searchWithKeywordsByRank("Borat"),
                            array($this->_records[1]));
        // Third record hit via patient name keyword.
        $this->assertEquals($adminSearch->searchWithKeywordsByRank("Larry|David"),
                            array($this->_records[2]));
    }

    public function testSearchWithKeywordsByRankDiagnosisHit(){
        // Admin test.
        $adminSearch = new Search("joeShmoe");
        
        // First record hit via diagnosis.
        $this->assertEquals(
            arraySetCompare(array($this->_records[2], $this->_records[0]), 
                            $adminSearch->searchWithKeywordsByRank("positive")),
            true);
        // Second record hit via diagnosis.
        $this->assertEquals(array($this->_records[1]),
                            $adminSearch->searchWithKeywordsByRank("negative"));
        // Third record hit via diagnosis.
        $this->assertEquals(array($this->_records[2]),
                            $adminSearch->searchWithKeywordsByRank("genius"));
    }

    public function testSearchWithKeywordsByTime(){
        // Admin test.
        $adminSearch = new Search("joeShmoe");
        
        // Ascending
        $this->assertEquals($this->_records,
                            $adminSearch->searchWithKeywordsByTime("positive|negative", false));
        // Descending
        $this->assertNotEquals($this->_records,
                               $adminSearch->searchWithKeywordsByTime("positive|negative", true));
        $this->assertEquals(array_reverse($this->_records),
                            $adminSearch->searchWithKeywordsByTime("positive|negative", true));
    }

    public function testSearchWithPeriodByTime(){
        // Admin test.
        $adminSearch = new Search("joeShmoe");
        $temp = $this->_records;
        
        // All hit.
        // Ascending.
        $this->assertEquals($this->_records,
                            $adminSearch->searchWithPeriodByTime(
                                new Date(Month::January, 2, 2015),
                                new Date(Month::December, 31, 2015),
                                false));
        // Ascending restrict to first two records.
        $this->assertEquals(array($temp[0], $temp[1]),
                            $adminSearch->searchWithPeriodByTime(
                                new Date(Month::January, 2, 2015),
                                new Date(Month::March, 23, 2015),
                                false));
        // Descending.
        $this->assertEquals(array_reverse($this->_records),
                            $adminSearch->searchWithPeriodByTime(
                                new Date(Month::January, 2, 2015),
                                new Date(Month::December, 31, 2015),
                                true));
        // Descending restrict to first two records.
        $this->assertEquals(array_reverse(array($temp[0], $temp[1])),
                            $adminSearch->searchWithPeriodByTime(
                                new Date(Month::January, 2, 2015),
                                new Date(Month::March, 23, 2015),
                                true));        
    }
    
    public function testSearchWithKPByRank(){
        // Admin test.
        $adminSearch = new Search("joeShmoe");
        $this->assertEquals(array($this->_records[0]),
                            $adminSearch->searchWithKPByRank(
                                "cyril|figgis",
                                new Date(Month::January, 2, 2015),
                                new Date(Month::December, 31, 2015)));
    }

    public function testSearchWithKPByTime(){
        // Admin test.
        $adminSearch = new Search("joeShmoe");
        // Ascending.
        $this->assertEquals(array($this->_records[0], $this->_records[1]),
                            $adminSearch->searchWithKPByTime(
                                "cyril|figgis|negative",
                                new Date(Month::January, 2, 2015),
                                new Date(Month::December, 31, 2015),
                                false));
    }

    public function testImageInsertDeleteRetrieval(){
        $db = Database::instance();  // Acquire database instance
        //var_dump($db->getDataCube(2));
    }
}

?>