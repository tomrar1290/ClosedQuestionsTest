use testdb;

DROP TABLE IF EXISTS odpowiedzi;
DROP TABLE IF EXISTS test;
DROP TABLE IF EXISTS wyniki;
DROP TABLE IF EXISTS pytania;
DROP TABLE IF EXISTS fotki;


create table test(
testid int unsigned not null auto_increment primary key,
name char(30),
pass char(30),
mail char(30)
);

CREATE TABLE odpowiedzi (
  id integer primary key auto_increment not null,
  testid int not null,
  nrpytid INTEGER NOT NULL,
  opcja INT(1) NOT NULL,
  prawid INTEGER NULL,
  tresc TINYtext NULL
);


CREATE TABLE wyniki (
  id integer primary key auto_increment not null,
  uczen tinytext,
  tresc tinytext NULL,
  razem char(6)
);



CREATE TABLE pytania (
  id integer primary key auto_increment not null,
  testid int not null,
  trescpyt tinytext NULL,
  ileprawid INTEGER NULL
);



CREATE TABLE fotki (
nrpytid INTEGER NOT NULL,
name CHAR(30) not NULL,
testid int not null
);

insert into test (testid, name, pass) values ('1', 'Klasa 3L', '3ltest');

insert INTO fotki (name, nrpytid, testid) VALUES('test.jpg', '1', '1');

INSERT INTO odpowiedzi (testid, nrpytid, opcja, prawid, tresc) VALUES('1', '1', '1', '0', 'Opcja uwierzetelniania');

INSERT INTO odpowiedzi (testid, nrpytid, opcja, prawid, tresc) VALUES('1', '1', '2', '1', 'Opcja kasowania');

INSERT INTO odpowiedzi (testid, nrpytid, opcja, prawid, tresc) VALUES('1', '1', '3', '0', 'Opcja dublowania');

INSERT INTO odpowiedzi (testid, nrpytid, opcja, prawid, tresc) VALUES('1', '1', '4', '0', 'Opcja chowania');

INSERT INTO odpowiedzi (testid, nrpytid, opcja, prawid, tresc) VALUES('1', '1', '5', '0', 'Opcja reedowania');

INSERT INTO pytania (testid, id, trescpyt, ileprawid) VALUES('1', '1', 'Jaka to funkcja', '1');



select * from pytania;

select * from odpowiedzi;

select p.trescpyt, o.opcja, o.tresc from odpowiedzi o, pytania p where nrpytid = 1;
