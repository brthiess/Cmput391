--Testing ground for new query. Faster loading.

SELECT (p.last_name||', '||p.first_name) AS nme, test_type, week, cnt
	    FROM persons p RIGHT JOIN
(SELECT rr.patient_id,
       rr.test_type,
       to_char(rr.test_date, 'yyyy') As week,
       COUNT(*) as cnt
FROM radiology_record rr JOIN radiology_image pi ON rr.record_id = pi.record_id
GROUP BY CUBE (rr.patient_id,
       	       rr.test_type,
	       to_char(rr.test_date, 'yyyy'))) a
	       ON p.person_id = a.patient_id;
