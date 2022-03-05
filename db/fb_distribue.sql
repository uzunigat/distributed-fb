
USE fb_distribue;

CREATE TABLE `utilisateur` (
    `id` int(11) NOT NULL AUTO_INCREMENT ,
    `firstName` varchar(20) NOT NULL,
    `lastName` varchar(20) NOT NULL,
    `url` varchar(80) NOT NULL,
    `typeUser` VARCHAR (20) CHECK ( `typeUser` IN ('Owner','Friend', 'Stranger', 'Request Sent', 'Request Received')) ,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `posts`(
    `idPost` int(100) NOT NULL AUTO_INCREMENT,
    `contentPost` varchar(1000) NOT NULL,
    `datePost` DATETIME NOT NULL,
    `scopePost` VARCHAR (20) CHECK ( `scopePost` IN ('Privee','Publique','Amis des amis','Amis')) ,
    `urlOwner` varchar(80),
    `firstName` varchar(50) NOT NULL,
    `lastName` varchar(50) NOT NULL,
    PRIMARY KEY (`idPost`)
);

CREATE TABLE `messages` (
    `idMessage` int(11) NOT NULL AUTO_INCREMENT,
    `senderURL` varchar(40) NOT NULL,
    `receiverURL` varchar(40) NOT NULL,
    `payload` varchar(500) NOT NULL,
    `dateMessage` DATETIME NOT NULL,
    PRIMARY KEY (`idMessage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO utilisateur(firstName, lastName, url, typeUser) VALUES('Rodrigo', 'Zuniga', 'http://zuniga.server:8001', 'Owner');


