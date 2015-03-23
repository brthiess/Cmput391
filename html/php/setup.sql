/**
 *  File name:  setup.sql
 *  Function:   to create the initial database schema for the CMPUT 391 project,
 *              Winter Term, 2015
 *  Author:     Prof. Li-Yan Yuan
 *
 *  Modified for purposes of this project.
 */
DROP FUNCTION searchWithKPByTime;
DROP FUNCTION searchWithKPByRank;
DROP FUNCTION searchWithKeyWordsByRank;
DROP FUNCTION searchWithKeyWordsByTime;
DROP FUNCTION searchWithPeriodByTime;
DROP FUNCTION getRadiologyRecords;
DROP FUNCTION insertPerson;
DROP FUNCTION insertRadiologyRecord;
DROP FUNCTION getFTImgCntTtAndP;
DROP TYPE ft01_t_t;
DROP TYPE ft01_t;
DROP TYPE persons_rt;
DROP TYPE radiology_record_rt_t;
DROP TYPE radiology_record_rt;
DROP INDEX personsIndexLastName;
DROP INDEX personsIndexFirstName;
DROP INDEX recordIndexDescription;
DROP INDEX recordIndexDiagnosis;
DROP INDEX recordIndexTestType;
DROP SEQUENCE records_seq;
DROP SEQUENCE persons_seq;
DROP TABLE family_doctor;
DROP TABLE pacs_images;
DROP TABLE radiology_record;
DROP TABLE users;
DROP TABLE persons;

/*
 *  To store the personal information
 */
CREATE TABLE persons (
   person_id int,
   first_name varchar(24),
   last_name  varchar(24),
   address    varchar(128),
   email      varchar(128),
   phone      char(10),
   PRIMARY KEY(person_id),
   UNIQUE (email)
);

/*
 *  To store the log-in information
 *  Note that a person may have been assigned different user_name(s), depending
 *  on his/her role in the log-in  
 */
CREATE TABLE users (
   user_name varchar(24),
   password  varchar(24),
   class     char(1),
   person_id int,
   date_registered date,
   CHECK (class in ('a','p','d','r')),
   PRIMARY KEY(user_name),
   FOREIGN KEY (person_id) REFERENCES persons
);

/*
 *  to indicate who is whose family doctor.
 */
CREATE TABLE family_doctor (
   doctor_id    int,
   patient_id   int,
   FOREIGN KEY(doctor_id) REFERENCES persons,
   FOREIGN KEY(patient_id) REFERENCES persons,
   PRIMARY KEY(doctor_id,patient_id)
);

/*
 *  to store the radiology records
 */
CREATE TABLE radiology_record (
   record_id   int,
   patient_id  int,
   doctor_id   int,
   radiologist_id int,
   test_type   varchar(24),
   prescribing_date date,
   test_date    date,
   diagnosis    varchar(128),
   description   varchar(1024),
   PRIMARY KEY(record_id),
   FOREIGN KEY(patient_id) REFERENCES persons,
   FOREIGN KEY(doctor_id) REFERENCES  persons,
   FOREIGN KEY(radiologist_id) REFERENCES  persons
);

/*
 *  to store the pacs images
 */
CREATE TABLE pacs_images (
   record_id   int,
   image_id    int,
   thumbnail   blob,
   regular_size blob,
   full_size    blob,
   PRIMARY KEY(record_id,image_id),
   FOREIGN KEY(record_id) REFERENCES radiology_record
);

/***********************************************************************
 * From here and below, are custom SQL statements. Freely append yours.*
 **********************************************************************/

 INSERT INTO persons (person_id, first_name, last_name, address,  email, phone) 
 VALUES (
 10,
 'Brad',
 'Thiessen',
 '753 Revell Cr.',
 'brthiess@ualberta.ca',
 '7809224343' 
 );
 
 INSERT INTO users VALUES (
   'brtlrt',
   'jikipol',
   'a',
   10,
   TO_DATE('JAN-05-15', 'mm/dd/yy')
);
 
