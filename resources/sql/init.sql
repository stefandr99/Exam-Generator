insert into `users` (name, email, password, role) values ('Administrator', 'administrator@gmail.com', '00000000', 1);
insert into `users` (name, email, password, role) values ('Dragoi Stefan', 'stefan.dragoi@info.uaic.ro', '00000000', 2);
insert into `users` (name, email, password, role) values ('Cosmin Varlan', 'cosmin.varlan@gmail.com', '00000000', 2);
insert into `users` (name, email, password, role) values ('Ciobaca Stefan', 'ciobaca.stefan@gmail.com', '00000000', 2);
insert into `users` (name, email, password, role) values ('Vitcu Anca', 'vitcu.anca@gmail.com', '00000000', 2);
insert into `users` (name, registration_number, email, year, users.group, semester, password) values ('Andrei Popescu', '123456789321654789', 'andrei.popescu@info.uaic.ro', 2, 'A5', 2, '00000000');
insert into `users` (name, registration_number, email, year, users.group, semester, password) values ('Mihai Calcea', '123456789321654788', 'mihai.calcea@info.uaic.ro', 1, 'B5', 2, '00000000');
insert into `users` (name, registration_number, email, year, users.group, semester, password) values ('Georgiana Alma', '123456789321654787', 'geo.alma@info.uaic.ro', 3, 'A2', 2, '00000000');
insert into `users` (name, registration_number, email, year, users.group, semester, password) values ('Mariuca Luca', '133456789321654789', 'mariuca.luca@info.uaic.ro', 1, 'A1', 2, '00000000');
insert into `users` (name, registration_number, email, year, users.group, semester, password) values ('Cristina Antal', '125456789321654789', 'cristina.antal@info.uaic.ro', 2, 'A6', 2, '00000000');
insert into `users` (name, registration_number, email, year, users.group, semester, password) values ('Victor Bales', '125456780321654789', 'victor.bales@info.uaic.ro', 1, 'B1', 2, '00000000');
insert into `users` (name, registration_number, email, year, users.group, semester, password) values ('Ciprian Apiroaie', '125476789321654789', 'ciprian.apiroaie@info.uaic.ro', 2, 'B2', 2, '00000000');
insert into `users` (name, registration_number, email, year, users.group, semester, password) values ('Malina Apostil', '125456789321004789', 'malina.apostil@info.uaic.ro', 1, 'B3', 2, '00000000');
insert into `users` (name, registration_number, email, year, users.group, semester, password) values ('Alexandra Volea', '125456789328954789', 'alexandra.volea@info.uaic.ro', 1, 'B6', 2, '00000000');
insert into `users` (name, registration_number, email, year, users.group, semester, password) values ('Tudor Zidea', '125456789321654999', 'tudor.zidea@info.uaic.ro', 1, 'A4', 2, '00000000');



insert into `courses` (name, year, semester, credits) values ('Baze de date', 2, 1, 6);
insert into `courses` (name, year, semester, credits) values ('Proiectarea algoritmilor', 1, 2, 5);
insert into `courses` (name, year, semester, credits) values ('Tehnologii Web', 2, 2, 6);
insert into `courses` (name, year, semester, credits) values ('Calcul numeric', 3, 2, 4);


insert into `didactics` (teacher_id, course_id) values (2, 1);
insert into `didactics` (teacher_id, course_id) values (2, 2);
insert into `didactics` (teacher_id, course_id) values (2, 3);
insert into `didactics` (teacher_id, course_id) values (2, 4);
insert into `didactics` (teacher_id, course_id) values (3, 1);
insert into `didactics` (teacher_id, course_id) values (3, 3);
insert into `didactics` (teacher_id, course_id) values (4, 2);
insert into `didactics` (teacher_id, course_id) values (5, 4);