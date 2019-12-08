<?php 
    include 'app/connection.php';
    
    $connection = database();

    // try to create a table
    $sqlArray = [ 
      'BANK' => "CREATE TABLE Bank (
        bankCode varchar(10) PRIMARY KEY,
        bankName varchar(20)
      );",
      'Invoice' => "CREATE TABLE Invoice (
        invoiceId varchar(6) PRIMARY KEY,
        bankCode varchar(10),
        accountNumber varchar(10),
        accountName varchar(50),
        amount integer,
        remark varchar(50),
        status varchar(10),
        createdAt varchar(100)
      );",
      'Disburse' => "CREATE TABLE Disburse (
        id varchar(6),
        invoiceId varchar(6),
        timeServed varchar(100),
        fee integer,
        beneficiaryName varchar(20),
        receipt varchar(100),
        refNum varchar(20)
      );",
      'Foreign_key1' => "ALTER TABLE Invoice ADD FOREIGN KEY (bankCode) REFERENCES Bank (bankCode);",
      'Foreign_key2' => "ALTER TABLE Disburse ADD FOREIGN KEY (invoiceId) REFERENCES Invoice (invoiceId);",
      'indexing1' => "CREATE INDEX bank ON Bank (bankCode);",
      'indexing2' => "CREATE INDEX Invoice ON Invoice (invoiceId, bankCode);",
      'indexing3' => "CREATE INDEX Disbursement ON Disburse (id, invoiceId);",
      'insertBankData' => "INSERT INTO Bank (
        bankCode, bankName
      ) VALUES (
        '002', 'BRI' 
      ), (
        '014', 'BCA'
      ), (
        '008', 'Mandiri'
      ), (
        '009', 'BNI'
      )"
    ];

    foreach($sqlArray as $key => $value){
        if ($connection->query($value) === true) {
            echo "Tabel $key berhasil dibuat\n";
        } else {
            echo "Tabel gagal dibuat: \n". $connection->error;
        }
    }  
    
    closeDb($connection);
?>