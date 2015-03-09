# PHP Module

This folder contains the php modules (Business Tier of 3-Tier architecture).

## Test

The *test* folder contains unit tests for the modules. If you are going to write unit tests,
there's already a **phpunit.phar** in the test, to write and run tests, I recommend reading

https://phpunit.de/getting-started.html

1. SearchTest: Testing module responsible for testing Search module.

## Requirements:

### Search Module:
    
1. Search Filter By Security Rules.
2. Search By Keywords.
3. Sort By Most Recently First
4. Sort By Most Recently Last
5. Sort By some specified rule.

### Data Analysis Module:

## Notes:
* For all relation, reserve id 0-100 for testing purposes. This is so testing won't interrupt relational instances. i.e.
  No need to do ```DROP``` operations.
* To use Database.php, set the three constant variables above the file with your connection info, e.g.:
```
const USER_NAME = 'system';
const PASS = 'oracle';
const CONNECTION_STRING = "localhost:49161/xe";
```

## TODO:

1. ~~For Search module, perform the intersect before ordering, not the other way around.~~