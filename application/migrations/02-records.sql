INSERT INTO loans(id,customerId,reference,state,amount_issued,amount_to_pay) VALUES ('51ed9314-955c-4014-8be2-b0e2b13588a5','c539792e-7773-4a39-9cf6-f273b2581438','LN12345678','ACTIVE',100.00,120.00);
INSERT INTO loans(id,customerId,reference,state,amount_issued,amount_to_pay) VALUES ('a54b0796-2fcb-4547-b23d-125786600ec3','c539792e-7773-4a39-9cf6-f273b2581438','LN22345678','ACTIVE',200.00,250.00);
INSERT INTO loans(id,customerId,reference,state,amount_issued,amount_to_pay) VALUES ('f7f81281-64a9-47a7-af60-5c6896896d1f','d275ce5e-91c8-49fe-9407-1700b59efe80','LN55522533','ACTIVE',50.00,70.00);
INSERT INTO loans(id,customerId,reference,state,amount_issued,amount_to_pay) VALUES ('b8d26e7b-1607-441d-8bb0-87517a874572','c5c05eeb-ff02-4de6-b92e-a1b7f02320df','LN20221212','ACTIVE',66.00,100.00);

INSERT INTO customers(id,firstname,lastname,ssn,email,phone) VALUES ('c539792e-7773-4a39-9cf6-f273b2581438','Pupa','Lupa',0987654321,'pupa.lupa@example.com',NULL);
INSERT INTO customers(id,firstname,lastname,ssn,email,phone) VALUES ('d275ce5e-91c8-49fe-9407-1700b59efe80','John','Doe',1234509876,NULL,+44123456789);
INSERT INTO customers(id,firstname,lastname,ssn,email,phone) VALUES ('a5c50ea9-9a24-4c8b-b4ae-c47ee007081e','Biba','Boba',1234567890,'biba@example.com',+44123456780);
INSERT INTO customers(id,firstname,lastname,ssn,email,phone) VALUES ('c5c05eeb-ff02-4de6-b92e-a1b7f02320df','Lorem','Ipsum',6789054321,'lorem@ipsum',+481230943320);
