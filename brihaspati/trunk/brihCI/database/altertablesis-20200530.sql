ALTER TABLE `employee_master` ADD `emp_retage` INT(2) NOT NULL AFTER `emp_bank_status`;
update employee_master set emp_retage=(YEAR(emp_dor)-YEAR(emp_dob));
UPDATE employee_master SET emp_dor = DATE_ADD(emp_dor, INTERVAL 1 YEAR),emp_retage=59 where emp_retage=58;
