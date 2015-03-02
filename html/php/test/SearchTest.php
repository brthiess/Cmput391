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
        $db = Database::instance();  // Acquire database instance
        
        // Add Person objects.
        $this->PEOPLE[] = new Person(
            1, 
            "Joe", 
            'Shmoe', 
            '666 Helldrive, Hell Prairie', 
            'shmoe@hell.h', '677-8081');
        $this->PEOPLE[] = new Person(
            2, 
            "Sterling", 
            'Archer', 
            'CIA branch', 
            'casablumpkin@archer.com', '345-344');
        $this->PEOPLE[] = new Person(
            3, 
            "Cyril", 
            'Figgis', 
            'CIA branch', 
            'chetmanly@yahoo.com', '345-344');
        $this->PEOPLE[] = new Person(
            4, 
            "Borat", 
            'Borat', 
            'Kazak', 
            'borat@yahoo.kz', '123-432');
        $this->PEOPLE[] = new Person(
            5, 
            "Larry", 
            'David', 
            'LA', 
            'larry@david.com', '123-432');
        $this->PEOPLE[] = new Person(
            6, 
            "Jerry", 
            'Seinfeld', 
            'New York', 
            'jerry@seinfeld.com', '123-432');
        $this->PEOPLE[] = new Person(
            7, 
            "Bill", 
            'Burr', 
            'New York', 
            'bill@burr.com', '123-432');
        
        foreach ($this->PEOPLE as $person){
            $db->addPerson($person);
        }

        // Add User objects.
        $this->USERS["joeShmoe"] = new User(
            "joeShmoe", '1234', 'a', 1, new Date(Month::February, 3, 2015));
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
        
        /*foreach ($this->RECORDS as $r){
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
            }*/
    }

    public function test01(){
    }
}

?>