CREATE DATABASE `dentalclinic`;

INSERT INTO `dentalclinic`.`roles` (`name`) VALUES ('Dentist');
INSERT INTO `dentalclinic`.`roles` (`name`) VALUES ('Admin');
INSERT INTO `dentalclinic`.`roles` (`name`) VALUES ('Hr');
INSERT INTO `dentalclinic`.`roles` (`name`) VALUES ('Financier');
INSERT INTO `dentalclinic`.`roles` (`name`) VALUES ('Receptionist');

INSERT INTO `dentalclinic`.`clinic_branches` (`name`, `city`, `address`, `phone`) VALUES ('Central', 'Varna', 'gen. Gurko ', '0888999777');
INSERT INTO `dentalclinic`.`clinic_branches` (`name`, `city`, `address`, `phone`) VALUES ('Burgas', 'Burgas', 'Slaveikov 14', '0888666777');
INSERT INTO `dentalclinic`.`users` (`username`, `password`, `fullName`, `roleId`, `branchId`) VALUES ('boss', '$2y$13$tTXNXn10OR6zhlevJu63mOuBSiXOT3nPI1leeZcLzKafQnl1jbKaa', 'Pesho Adm', '2', '1');


INSERT INTO `dentalclinic`.`users` (`username`, `password`, `fullName`, `roleId`, `branchId`) VALUES ('dok', '$2y$13$.JKjwyORsocc5bGHXwPMWuAx86gRZZmh0vr31.mYsJABTPfhXfN6G', 'Gosho Dok', '1', '1');

INSERT INTO `dentalclinic`.`tariffs` (`treatment`, `price`) VALUES ('examination', '10.00');
INSERT INTO `dentalclinic`.`tariffs` (`treatment`, `price`) VALUES ('karies', '25.00');
INSERT INTO `dentalclinic`.`tariffs` (`treatment`, `price`) VALUES ('smile', '5.00');
INSERT INTO `dentalclinic`.`tariffs` (`treatment`, `price`) VALUES ('kkjuyt', '3.50');

INSERT INTO `dentalclinic`.`patients` (`fullName`) VALUES ('child');