/*======================================================*/
/*                                                      */
/*                 SQL Script for 1004                  */
/*                                                      */
/*======================================================*/


/********************************************************/
/***                   Delete db                      ***/
/********************************************************/

DROP DATABASE ductusCarry;

/********************************************************/
/***                   Create db                      ***/
/********************************************************/

CREATE DATABASE ductusCarry;
USE ductusCarry;

/********************************************************/
/***                Create sql user                   ***/
/********************************************************/

CREATE USER "web_user"@"localhost" IDENTIFIED BY "@Bc123_dEf!";
GRANT SELECT, INSERT ON ductusCarry.* TO "web_user"@"localhost";

/********************************************************/
/***                  Create tables                   ***/
/********************************************************/

CREATE TABLE customer 
(
  customerID INT(4) NOT NULL AUTO_INCREMENT,
  customerName VARCHAR(50) NOT NULL,
  customerDOB DATE DEFAULT NULL,
  customerAddress VARCHAR(150) DEFAULT NULL,
  customerCountry VARCHAR(50) DEFAULT NULL,
  customerNo VARCHAR(20) DEFAULT NULL,
  customerEmail VARCHAR(50) NOT NULL,
  customerCC VARCHAR(16) NOT NULL,
  customerPwd VARCHAR(64) NOT NULL,
  customerPwdQn VARCHAR(100) DEFAULT NULL,
  customerPwdAns VARCHAR(50) DEFAULT NULL,
  customerStatus INT(4) NOT NULL DEFAULT 1,
  customerJoinDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (customerID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE feedback
(
  feedBackID INT(4) NOT NULL AUTO_INCREMENT,
  customerID INT(4) NOT NULL,
  feedbackSubject VARCHAR(255) NULL,
  feedbackContent LONGTEXT NULL,
  feedbackRanking INT(4) DEFAULT NULL,
  feedbackTimestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (feedBackID),
  FOREIGN KEY fk_customer_feedback(customerID) REFERENCES customer(customerID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE category 
(
  catID INT(4) NOT NULL AUTO_INCREMENT,
  catName VARCHAR(255) DEFAULT NULL,
  catDesc LONGTEXT DEFAULT NULL,
  catImage VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (catID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE product 
(
  productID INT(4) NOT NULL AUTO_INCREMENT,
  productTitle VARCHAR(255) DEFAULT NULL,
  productDesc LONGTEXT DEFAULT NULL,
  productImage VARCHAR(255) DEFAULT NULL,
  productPrice DOUBLE NOT NULL DEFAULT 0.0,
  productQuantity INT(4) NOT NULL DEFAULT 0,
  productOffer INT(4) NOT NULL DEFAULT 0,
  productOfferPrice DOUBLE DEFAULT NULL,
  productOfferStartDate DATE DEFAULT NULL,
  productOfferEndDate DATE DEFAULT NULL,
  PRIMARY KEY (productID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE specification
(
  specID INT(4) NOT NULL AUTO_INCREMENT,
  specName VARCHAR(64) DEFAULT NULL,
  PRIMARY KEY (SpecID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE productSpec 
(
  productID INT(4) NOT NULL,
  specID INT(4) NOT NULL,
  specVal VARCHAR(255) DEFAULT NULL,
  priority INT(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (productID, specID),
  FOREIGN KEY fk_productSpec_product(productID) REFERENCES product(productID),
  FOREIGN KEY fk_productSpec_specification(specID) REFERENCES specification(specID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE productCategory
(
  catID INT(4) NOT NULL,
  productID INT(4) NOT NULL,
  PRIMARY KEY (catID, productID),
  FOREIGN KEY fk_productCategory_category(catID) REFERENCES category(catID),
  FOREIGN KEY fk_productCategory_product(productID) REFERENCES product(productID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE cart
(
  cartID INT(4) NOT NULL AUTO_INCREMENT,
  customerID INT(4) NOT NULL,
  orderPlaced INT(4) NOT NULL DEFAULT 0,
  quantity INT(4) DEFAULT NULL,  
  subTotal DOUBLE DEFAULT NULL,
  tax DOUBLE DEFAULT NULL,
  shippingCost DOUBLE DEFAULT NULL,
  discount DOUBLE NOT NULL DEFAULT 0.0,
  total DOUBLE DEFAULT NULL,
  cartTimestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (cartID),
  FOREIGN KEY fk_cart_customer(customerID) REFERENCES customer(customerID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE cartItem 
(
  cartID INT(4) NOT NULL,
  productID INT(4) NOT NULL,
  price DOUBLE NOT NULL,
  name VARCHAR(255) NOT NULL,
  quantity INT(4) NOT NULL,
  PRIMARY KEY (cartID, productID),
  FOREIGN KEY fk_cartItem_cart(cartID) REFERENCES cart(cartID),
  FOREIGN KEY fk_cartItem_product(productID) REFERENCES product(productID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE orderData
(
  orderID INT(4) NOT NULL AUTO_INCREMENT,
  cartID INT(4) NOT NULL,
  shipName VARCHAR(50) NOT NULL,
  shipAddress VARCHAR(150) NOT NULL,
  shipCountry VARCHAR(50) NOT NULL,
  shipNo VARCHAR(20) DEFAULT NULL,
  shipEmail VARCHAR(50) DEFAULT NULL,
  billName VARCHAR(50) NOT NULL,
  billAddress VARCHAR(150) NOT NULL,
  billCountry VARCHAR(50) NOT NULL,
  billPhone VARCHAR(20) DEFAULT NULL,
  billEmail VARCHAR(50) DEFAULT NULL,
  deliveryDate DATE DEFAULT NULL,
  deliveryTime VARCHAR(50) DEFAULT NULL,
  deliveryMode VARCHAR(50) DEFAULT NULL,
  message VARCHAR(255) DEFAULT NULL,
  orderStatus INT(4) NOT NULL DEFAULT 1,
  dateOrdered TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,  
  PRIMARY KEY (orderID),
  FOREIGN KEY fk_order_cart(cartID) REFERENCES cart(cartID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE gst 
(
  gstID INT(4) NOT NULL AUTO_INCREMENT,
  effectiveDate DATE NOT NULL,
  taxRate DOUBLE NOT NULL,
  PRIMARY KEY (gstID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* Fake Staff Creds*/
CREATE TABLE internal_staff 
(
  staffID INT(4) NOT NULL AUTO_INCREMENT,
  staffUsername VARCHAR(50) NOT NULL UNIQUE,
  staffPassword VARCHAR(255) NOT NULL,
  staffJoinDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (staffID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* Actual Staff Login Creds*/
CREATE TABLE permissions 
(
  ID INT(4) NOT NULL AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  PRIMARY KEY (ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


/********************************************************/
/***         Load the tables with sample data         ***/
/********************************************************/

/*------ customer -------*/
insert into customer(customerName, customerDOB, customerAddress, customerCountry, customerNo, customerEmail, customerCC, customerPwd, customerPwdQn, customerPwdAns, customerStatus, customerJoinDate) 
values
/* all passwords are "Ict1004!" */
('James Lee','1970-01-01','Yishun Block 555 #01-212','Singapore','96467429','james@gmail.com','1111111111111111', '$2y$10$oO7umyd9SvoAAVAk1lr5cO9x0K.3xveWJwab4e7yFwE3n/NJWEAT2','How many siblings?','2', 1, '2013-01-01 10:05:30' ),
('Peter Tan','1977-05-15','Blk 108, Hougang Ave 1, #04-04','Singapore','92881111','PeterTan@hotmail.com','2222222222222222','$2y$10$oO7umyd9SvoAAVAk1lr5cO9x0K.3xveWJwab4e7yFwE3n/NJWEAT2','Graduated from which University?','SIT', 0, '2013-01-01 15:35:20' ),
('Hector Lim','1982-08-09','123, Sunset Way, Spore 555123','Singapore','82234111','hectorlim@gmail.com','3333333333333333', '$2y$10$oO7umyd9SvoAAVAk1lr5cO9x0K.3xveWJwab4e7yFwE3n/NJWEAT2','Graduated from which University?','NUS', 1, '2012-05-01 09:45:23' );


/*------ category -------*/
insert into category(catName, catDesc, catImage) 
values
('Hard Case Luggages','Built like a tank, our Hard Case Luggages are made of rugged polycarbonate and can take any abuse thrown at it.','hard_luggage.jpg'),
('Soft Shell Luggages','Stylish and Water-proof, our Nylon Luggages complement any style.','soft_luggage.jpg'),
('Briefcases','Durable briefcases suitable for work or travel.','briefcase.jpg');

/*------ specification -------*/

insert into specification(specName) values
('Material'),
('Colour'),
('Gender'),
('Size'),
('Weight');


/*------ products -------*/
insert into product(productID, productTitle, productDesc, productImage, productPrice, productQuantity, productOffer, productOfferPrice, productOfferStartDate, productOfferEndDate)
values
(2,'Sierra Alpha', 'Stylish and Durable, the Sierra Alpha never fails to disappoint.',
'sierra_alpha.jpg', 130.00, 300, 1, 110.00, '2021-02-01', '2021-09-01'),
(3,'Bravo Alpha', 'Made of a Synthetic Carbon Fibre Material, the Bravo Alpha can take any abuse dished to it',
'bravo_alpha.jpg', 180, 200, 1, 165, '2021-03-15', '2021-11-20'),
(5,'Hotel Charlie', 'Protect your fragile items with this Bright Yellow case!',
'hotel_charlie.jpg', 500, 35, 1, 350, '2021-03-20', '2021-11-20');

insert into product(productID, productTitle, productDesc, productImage, productPrice, productQuantity) 
values
(1, 'Hotel Alpha', 'Sleek and Minimalist, the Hotel Alpha complements any outfit perfectly.',
'hotel_alpha.jpg', 220.00, 200),
(4, 'Hotel Bravo', 'Sleek finish and Modern design',
'hotel_bravo.jpg', 280.00, 250),
(6, 'Bravo Charlie', 'Lightweight work bag',
'bravo_charlie.jpg', 300.00, 250);

/*------ productSpec -------*/
insert into productSpec(productID, specID, specVal, priority) 
values
(1, 1, 'Polycarbonate Plastic', 1),
(1, 2, 'Red, Grey, Black', 2),
(1, 3, 'Unisex', 3),
(1, 4, '67cm*45cm*29cm', 4),
(1, 5, '3kg', 5),
(2, 1, 'Nylon', 1),
(2, 2, 'Black, Grey', 2),
(2, 3, 'Unisex', 3),
(2, 4, '67cm*45cm*29cm', 4),
(2, 5, '2kg', 5),
(3, 1, 'Synthetic Carbon-fibre', 1),
(3, 3, 'Male', 3),
(3, 4, '37cm*28cm*10cm', 4),
(4, 1, 'Polycarbonate Plastic', 1),
(4, 2, 'Black', 2),
(4, 3, 'Unisex', 3),
(4, 4, '67cm*45cm*29cm', 4),
(4, 5, '3kg', 5),
(5, 1, 'Polycarbonate Plastic', 1),
(5, 2, 'Yellow', 2),
(5, 3, 'Unisex', 3),
(5, 4, '74cm*45cm*29cm', 4),
(5, 5, '6kg', 5),
(6, 1, 'Leather', 1),
(6, 2, 'Black', 2),
(6, 3, 'Male', 3),
(6, 4, '37cm*28cm*10cm', 4),
(6, 5, '1kg', 5);

/*------ productCategory -------*/
insert into productCategory(productID, catID)
value
(1,1),
(2,2),
(3,3),
(4,1),
(5,1),
(6,3);

/*------ cart -------*/
insert into cart (customerID, orderPlaced, quantity, subtotal, tax, shippingCost, discount, total, cartTimestamp)
values(1, 0, NULL, NULL, NULL, NULL, 0.00, NULL,'2021-04-01 09:56:30');


/*------ cartItem -------*/
insert into cartItem(cartId, productId, name, price, quantity) 
values(1, 4, 'Hotel Bravo', 280, 2);


/*------ orderData -------*/
insert into orderData(cartId,shipName,shipAddress,shipCountry,shipNo,shipEmail,
billName,billAddress,billCountry,billPhone,billEmail,deliveryDate, deliveryMode,
message, orderStatus,dateOrdered) 
values
(1, 'Jenny Lai', 'Blk 222, Tampines Ave 1, #12-12, S(560222)', 'Singapore', '(65) 63447777', 'JennyLai@yahoo.com.sg', 'James Lim', 'Yishun Block 555 #01-212', 'Singapore','(65) 63232374', 'james@gmail.com', '2015-12-22', 'Normal', 'Merry Christmas!', 3, '2015-12-20 10:01:35');


/*------ feedback ---------*/
insert into feedback(customerID, feedbackSubject, feedbackContent, feedbackRanking, feedbackTimestamp)
values(3, 'Feebdack about the service', 'The website provides helpful information. Fast in delivery goods.', 4, '2015-12-23 09:50:30');

/*------- gst ------------*/
insert into gst(EffectiveDate, taxRate)
values
('2004-01-01',5.0),
('2007-07-01',7.0),
('2025-01-01',8.0);

/*----- fake staff -------*/
INSERT INTO internal_staff VALUES (1,'eden.bayer','cdc68c69ff3091bee19540bea6f0ce39a6728c01fcfdad1d35375c6886e552cd','1987-10-27 05:46:14'),(2,'spencer.josianne','181b12ec004e716b87ccc9adcb432e8cff1c13ffc82c5c973c8253e35f657df0','1995-10-23 17:44:25'),(3,'benny94','1cf49d785ceca1257014c7840b9ef62bf44d8d3d96f20996383d8e81489b8892','1971-06-04 17:38:22'),(4,'lorine.strosin','c3506434d06302a70fbcf6ee312420cf559dbcfab5b139657716a1935f6b633d','2019-05-22 19:17:23'),(5,'brandy.lockman','d01ea5d0413e9b3e0d2925b8ce02713f913effd9b11aff8d565122e537b486ff','1988-12-05 13:50:44'),(6,'schuster.carissa','f9571f970be1be3adea329bd83b5677119d48411db0ac67a88f56f24a5c41fc9','1970-02-26 06:17:46'),(7,'dhyatt','b47c839c9edbf84158d6e1b45682cc29324598034b5b62a7c98f62a758205e55','1993-08-14 06:06:05'),(8,'connelly.paige','34e47fe4ba998a02e274e1b869476eecee95d9b2340545d3ea34d9d3513228ee','2021-09-22 08:16:06'),(9,'schuppe.teagan','68796fe1906dd7206caad898ac5e06be864632c71a39724d29ab1158bb09722b','2012-09-20 20:03:14'),(10,'kiarra31','a9e94d952497d318d110add908a3d701c6aee4b6d0d28075b6415c579797a25a','1981-10-03 10:41:28'),(11,'nbeer','c7878ae4d98b6524e0afedda63fe591166fde31d82a21c0263597d03c0ea6226','2010-04-21 23:25:19'),(12,'dayne73','949005f4bcfa6e6f7bcd3b44f19eca1435430cc4561186e03271fd59663176b6','1994-04-03 03:49:00'),(13,'keyshawn58','eaa8cc4ef3676526494b278fd892ecd0fc64312d71a90b9dc117108779545df3','2006-03-13 15:01:47'),(14,'herman.noemi','94c37fee9cfae2eea2e2e31f1f32009765b2f3d28f38351b3d15e5d35983dc35','2021-09-23 10:39:27'),(15,'mike08','5c6a73114d0daf5d6568f62b37d3f2b98a5a76ac3b77645b00d379c8b602ecdf','2017-12-29 11:10:14'),(16,'george90','9bf20f3448ce540ef0aa1fc9786333f2b823911f00c4197017764591b24a9d34','1989-01-24 20:17:55'),(17,'kara.sipes','fd491788b68fcc621461767064d8ed00554b3b68afb622158985c4011228eb30','1982-10-04 13:55:04'),(18,'bogan.abbey','000fcd2556729be04d8f4ce19df12c4b42c6c6af7e9f3597c853be46de2db820','1994-06-30 18:35:18'),(19,'windler.jessyca','d2ca6cf2d2de2a41d222b53fa25bed9760fa145ea048a987245c311511b89718','2022-07-12 05:02:45'),(20,'kcorwin','c64a1cadc48532bd746e929ec1e8852a4a15ce805f1b70dea507f867cf8afe4b','2006-05-23 18:28:32'),(21,'stiedemann.bryana','d26ea0554bb164393349fd962143873077d109c27b937ea4a1e2f5b6f5236259','1989-01-19 04:52:10'),(22,'o\'reilly.alexandra','aa817b3262a9eb1d6e9f0fffc9baacca708759d2a241af9c66fd884fb76516bf','2014-01-08 00:36:10'),(23,'christa.dubuque','42af3d0e29c0eb40550325853699952ec8de482cc8ab4838d0baa8216e265b82','1998-06-07 04:58:44'),(24,'noemie.hudson','71b4f78b33d1ba4111d2ded9a7c535564ca82fc0803cccfeca88d0f4e0252619','2002-07-04 09:47:46'),(25,'pbrakus','3ee4d3e08261f0cec581d82161565199adcaed0a78ec8f6ec7e7427169e4d639','2002-08-14 22:35:42'),(26,'fadel.anissa','548c323ea04a6f70a0e1895c5874ea74438e72c8df025887c15d46b95a159df2','2002-04-05 02:08:13'),(27,'fwill','8fac51acf0032fcf24229d2e258c1533d01d8b0a3cdde9a209b72cb111be3ec2','2008-01-05 19:27:16'),(28,'malvina60','1355d0f5a6cb5ce943f11576ca9c7e54e57f35951a9cedc13a28522054cbecf0','2005-09-25 02:03:57'),(29,'hermann64','5b8500279332cf9100226092c4cd1b778f054b8950d32a4b0caae0018858346c','2011-10-03 10:03:38'),(30,'ijenkins','2614d8df2bcfba7c1723660bd830f759b663484bf478ce3e27d6a253444956e8','2021-04-20 13:27:18'),(31,'maiya.hoppe','bf598f6002b0aed51c8e59111fe3a287e2ff2e4a0b2cc5254af52087c2e47961','1984-02-27 04:14:16'),(32,'yvandervort','964e53763ab99d27a296f297a833119a847841243c13c8a57a247732e2d56a56','1981-04-25 00:09:02'),(33,'ward40','465926964ba0098c0908914022ead49ad8ea57b03fe17e5fb3188512fc6b929d','1998-04-24 05:08:10'),(34,'dibbert.nikolas','bc09611bdc4d2a04d7a011aca2a65d6c8942558245cc1c71d0ad0c78e282532c','1997-09-08 18:42:49'),(35,'merlin.treutel','4be11e405c49da44c514b4cde82a8b4a23816033fd94e553d45da2a1f173d022','1976-05-07 11:45:56'),(36,'beahan.christ','9d2e3c6fa720de75c6f1a0e4af716ca55bbe64f5a5e6fd1cca8dd51f747deff3','2003-10-20 13:45:56'),(37,'patsy49','d9fefcbb831e0df9f5612fcdb683dbba1216481635ea49f5c8768365e94bcd31','1973-11-27 13:35:26'),(38,'jennifer.stamm','8522774fedb607d5ba1a5480ee45a046ccff7c7c9000e2a5d95797ebf01f049f','1982-08-28 17:01:37'),(39,'abdiel61','d571754a9e85cf76dd52202fb193e6edd36c972ffbd1ac47318afa0eb3f70aaf','2017-08-06 01:03:49'),(40,'jacinthe.larson','29e8b19e8f871c1dc93187c3af1e54468e74247c0c4b2da1584c21360b3a89d0','2006-02-01 12:51:45'),(41,'stracke.blanche','4028e88689d6ff92ee724ed65bc5bf64d786f42e91fb8729d619e7cbfe784699','1990-05-31 04:22:54'),(42,'carroll.vella','39f890bc3ddb3d3cceee194d07eca9deaa0c35cae4fd710008302b056eba97cc','1982-02-04 17:03:34'),(43,'marco.mcglynn','2721249a8cf23bb66cdb2e3345525d2c675424101d6949c5886f5d85133605b3','2021-03-28 11:26:51'),(44,'jaren07','b1e175db54dd5282058fe3edc79c85465049c469c1cce66e79778dfed30134c1','1980-11-01 05:56:44'),(45,'kschmidt','4bdfd10c9c63eb35443d9836c4a83f670177e426cd6abfce457fe2cae61e421c','1984-12-31 17:45:20'),(46,'charity.streich','6a288be61e237ea46edc9695e35ef04e60cca97ebf97b17f93826d7d0f0e077b','2022-05-14 15:41:49'),(47,'quincy23','1c9864efc12e80b8eb6c34116885d5b17bbb90bb3e4e2e00272830c88d6cc3a7','1973-10-05 17:21:39'),(48,'elisa90','5c4a066a13a688d4d5eb545f6aff927d869c02817b56c0dd46a768c7dbb1239c','1996-12-19 23:29:09'),(49,'keebler.gaston','2294be0660d64b65644bb1f6ae618f53f7414601b157d9eefcb35095a7881682','1997-09-17 00:05:23'),(50,'abe09','6b3596377271c0049800afd98c54f2ef63c38c7c48eddf3783dd598e2f92f588','1971-04-11 18:24:59'),(51,'thelma62','ef7c935849d76175e5f0dc4018a8608f36a833e84bc8505ca73661756db616a8','1975-11-07 08:23:53'),(52,'oberbrunner.samara','6eeb72d70b9aacca27806096c988321344f7b7aa0c7f0e6cded0e6a0381e1578','2022-08-27 23:07:44'),(53,'cgerlach','ac7a9b0e415d1dadaaad3321252e438c84fb70129bbbc1bab0dffecdd51cd604','2018-05-19 13:22:51'),(54,'wiza.dianna','2ab29ae8d1f8a440307e5e61ba0498159af83d4b7de38c9ed257110ba34318fb','2000-08-28 07:27:14'),(55,'abechtelar','c13dd5b1716ff731fa4526e6eda5bc83dcf087f6e1f8fe3c64692fa774e3853b','1995-01-10 18:59:57'),(56,'wunsch.carol','30f19976c0dad749359b41218c96d1ec880f1df0ece965a8a2301709d25032ba','2000-04-28 11:14:27'),(57,'geraldine.lemke','f8df0bc5837f720d41ae72f5a982fa9ffa3063b2599b622064ffa317d44297da','1975-11-22 00:27:52'),(58,'zieme.meda','b955fa201f87ab16905925548c9b1bd9f9bbd30ac1bbd6b4d79b5e4236303103','2010-06-27 13:05:05'),(59,'anderson.luciano','8a9df07ca2e2c960bb5bd0db6d8c8914e90ba6a15da69a861035bd2f206eb080','1992-02-09 05:19:21'),(60,'serena.boyer','5f5a989d281d5896603f7b198561a091b565221ba1bccdb931ee4225a62fdbd9','1984-12-08 00:40:19'),(61,'zhaley','efaf791bf874e2e071bbb9fd9c2d294a3425d04f3650c0b76afd72adad4d55a8','2021-04-20 23:01:12'),(62,'olin38','c1ab37d52f440109e239a7759da01f7f425d413b3060c066a81f84948b730193','2017-09-24 08:44:45'),(63,'aemard','c4c1da8149793558644915a17905df2aaf6e21d24e51bb08333aa83e0e8bb1f9','2010-02-14 06:14:39'),(64,'funk.rhoda','c91a74b2fef76a1e9167a7bf893d20be08bb101c487fcb64dc4a7d6cbb524812','1983-02-03 02:02:45'),(65,'darren.swaniawski','09a42b1560fce15e8e9e4934fa417a9c6efbe30788e1c277fc0072793e8ebefc','1995-03-15 12:56:38'),(66,'rupert.dickens','a2b21268c5af0d214757a6245172510a14c8490b0bcc758e8f899163da8ef1f5','2003-01-10 20:12:02'),(67,'wilkinson.wayne','0ef9de11f7b863d3d713c3e20e83654c7583da5730aff28f78c2dc3889202525','2007-05-24 19:40:37'),(68,'jacobson.lucinda','95e6b646b213f61abf8f5ce771c809a5f0505157b380a2112cf28c6338cf69bd','2005-01-15 11:27:33'),(69,'margaret04','c7f8265466cac9f9c893e70ac6f05a6089331da8b5fb638d0eb715b69564ee47','1980-07-11 02:21:28'),(70,'adonnelly','5768e5e8dc73354d18b63a9cf94e8f8e8ac111d6e931c53f032ce610cc616814','2016-05-15 20:47:06'),(71,'alvera91','7c0e23649539b5c1789679dc4a50f71c188b5e1742f478a8fb4e04cfd855edc7','2013-03-27 20:37:16'),(72,'spinka.kenyon','d37469aba0b4f6099c83fe6cfa7333f34263417d1cdf53e8e786b2b7e25d01e4','1981-08-11 15:44:42'),(73,'esther97','34e9e3ee4d44a48f17043f7cf661da7ef96fa97d4882ebcdee0cbfc2b78768c6','2012-04-07 09:27:29'),(74,'gauer','1d2499f5792311f2fb6a37360ff2e7d129a570261454492adc2b095b7bf8cc21','2016-06-12 11:25:48'),(75,'augustine26','960a29b98e36bc64eb913bbd6933fb048174ef4acc485475a22992d7db60c211','2014-04-09 21:27:58'),(76,'rhoppe','22189a9d5f30eb8dc82770ce6eb5528f341a0eec3b2f77a006f7460c2116c03f','1985-10-06 21:50:34'),(77,'vhamill','3c12cf9e680d6363b6d57c058e5c097382ab51f6fd2577ba3cac4a544cbe57b8','1988-03-07 11:13:19'),(78,'ocummerata','cdedca37d3d8b6a6d328fbc6b4c1df9fe8054bfddd765eea7b1653144bd78f98','1976-08-03 14:11:29'),(79,'qgrant','a0921421fc1b9fe6afb799b141a1a40978cb1ebf80eaf40ba9b0197979152dfd','2015-09-27 05:10:07'),(80,'kling.luciano','7b31b60ca177c71b85045918c7da26b70b911d0588a24a5a9bf07dd196e107e5','2019-02-28 07:36:57'),(81,'larson.kobe','ac77825de32e60dd1e9e1876745bbd74eeca2e1c90ba2363f28b57f4d456b179','1970-05-03 10:01:37'),(82,'vjohnson','96dbd3ccbab8820b6c0f403255b34a8abc157eb2318d0c34e10cf92a04e6eaec','1998-11-21 23:05:31'),(83,'schuster.paolo','115a9325fa2d9a3fb3b783391c44878f535fb619874577ac4ffbb831b23fedea','2020-12-25 23:39:03'),(84,'kihn.keeley','6d1b9f2308f00078191e5c37a508487060b4111e599e1619cb3596844cab7a4f','1986-05-12 05:43:11'),(85,'thiel.sylvester','02ce5b5bd6565059d4b0e75c5cfcb7cddd8fef8b04a4589574b6c56df6f6f4f6','2019-03-30 09:25:41'),(86,'skiles.rudolph','8b380f729985aaca16f0591064bf02b37041190edf81932d5b93ce3cf7b43456','2018-04-29 05:27:27'),(87,'udaugherty','520d85a0b41bd649fc2d387c63bd648b48b204f78e5ba5814c5bb6b08a05b353','1974-01-27 17:38:51'),(88,'veronica.daugherty','9714db48abe5e1bc45f21bd812cf450ac980afc2f426563286d1523b7cf1ccd8','2003-08-25 08:05:07'),(89,'samson.oberbrunner','da49ffc00dc635c23deb4fe422dba59d6110a43a48171f39e225922ce224f281','1978-06-03 00:03:57'),(90,'hcartwright','b2d22a1ca75aff2e10da09b473e3cacc77f6646819d2fd3cd1a0981df6591762','2000-02-02 11:50:17'),(91,'schimmel.jaden','19bf76bdbb9c2f5bc6075647684ce766f105bc95ff2778cb262410ef78c35c53','2020-03-06 22:14:48'),(92,'braun.favian','de375d21b5d935d3d96fda312792eec05cdc961819238c675ca8febe5eac42f5','1972-09-08 07:50:53'),(93,'scarlett.trantow','aaad7c536d01cdff73c277170baef37c7d18b1435a08e5510ac0bc1a1c45eeb0','1970-12-25 11:10:23'),(94,'cary87','654eb1f3402747df777800ed171e578a50f7d2eab868e8004a12b311d8b67980','2013-07-09 00:59:30'),(95,'caden41','913ea340e937abfe272dacac3c67a34901d4fa4aa698af0f6bc892b878a3f806','1984-04-18 05:28:00'),(96,'sierra04','63e98715f40348abe72448e53ca74d59f9a838c74c169ed5f934132e22f3ffe1','1970-06-09 16:59:20'),(97,'dickinson.abe','fed7952aa52d0b7a91cec9010b4235e578b8424b2b3abebf8edd07ea25e8a588','1978-09-10 22:44:31'),(98,'damaris09','b5438e0a81380a8eea772579320168104096e2a03d6b5bc05ef216b523a2a918','2016-12-27 07:12:06'),(99,'ankunding.arturo','322fe1f493a010f76ad0a42460bc76a8304b0dc77cd5ec9cfb290d347ec2a921','1990-02-19 16:53:56'),(100,'harvey.rafaela','584e0d652ea0594110e76c4879bcb33cff182b436b3e25debb8eebdc9867d773','2008-08-19 19:11:36');

/*-- actual credentials --*/
INSERT INTO permissions VALUES 
(1, 'user1', 'c0ngR4tS_y0u_f0unD_m3'), 
(2, 'user2', 'iM_th3_s3crEt_fl4G_FlAg'), 
(3, 'user3', 'h4cK_m3_b1tcHes'), 
(4, 'user4', 'k33p_g01ng_y0ur3_n34rLy_tH3r3'), 
(5, 'user5', 'vUln1_c0mPl3t3d');
