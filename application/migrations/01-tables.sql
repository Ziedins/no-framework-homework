CREATE TABLE payments(
   firstname   TEXT NOT NULL
  ,lastname    TEXT NOT NULL
  ,paymentDate DATETIME  NOT NULL
  ,amount      TEXT NOT NULL
  ,description TEXT NOT NULL
  ,refId       TEXT NOT NULL PRIMARY KEY
  ,status      TEXT NOT NULL
  ,loan_reference TEXT NOT NULL
);

CREATE TABLE loans(
   id            TEXT NOT NULL PRIMARY KEY
  ,customerId    TEXT NOT NULL
  ,reference     TEXT NOT NULL
  ,state         TEXT NOT NULL
  ,amount_issued FLOAT NOT NULL
  ,amount_to_pay FLOAT NOT NULL
);

CREATE TABLE customers(
   id        TEXT NOT NULL PRIMARY KEY
  ,firstname TEXT NOT NULL
  ,lastname  TEXT NOT NULL
  ,ssn       TEXT  NOT NULL
  ,email     TEXT
  ,phone     VARCHAR(255)
  ,unique(ssn)
);
