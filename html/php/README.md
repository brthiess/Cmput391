# PHP Module

This folder contains the php modules (Business Tier of 3-Tier architecture).

## Modules:
* setup.sql: Contains SQL schema and PL/SQL functions.
* common.php: Functions that don't are small enough to not deserve their own specific module.
* User.php: Encapsulate the SQL *users* schema.
* Search.php: Search module.
* RadiologyRecord.php: Encapsulate the SQL *radiology_record* schema.
* Person.php: Encapsulate the SQL *persons* schema.
* FamilyDoctor.php: Encapsulate the SQL *family_doctor* schema.
* Date.php: Encapsulate the date object. This is so the user don't have to worry about date format between sql and php.
* Database.php: A singleton that directly communicates with the Database tier, i.e. acts as a proxy to db.

## Search Module Methods Notes:
*(This is just a note so I have something to talk about my modules in demo.)*

Requirements:
1. Search condition: (a) keywords, and/or (b) time periods.
2. All records are accessible to a give user.
3. If order is by timming, have an option of (a) descending or (b) ascending.
4. If order is by ranking, only descending order.

With these requirements, we can derive the methods needed by creating a dependency tree:
*

Methods that satisfy the requirements are the following:
1. getRadiologyRecords: Get records that accessible to a user with respect to his/her user type.
2. searchWithKeyWordsByRank: Search with key words by rank.
3. searchWithKeyWordsByTime: Search with key words by time.
4. searchWithPeriodByTime: Search with period by time.
5. searchWithKPByTime: Search with keywords and period by time.
6. searchWithKPByRank: Search with keywords and period by rank.


## Notes:
* For all relation, reserve id 0-100 for testing purposes. This is so testing won't interrupt relational instances. i.e.
  No need to do ```DROP``` operations.
* To use Database.php, set the three constant variables above the file with your connection info, e.g.:
```
const USER_NAME = 'system';
const PASS = 'oracle';
const CONNECTION_STRING = "localhost:49161/xe";
```
**Since installing oracle is a pain and connecting to school oracle is not possible (as far as I know), I recommend
using Docker (light vm) or a fully fledge vm.**

## TODO:

1. ~~For Search module, perform the intersect before ordering, not the other way around.~~