/**
 * person_seq
 *
 * Used to automate ids for persons relation. 
 * e.g. person_seq.nextval to access next value.
 *
 * Note: Values 1-100 are used for testing purposes.
 */
CREATE SEQUENCE persons_seq
       MINVALUE 1
       START WITH 101
       INCREMENT BY 1
       CACHE 10;

/**
 * records_seq
 *
 * Used to automate ids for records relation.
 * e.g. records_seq.nextval to access next value.
 *
 * Note: Values 1-100 are used for testing purposes.
 */
CREATE SEQUENCE records_seq
       MINVALUE 1
       START WITH 101
       INCREMENT BY 1
       CACHE 10;

/**
 * Indices on string attributes of radiology_record.
 */
CREATE INDEX recordIndexTestType ON radiology_record(test_type) INDEXTYPE IS CTXSYS.CONTEXT 
       PARAMETERS ('SYNC ( ON COMMIT)');
CREATE INDEX recordIndexDiagnosis ON radiology_record(diagnosis) INDEXTYPE IS CTXSYS.CONTEXT
       PARAMETERS ('SYNC ( ON COMMIT)');
CREATE INDEX recordIndexDescription ON radiology_record(description) INDEXTYPE IS CTXSYS.CONTEXT
       PARAMETERS ('SYNC ( ON COMMIT)');
CREATE INDEX personsIndexFirstName ON persons(first_name) INDEXTYPE IS CTXSYS.CONTEXT
       PARAMETERS ('SYNC ( ON COMMIT)');
CREATE INDEX personsIndexLastName ON persons(last_name) INDEXTYPE IS CTXSYS.CONTEXT
       PARAMETERS ('SYNC ( ON COMMIT)');

/**
 * radiology_record_rt is a row tuple type.
 */
CREATE TYPE radiology_record_rt AS OBJECT(
   record_id   int,
   patient_id  int,
   doctor_id   int,
   radiologist_id int,
   test_type   varchar(24),
   prescribing_date date,
   test_date    date,
   diagnosis    varchar(128),
   description   varchar(1024)
);
/

/**
 * @param rr radiology record tuple to be inserted.
 * @return autoID 'TRUE' to automate primary key (record_id). Otherwise, rr.record_id will be the primary key.
 */
CREATE FUNCTION insertRadiologyRecord(rr IN radiology_record_rt, autoID IN VARCHAR2) RETURN INTEGER
       IS
       primaryKey INTEGER := -1;
       BEGIN
       
       IF UPPER(autoID)='TRUE' THEN
       	  primaryKey := records_seq.nextVal;
       ELSE
          primaryKey := rr.record_id;
       END IF;
       
       INSERT INTO radiology_record VALUES(primaryKey,
       	      	   	     	           rr.patient_id,
			     	  	   rr.doctor_id,
					   rr.radiologist_id,
					   rr.test_type,
					   rr.prescribing_date,
					   rr.test_date,
					   rr.diagnosis,
					   rr.description);
       
       return primaryKey;
       
       END;
/

/**
 * radiology_record_rt_t table of row tuple. This is used to hold
 * the result for the query functions that returns radiology_record tuples.
 */
CREATE TYPE radiology_record_rt_t IS TABLE OF radiology_record_rt;
/

CREATE TYPE persons_rt AS OBJECT(
   person_id int,
   first_name varchar(24),
   last_name  varchar(24),
   address    varchar(128),
   email      varchar(128),
   phone      char(10)
);
/

/**
 * @param person Person tuple to be inserted.
 * @param autoID 'TRUE' if you want the primary key or person_id to be generated automatically.
 * @return person_id of the newly inserted tuple.
 */
CREATE FUNCTION insertPerson(person IN persons_rt, autoID IN VARCHAR2) return INTEGER
       IS
       primaryKey INTEGER := -1;
       BEGIN
       
       IF UPPER(autoID)='TRUE' THEN
       	    primaryKey := persons_seq.nextVal;
       ELSE
          primaryKey := person.person_id;
       END IF;
       
       INSERT INTO persons VALUES(primaryKey,
       	      	   	     	  person.first_name,
			     	  person.last_name,
			     	  person.address,
			     	  person.email,
			     	  person.phone);
       
       return primaryKey;

       END;
