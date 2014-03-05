--Nathaniel Thompson
--Lab3
--2/16/14

--BONUS--
--I added 'ON DELETE CASCADE' to references
--that had a many to 1 relationship with a customer, 
--this should delete them when a customer is deleted

--Drop and create lab 3 schema to start fresh
DROP SCHEMA IF EXISTS lab3 CASCADE;
CREATE SCHEMA lab3;
SET search_path = lab3, public;

--
-- Customer Table
--
DROP TABLE IF EXISTS customer;
CREATE TABLE customer (
  cust_id integer PRIMARY KEY,
  poc_name varchar(60) NOT NULL
);

--
--Invoice table
--
DROP TABLE IF EXISTS invoice;
CREATE TABLE invoice (
  inv_no integer PRIMARY KEY,
  invoice_date date NOT NULL,
  customer_num integer NOT NULL REFERENCES customer(cust_id) ON DELETE CASCADE,
  street varchar(60) NOT NULL,
  city varchar(60) NOT NULL,
  state varchar(60) NOT NULL,
  zipcode integer NOT NULL
);

--
--Employee table
--
DROP TABLE IF EXISTS employee;
CREATE TABLE employee (
  employee_id integer PRIMARY KEY,
  first_name varchar(60),
  last_name varchar(60)
);

--
--factory table
--
DROP TABLE IF EXISTS factory;
CREATE TABLE factory (
  factory_id integer PRIMARY KEY,
  phone_number char(10) NOT NULL,
  manager integer REFERENCES employee(employee_id)
);

--
--Product table
--
DROP TABLE IF EXISTS product;
CREATE TABLE product (
  product_id integer PRIMARY KEY,
  name varchar(60) NOT NULL,
  description varchar(60),
  factory_id integer REFERENCES factory(factory_id)
);

--
--Invoice Line table
--
DROP TABLE IF EXISTS invoiceLine;
CREATE TABLE invoiceLine (
  line_number integer NOT NULL,
  quanity integer NOT NULL, 
  unit_price float(2) NOT NULL,
  inv_no integer REFERENCES invoice(inv_no),
  product_id integer REFERENCES product(product_id) ON DELETE CASCADE,
  PRIMARY KEY(line_number, inv_no)
);

---------------------------
--Insert data into tables--
---------------------------

--
--Employee inserts
--
INSERT INTO employee VALUES (111, 'George', 'Of The Jungle');
INSERT INTO employee VALUES (222, 'Cole', 'Gibbs');
INSERT INTO employee VALUES (333, 'Colin', 'McParty');

--
--Factory inserts
--
INSERT INTO factory VALUES (123, '5551234567', 111);
INSERT INTO factory VALUES (124, '5551245667', 222);
INSERT INTO factory VALUES (125, '5551275867', 333);

--
--Product inserts
--
INSERT INTO product VALUES (999,'Bag of holding', 'A bag that holds things', 123);
INSERT INTO product VALUES (888,'Shoe of Wearing', 'A shoe to wear', 124);
INSERT INTO product VALUES (777,'Horse of Riding', 'A horse to ride', 125);

--
--Customer inserts
--
INSERT INTO customer VALUES (1,'Brad Girdlinger');
INSERT INTO customer VALUES (2,'Susan the Terrible');
INSERT INTO customer VALUES (3, 'Fred FrederMcFrederson');

--
--Invoice inserts
--
INSERT INTO invoice VALUES (321, '1992-10-01', 1, 'Willow Street', 'Columbia', 'MO', 65203);
INSERT INTO invoice VALUES (421, '2012-03-05', 2, 'Burbon Street', 'New Orleans', 'LA', 45816);
INSERT INTO invoice VALUES (521, '1555-09-09', 3, 'Main Street', 'Ney York', 'NY', 11125);

--
--invoiceLine inserts
--
INSERT INTO invoiceLine VALUES(1, 3, 12.55, 321, 999);
INSERT INTO invoiceLine VALUES(1, 2, 5.23, 421, 888);
INSERT INTO invoiceLine VALUES(1, 8, 1.99, 521, 777);