# PHP Module

This folder contains the php modules (Business Tier of 3-Tier architecture).

## Modules:
* setup.sql: Contains SQL schema and PL/SQL functions.
* common.php: Functions that don't are small enough to not deserve their own specific module.
* User.php: Encapsulate the SQL *users* schema.
* Search.php: Search module. Example:

  ```
  // Throws an exception if not a valid "userName" is not valid.
  $search = new Search("userName");

  // Returns all records that contain "awesome" that is accessible to
  // whatever user type is associated with "userName". e.g.
  // if "userName" is associated to an admin, then it can
  // access all records with keyword "awesome".
  $search->searchWithKeywordsByRank("awesome");
  
  // See Search.php for more methods and documentation.
  ```
  
* RadiologyRecord.php: Encapsulate the SQL *radiology_record* schema.
* Person.php: Encapsulate the SQL *persons* schema.
* FamilyDoctor.php: Encapsulate the SQL *family_doctor* schema.
* Date.php: Encapsulate the date object. This is so the user don't have to worry about date format between sql and php.

  ```
  $date01 = new Date(Month::March, 3, 2015);  // Recommended initialization style.
  $date02 = new Date(Month::March, 3, 15);  // Year truncated.
  $date03 = new Date("03-MAR-15");  // Standard oracle date format.

  // These are all equals.
  $date01 == $date02;
  $date02 == $date03;

  print $date01 . PHP_EOL;  // $date01 is automatically converted to Oracle date format string.
  ```
  
* Database.php: A singleton that directly communicates with the Database tier, i.e. acts as a proxy to db.
* UserManagement.php: allows a system administrator to manage (to enter or update) the user information, i.e., the information stored in tables
  		      users,persons, family_doctor.

  ```
  // Using constructor.
  $um = NULL;
  try{
    $um = new UserManagement("adminUserName");
  }catch(Exception $e){	
    // "adminUserName" doesn't exit or is not a username of an admin.
  }
  ```

## Views to be considered:
1. **Admin Profile Page:** I saw in the views that there is a profile.php. Since Admin have different
   requirements from other users, it needs soemthing different. The following are requirements for the Admin
   Profile page (not limited to, thus add whatever is discovered is necessary along the way):
   
   * A link to UserManagement page (utilizes *UserManagement.php*) see UserManagement page below.

2. **UserManagement Page:** This is just a suggestion but basically, it must be able to do the
   following (basically most of the methods in *UserManagement.php*), implement it whatever way
   you want:
   * For the update, I suggest having a way to first enter the primary key (userName or
     person_id) and retrieve the corresponding data, before editing and changing them.
   * update user
   * remove user
   * add Person
   * update Person
   * add Family Doctor
   * remove Family Doctor

3. **Search Page:** Utilizing the Search.php, it retrieves a list of Radiology_Records. Note that,
    *"Each record displayed shall contain all the information, including the list of thumbnails of medical images, of the record."*, thus try to accomodate the multiple thumbnails per record. For images,
    there are methods for it, but they are all bulks, thus could result in a performance overhead.
    I will create methods to retrieve only thumbnails if needed. So for now, just have some 
    dummy image.

4. **Record Page:** If a record is selected, a page concerning the record is displayed.   
   

## Search Module Methods Notes:
*(This is just a note so I have something to talk about my modules in demo.)*

Requirements:

1. Search condition: (a) keywords, and/or (b) time periods.
2. All records are accessible to a give user.
3. If order is by timming, have an option of (a) descending or (b) ascending.
4. If order is by ranking, only descending order.



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