# PHP Module

This folder contains the php modules (Business Tier of 3-Tier architecture).

## Test

The *test* folder contains unit tests for the modules. If you are going to write unit tests,
there's already a **phpunit.phar** in the test, to write and run tests, I recommend reading

https://phpunit.de/getting-started.html

1. SearchTest: Testing module responsible for testing Search module.

## Requirements

### Search Module:
    
1. Search Filter By Security Rules.
2. Search By Keywords.
3. Sort By Most Recently First
4. Sort By Most Recently Last
5. Sort By some specified rule.

## Notes:

* For all relation, reserve id 0-100 for testing purposes. This is so testing won't interrupt relational instances. i.e.
  No need to do ```DROP``` operations.

## TODO:

1. ~~Discuss the use of Database module. Should it be here just for testing purposes, or make it a standard for
   encapsulating SQL Queries.~~ An agreement has been reached.
2. Create a Date class that will contain month, day, and, year elements. This class will handle the responsibility of
   representing Dates so we don't have to worry about formats. for instance, by worrying only about Date class, 
   we don't have to worry wether DD-MON-YY, DD-MM-YY, or DD-MM-YYYY, ... is the date format.
3. Speaking of accessors, discuss wether we should encapsulate everything (get rid of all public data types) and access
   everything through accessors. I personally want to be consistent, but I have worked with folks who hate this strict
   OOP mentality. Anyway, this is a standard we can vote on.
4. Convert C++ MSD sort (Maximum Significant Digit) sort for sublinear string sorting.
5. Ask TAs or Prof if keywords have to be concern with Person Schema and not just radiology_record sechema.