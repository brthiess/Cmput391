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


## TODO:

1. Discuss the use of Database module. Should it be here just for testing purposes, or make it a standard for
   encapsulating SQL Queries.
2. Since equality of SQL and PHP data types are critical, create data converter behind the constructor. For instance,
   SQL Date standard is 'DD-MON-YY' which is kinda hard to remember. To truly encapsulate and forget about difference,
   create a Date object that will contain *Day, Month, Year* fields (one can easily extend this for timestamps), and
   ensure full encapsulation (all private members), so access everything through accessors. We can enforce these 
   rules in accessors.
3. Speaking of accessors, discuss wether we should encapsulate everything (get rid of all public data types) and access
   everything through accessors. I personally want to be consistent, but I have worked with folks who hate this strict
   OOP mentality. Anyway, this is a standard we can vote on.
4. Convert C++ MSD sort (Maximum Significant Digit) sort for sublinear string sorting.
   