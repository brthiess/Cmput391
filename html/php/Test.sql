--Testing ground for new query. Faster loading.

SELECT rr.patient_id,
       rr.test_type,
       to_char(rr.test_date, 'yyyy') As week,
       COUNT(*)
FROM radiology_record rr JOIN pacs_images pi ON rr.record_id = pi.record_id
GROUP BY CUBE (rr.patient_id,
       	       rr.test_type,
	       to_char(rr.test_date, 'yyyy'));
