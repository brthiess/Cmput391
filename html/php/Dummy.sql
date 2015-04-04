set serveroutput on

DECLARE 
     id INTEGER := -1; 
BEGIN 
      id := insertPerson(persons_rt(-1, 'Kill', 'bill', 'asdf', 'asdf@yahoo.ca', '789'), 'TRUE');
      id := insertPerson(persons_rt(-1, 'Kill', 'bill', 'asdf', 'asdf@yahoo.ca', '789'), 'TRUE'); 
     DBMS_OUTPUT.put_line('id: ': id);
END;
/