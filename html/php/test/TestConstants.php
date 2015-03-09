<?php
/**
 * TestConstants.php
 *
 * Contains constants used for testing. 
 */

include_once "../Person.php";
include_once "../User.php";
include_once "../RadiologyRecord.php";
include_once "../FamilyDoctor.php";
include_once '../Date.php';

$PEOPLE = array(
    new Person(1, "Joe", "Shmoe", "123 Edm", "shmoe@joe.h", "123-456"),
    new Person(2, "Sterling", "Archer", "CIA branch", "casablumpkin@archer.com", "345-344"),
    new Person(3, "Cyril", "Figgis", "CIA branch", "chetmanly@yahoo.com", "345-344"),
    new Person(4, "Borat", "Borat", "Kazak", "borat@yahoo.kz", "123-432"),
    new Person(5, "Larry", "David", "LA", "larry@david.com", "123-432"),
    new Person(6, "Jerry", "Seinfeld", "New York", "jerry@seinfeld.com", "123-432"),
    new Person(7, "Bill", "Burr", "New York", "bill@burr.com", "123-432")
);

$USERS = array(
    new User("joeShmoe", '1234', 'a', 1, new Date(Month::February, 3, 2015)),
    new User("bertReynolds", '1234', 'd', 2, new Date(Month::February, 2, 2015)),
    new User("chetManly", '1234', 'p', 3, new Date(Month::February, 2, 2015)),
    new User("Ilike", '1234', 'p', 4, new Date(Month::February, 2, 2015)),
    new User("purtygood", '1234', 'p', 5, new Date(Month::February, 2, 2015)),
    new User("billionaireCommedian", '1234', 'r', 6, new Date(Month::February, 2, 2015)),
    new User("irishNonDrunk", '1234', 'r', 7, new Date(Month::February, 2, 15)),
);

$FAMILY_DOCTORS = array(
    new FamilyDoctor(2, 3),
    new FamilyDoctor(2, 4),
    new FamilyDoctor(2, 5),
);

$RECORDS = array(
    new RadiologyRecord(1, 3, 2, 7, 'danger zone', 
                        new Date(Month::February, 2, 2015),
                        new Date(Month::February, 7, 2015), 'positive', 
                        'zone of danger cannot be stopped.'),
    new RadiologyRecord(2, 4, 2, 6, 'alochol deprivation test',
                        new Date(Month::February, 5, 2015), 
                        new Date(Month::February, 21, 2015),  'negative', 
                        'Bourbon is not strong enough to lure.'),
    new RadiologyRecord(3, 5, 2, 6, 'brain scan', 
                        new Date(Month::February, 10, 2015), 
                        new Date(Month::April, 21, 2015), 'super genius positive',
                        'client exceed single digit IQ.')
);
?>