/

/**
 * @param userName
 * @return table of radiology_records that the given user can access.
 */
CREATE FUNCTION getRadiologyRecords(userName IN VARCHAR2) return radiology_record_rt_t
       IS
       l_tab radiology_record_rt_t := radiology_record_rt_t();
       userRow users%ROWTYPE := NULL;
       BEGIN
       
       Select * INTO userRow
       FROM users WHERE user_name=userName;
       
       CASE userRow.class
       	    WHEN 'a' THEN
       	    FOR t in
       	    (SELECT rr.*
             FROM radiology_record rr) LOOP
	     	   l_tab.extend;
		   	     	   l_tab(l_tab.last) := radiology_record_rt(
	           		     t.record_id, t.patient_id, t.doctor_id, t.radiologist_id, t.test_type,
		 		     t.prescribing_date, t.test_date, t.diagnosis, t.description);
	    END LOOP;

       	    WHEN 'p' THEN
       	    FOR t in
       	    (SELECT rr.*
             FROM radiology_record rr JOIN users u ON rr.patient_id=u.person_id
             WHERE u.person_id=userRow.person_id) LOOP
	     	   l_tab.extend;
	     	   l_tab(l_tab.last) := radiology_record_rt(
	           		     t.record_id, t.patient_id, t.doctor_id, t.radiologist_id, t.test_type,
		 		     t.prescribing_date, t.test_date, t.diagnosis, t.description);
	    END LOOP;

       	    WHEN 'd' THEN
       	    FOR t in
       	    (SELECT rr.*
             FROM radiology_record rr JOIN users u ON rr.doctor_id=u.person_id
             WHERE u.person_id=userRow.person_id) LOOP
	     	   l_tab.extend;
	     	   l_tab(l_tab.last) := radiology_record_rt(
	           		     t.record_id, t.patient_id, t.doctor_id, t.radiologist_id, t.test_type,
		 		     t.prescribing_date, t.test_date, t.diagnosis, t.description);
	    END LOOP;

       	    WHEN 'r' THEN
       	    FOR t in
       	    (SELECT rr.*
             FROM radiology_record rr JOIN users u ON rr.radiologist_id=u.person_id
             WHERE u.person_id=userRow.person_id) LOOP
	     	   l_tab.extend;
	     	   l_tab(l_tab.last) := radiology_record_rt(
	           		     t.record_id, t.patient_id, t.doctor_id, t.radiologist_id, t.test_type,
		 		     t.prescribing_date, t.test_date, t.diagnosis, t.description);
	    END LOOP;
	    
       	    ELSE RAISE_APPLICATION_ERROR(-20000, 'user type not recognized.');
       END CASE;

       return l_tab;
       
       END;
/

/**
 * @param userName userName of the user using search module.
 * @param keywords string of keywords.
 * @return table of radiology_records that matches the given keywords, ordered by rank and is accessible from user with corresponding userName.
 */
CREATE FUNCTION searchWithKeywordsByRank(userName IN VARCHAR2, keywords IN VARCHAR2) return radiology_record_rt_t
       IS
       l_tab radiology_record_rt_t := radiology_record_rt_t();
       BEGIN

       FOR t in 
       (SELECT rr.*
        FROM radiology_record rr JOIN persons p ON rr.patient_id=p.person_id
        WHERE (CONTAINS(p.first_name, keywords, 1) > 0 OR
               CONTAINS(p.last_name, keywords, 2) > 0 OR
      	       CONTAINS(rr.diagnosis, keywords, 3) > 0 OR
      	       CONTAINS(rr.description, keywords, 4) > 0) AND
	       rr.record_id IN (SELECT r2.record_id FROM TABLE(getRadiologyRecords(userName)) r2)
        ORDER BY 6*(SCORE(1) + SCORE(2))/2 + 3*SCORE(3) + SCORE(4) DESC) LOOP
       	     l_tab.extend;
	     l_tab(l_tab.last) := radiology_record_rt(
	         t.record_id, t.patient_id, t.doctor_id, t.radiologist_id, t.test_type,
		 t.prescribing_date, t.test_date, t.diagnosis, t.description);
       END LOOP;

       return l_tab;

      END;
