DROP DATABASE IF EXISTS 'accounting-db';
CREATE DATABASE 'accounting-db';

DROP USER IF EXISTS 'accounting'@'localhost';
CREATE USER 'accounting'@'localhost' IDENTIFIED BY 'accpwd';
GRANT ALL PRIVILEGES ON 'accounting'.* TO 'accounting'@'localhost';

