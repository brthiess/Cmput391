/*
 *  File name:  setup.sql
 *  Function:   to create the initial database schema for the CMPUT 391 project,
 *              Winter Term, 2015
 *  Author:     Prof. Li-Yan Yuan
 */
DROP FUNCTION searchWithKPByTime;
DROP FUNCTION searchWithKPByRank;
DROP FUNCTION searchWithKeyWordsByRank;
DROP FUNCTION searchWithKeyWordsByTime;
DROP FUNCTION searchWithPeriodByTime;
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
 * radiology_record_rt_t table of row tuple. This is used to hold
 * the result for the query functions that returns radiology_record tuples.
 */
CREATE TYPE radiology_record_rt_t IS TABLE OF radiology_record_rt;
/

/**
 * @param keywords string of keywords.
 * @return table of radiology_records that matches the given keywords, ordered by rank.
 */
CREATE FUNCTION searchWithKeywordsByRank(keywords IN VARCHAR2) return radiology_record_rt_t
       IS
       l_tab radiology_record_rt_t := radiology_record_rt_t();
       BEGIN

       FOR t in 
       (SELECT rr.*
        FROM radiology_record rr JOIN persons p ON rr.patient_id=p.person_id
        WHERE CONTAINS(p.first_name, keywords, 1) > 0 OR
              CONTAINS(p.last_name, keywords, 2) > 0 OR
      	      CONTAINS(rr.diagnosis, keywords, 3) > 0 OR
      	      CONTAINS(rr.description, keywords, 4) > 0
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
 * @param keywords string of keywords.
 * @param true for descending ordering, false otherwise.
 * @return table of radiology_records that matches the given keywords, ordered by test_date.
 */
CREATE FUNCTION searchWithKeywordsByTime(keywords IN VARCHAR2, descending IN VARCHAR2)
       return radiology_record_rt_t IS
       l_tab radiology_record_rt_t := radiology_record_rt_t();
       BEGIN
       
       IF descending='TRUE' THEN
       	  FOR t in
       	  (SELECT *
           FROM TABLE(searchWithKeywordsOrderByRank(keywords)) rr
           ORDER BY rr.test_date DESC) LOOP
       	      	 l_tab.extend;
	      	 l_tab(l_tab.last) := radiology_record_rt(
	         		   t.record_id, t.patient_id, t.doctor_id, t.radiologist_id, t.test_type,
		 		   t.prescribing_date, t.test_date, t.diagnosis, t.description);
  	   END LOOP;
       ELSE
          FOR t in
       	  (SELECT *
           FROM TABLE(searchWithKeywordsOrderByRank(keywords)) rr
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
 * @param d1 lowerbound of date to be included.
 * @param d2 upperbound of date to be included.
 * @param descending true for descending ordering, false otherwise.
 * @return table of radiology_records that matches the given keywords, ordered by test_date.
 */
CREATE FUNCTION searchWithPeriodByTime(d1 IN DATE, d2 IN DATE, descending IN VARCHAR2) 
       return radiology_record_rt_t IS
       l_tab radiology_record_rt_t := radiology_record_rt_t();
       BEGIN
       
       IF descending='TRUE' THEN
       	  FOR t in 
       	  (SELECT rr.*
           FROM radiology_record rr JOIN persons p ON rr.patient_id=p.person_id
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
           FROM radiology_record rr JOIN persons p ON rr.patient_id=p.person_id
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
 * @param keywords string of keywords.
 * @param d1 lowerbound of date to be included.
 * @param d2 upperbound of date to be included.
 * @return table of radiology_records that matches the given keywords, ordered by rank.
 */
CREATE FUNCTION searchWithKPByRank(keywords IN VARCHAR2 ,d1 IN DATE, d2 IN DATE)
       return radiology_record_rt_t IS
       l_tab radiology_record_rt_t := radiology_record_rt_t();
       BEGIN
       
       FOR t in
       	   (SELECT *
       	    FROM TABLE(searchWithKeywordsOrderByRank(keywords)) rr
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
 * @param keywords string of keywords.
 * @param d1 lowerbound of date to be included.
 * @param d2 upperbound of date to be included.
 * @param desencending true for descending ordering, false otherwise.
 * @return table of radiology_records that matches the given keywords, ordered by rank.
 */
CREATE FUNCTION searchWithKPByTime(keywords IN VARCHAR2 ,d1 IN DATE, d2 IN DATE, 
       						   descending IN VARCHAR2)
       return radiology_record_rt_t IS
       l_tab radiology_record_rt_t := radiology_record_rt_t();
       BEGIN
       
       IF descending='TRUE' THEN
       	  FOR t in
       	  (SELECT *
           FROM TABLE(searchWithKeywordsOrderByRank(keywords)) rr
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
           FROM TABLE(searchWithKeywordsOrderByRank(keywords)) rr
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