/


/**
 * @param userName userName of the user using search module.
 * @param keywords string of keywords.
 * @param true for descending ordering, false otherwise.
 * @return table of radiology_records that matches the given keywords, ordered by test_date 
 *         and is accessible from user with corresponding userName.
 */
CREATE FUNCTION searchWithKeywordsByTime(userName IN VARCHAR2, keywords IN VARCHAR2, descending IN VARCHAR2)
       return radiology_record_rt_t IS
       l_tab radiology_record_rt_t := radiology_record_rt_t();
       BEGIN
       
       IF descending='TRUE' THEN
       	  FOR t in
       	  (SELECT *
           FROM TABLE(searchWithKeywordsByRank(userName, keywords)) rr
           ORDER BY rr.test_date DESC) LOOP
       	      	 l_tab.extend;
	      	 l_tab(l_tab.last) := radiology_record_rt(
	         		   t.record_id, t.patient_id, t.doctor_id, t.radiologist_id, t.test_type,
		 		   t.prescribing_date, t.test_date, t.diagnosis, t.description);
  	   END LOOP;
       ELSE
          FOR t in
       	  (SELECT *
           FROM TABLE(searchWithKeywordsByRank(userName, keywords)) rr
           ORDER BY rr.test_date ASC) LOOP
       	      	 l_tab.extend;
	      	 l_tab(l_tab.last) := radiology_record_rt(
	         		   t.record_id, t.patient_id, t.doctor_id, t.radiologist_id, t.test_type,
		 		   t.prescribing_date, t.test_date, t.diagnosis, t.description);
  	   END LOOP;
       END IF;

       return l_tab;

       END;
/

/**
 * @param userName userName of the user using search module.
 * @param d1 lowerbound of date to be included.
 * @param d2 upperbound of date to be included.
 * @param descending true for descending ordering, false otherwise.
 * @return table of radiology_records that matches the given keywords, ordered by test_date
 *         and is accessible from user with corresponding userName.
 */
CREATE FUNCTION searchWithPeriodByTime(userName IN VARCHAR2, d1 IN DATE, d2 IN DATE, descending IN VARCHAR2) 
       return radiology_record_rt_t IS
       l_tab radiology_record_rt_t := radiology_record_rt_t();
       BEGIN
       
       IF UPPER(descending)='TRUE' THEN
       	  FOR t in 
       	   (SELECT rr.*
            FROM TABLE(getRadiologyRecords(userName)) rr JOIN persons p ON rr.patient_id=p.person_id
            WHERE rr.test_date BETWEEN d1 AND d2
            ORDER BY rr.test_date DESC) LOOP
       	     	 l_tab.extend;
	     	 l_tab(l_tab.last) := radiology_record_rt(
	         		   t.record_id, t.patient_id, t.doctor_id, t.radiologist_id, t.test_type,
		 		   t.prescribing_date, t.test_date, t.diagnosis, t.description);
           END LOOP;
       ELSE
          FOR t in 
       	   (SELECT rr.*
            FROM TABLE(getRadiologyRecords(userName)) rr JOIN persons p ON rr.patient_id=p.person_id
            WHERE rr.test_date BETWEEN d1 AND d2
            ORDER BY rr.test_date ASC) LOOP
       	     	 l_tab.extend;
	     	 l_tab(l_tab.last) := radiology_record_rt(
	         		   t.record_id, t.patient_id, t.doctor_id, t.radiologist_id, t.test_type,
		 		   t.prescribing_date, t.test_date, t.diagnosis, t.description);
           END LOOP;
       END IF;

       return l_tab;

       END;
/

/**
 * @param userName userName of the user using search module.
 * @param keywords string of keywords.
 * @param d1 lowerbound of date to be included.
 * @param d2 upperbound of date to be included.
 * @return table of radiology_records that matches the given keywords, ordered by rank
 *         and is accessible from user with corresponding userName.
 */
CREATE FUNCTION searchWithKPByRank(userName IN VARCHAR2, keywords IN VARCHAR2 ,d1 IN DATE, d2 IN DATE)
       return radiology_record_rt_t IS
       l_tab radiology_record_rt_t := radiology_record_rt_t();
       BEGIN
       
       FOR t in
       	    (SELECT *
       	     FROM TABLE(searchWithKeywordsByRank(userName, keywords)) rr
       	     WHERE rr.test_date BETWEEN d1 AND d2) LOOP
       	    	  l_tab.extend;
       		  l_tab(l_tab.last) := radiology_record_rt(
       		  		    t.record_id, t.patient_id, t.doctor_id, t.radiologist_id, t.test_type,
       		  		    t.prescribing_date, t.test_date, t.diagnosis, t.description);
       END LOOP;

       return l_tab;

       END;
/

/**
 * @param userName userName of the user using search module.
 * @param keywords string of keywords.
 * @param d1 lowerbound of date to be included.
 * @param d2 upperbound of date to be included.
 * @param desencending true for descending ordering, false otherwise.
 * @return table of radiology_records that matches the given keywords, ordered by rank
 *         and is accessible from user with corresponding userName.
 */
CREATE FUNCTION searchWithKPByTime(userName IN VARCHAR2, keywords IN VARCHAR2 ,d1 IN DATE, d2 IN DATE, 
       						   descending IN VARCHAR2)
       return radiology_record_rt_t IS
       l_tab radiology_record_rt_t := radiology_record_rt_t();
       BEGIN
       
       IF UPPER(descending)='TRUE' THEN
       	  FOR t in
       	   (SELECT *
            FROM TABLE(searchWithKeywordsByRank(userName, keywords)) rr
	    WHERE rr.test_date BETWEEN d1 AND d2
            ORDER BY rr.test_date DESC) LOOP
       	      	 l_tab.extend;
	      	 l_tab(l_tab.last) := radiology_record_rt(
	         		   t.record_id, t.patient_id, t.doctor_id, t.radiologist_id, t.test_type,
		 		   t.prescribing_date, t.test_date, t.diagnosis, t.description);
  	   END LOOP;
       ELSE
          FOR t in
       	   (SELECT *
            FROM TABLE(searchWithKeywordsByRank(userName, keywords)) rr
	    WHERE rr.test_date BETWEEN d1 AND d2
            ORDER BY rr.test_date ASC) LOOP
       	      	 l_tab.extend;
	      	 l_tab(l_tab.last) := radiology_record_rt(
	         		   t.record_id, t.patient_id, t.doctor_id, t.radiologist_id, t.test_type,
		 		   t.prescribing_date, t.test_date, t.diagnosis, t.description);
  	   END LOOP;
       END IF;

       return l_tab;

       END;
/

/**
 * fact table 01 type.
 */
CREATE TYPE ft01_t AS OBJECT(
       patient_name varchar(50),
       test_type   varchar(24),
       period_date date  -- Starting date of the period in which the test is taken.
);
/

/**
 *  fact table 01 table type. (Stores a bunch of ft01_t).
 */
CREATE TYPE ft01_t_t IS TABLE OF ft01_t;
/

CREATE FUNCTION getFTImgCntTtAndP(ival IN INTEGER) RETURN ft01_t_t 
       IS
       l_tab ft01_t_t := ft01_t_t();
       BEGIN
       
       IF ival=1 THEN
          raise_application_error(-20000, 'Interval '||ival||' is not recognized.');
       ELSIF ival=2 THEN
       	    raise_application_error(-20000, 'Interval '||ival||' is not recognized.');
       ELSE
          --interval not recognized, raise an error.
	  raise_application_error(-20000, 'Interval '||ival||' is not recognized.');
       END IF;

       return l_tab;
      
       END;
/
