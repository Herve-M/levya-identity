-- MySQL Script generated by MySQL Workbench
-- 06/12/15 16:12:58
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema levya_identity
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `levya_identity` ;

-- -----------------------------------------------------
-- Schema levya_identity
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `levya_identity` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `levya_identity` ;

-- -----------------------------------------------------
-- Table `levya_identity`.`USERSTATE`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `levya_identity`.`USERSTATE` ;

CREATE TABLE IF NOT EXISTS `levya_identity`.`USERSTATE` (
  `USERSTATE_ID` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `USERSTATE_NAME` VARCHAR(150) NOT NULL,
  `USERSTATE_DESCRIPTION` TEXT NOT NULL,
  `USERSTATE_DEFAULT` TINYINT(1) NOT NULL,
  PRIMARY KEY (`USERSTATE_ID`),
  UNIQUE INDEX `USERSTATE_ID_UNIQUE` (`USERSTATE_ID` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `levya_identity`.`COUNTRY`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `levya_identity`.`COUNTRY` ;

CREATE TABLE IF NOT EXISTS `levya_identity`.`COUNTRY` (
  `COUNTRY_ID` SMALLINT(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `COUNTRY_CODE` CHAR(2) NOT NULL,
  `COUNTRY_NAME` VARCHAR(45) NOT NULL,
  `COUNTRY_CONTINENT` CHAR(2) NULL,
  PRIMARY KEY (`COUNTRY_ID`))
ENGINE = InnoDB
AUTO_INCREMENT = 276
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `levya_identity`.`USER`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `levya_identity`.`USER` ;

CREATE TABLE IF NOT EXISTS `levya_identity`.`USER` (
  `USER_ID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `USER_LASTNAME` VARCHAR(80) NULL,
  `USER_FORNAME` VARCHAR(80) NULL,
  `USER_MAIL` VARCHAR(254) NOT NULL,
  `USER_MAIL_PROJECT` VARCHAR(254) NULL,
  `USER_NICKNAME` VARCHAR(80) NOT NULL,
  `USER_PASSWORD` VARCHAR(255) NOT NULL,
  `USER_ADDRESS` TEXT NULL,
  `USER_PHONE` VARCHAR(20) NULL,
  `USER_SECRETKEY` VARCHAR(80) NOT NULL,
  `USER_CREATIONDATE` DATETIME NOT NULL,
  `USER_CREATIONIP` VARBINARY(16) NOT NULL COMMENT 'http://dev.mysql.com/doc/refman/5.6/en/miscellaneous-functions.html#function_inet6-aton\nhttp://www.highonphp.com/5-tips-for-working-with-ipv6-in-php',
  `USER_REGISTRATIONDATE` DATETIME NULL,
  `USER_REGISTRATIONIP` VARBINARY(16) NULL COMMENT 'http://dev.mysql.com/doc/refman/5.6/en/miscellaneous-functions.html#function_inet6-aton\nhttp://www.highonphp.com/5-tips-for-working-with-ipv6-in-php',
  `USER_UPDATEDATE` DATETIME NULL DEFAULT NULL,
  `USER_AUTHKEY` VARCHAR(32) NULL,
  `USERSTATE_USERSTATE_ID` TINYINT UNSIGNED NOT NULL,
  `USER_LDAPUID` VARCHAR(100) NULL,
  `COUNTRY_COUNTRY_ID` SMALLINT(6) UNSIGNED NULL,
  `USER_LONGITUDE` FLOAT(13,10) NULL,
  `USER_LATITUDE` FLOAT(13,10) NULL,
  `USER_ISDELETED` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`USER_ID`),
  UNIQUE INDEX `USER_ID_UNIQUE` (`USER_ID` ASC),
  UNIQUE INDEX `USER_SECRETKEY_UNIQUE` (`USER_SECRETKEY` ASC),
  INDEX `fk_USER_USERSTATE1_idx` (`USERSTATE_USERSTATE_ID` ASC),
  INDEX `fk_USER_COUNTRIE1_idx` (`COUNTRY_COUNTRY_ID` ASC),
  UNIQUE INDEX `USER_LDAPUID_UNIQUE` (`USER_LDAPUID` ASC),
  CONSTRAINT `fk_USER_USERSTATE1`
    FOREIGN KEY (`USERSTATE_USERSTATE_ID`)
    REFERENCES `levya_identity`.`USERSTATE` (`USERSTATE_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_USER_COUNTRY1`
    FOREIGN KEY (`COUNTRY_COUNTRY_ID`)
    REFERENCES `levya_identity`.`COUNTRY` (`COUNTRY_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `levya_identity`.`TOKEN`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `levya_identity`.`TOKEN` ;

CREATE TABLE IF NOT EXISTS `levya_identity`.`TOKEN` (
  `TOKEN_ID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `TOKEN_CODE` VARCHAR(45) NOT NULL,
  `TOKEN_CREATEDATE` DATETIME NOT NULL,
  `TOKEN_TYPE` TINYINT(3) UNSIGNED NOT NULL,
  `USER_USER_ID` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`TOKEN_ID`),
  UNIQUE INDEX `TOKEN_ID_UNIQUE` (`TOKEN_ID` ASC),
  UNIQUE INDEX `TOKEN_CODE_UNIQUE` (`TOKEN_CODE` ASC),
  INDEX `fk_TOKEN_USER1_idx` (`USER_USER_ID` ASC),
  CONSTRAINT `fk_TOKEN_USER1`
    FOREIGN KEY (`USER_USER_ID`)
    REFERENCES `levya_identity`.`USER` (`USER_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `levya_identity`.`SOCIAL_ACCOUNT`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `levya_identity`.`SOCIAL_ACCOUNT` ;

CREATE TABLE IF NOT EXISTS `levya_identity`.`SOCIAL_ACCOUNT` (
  `SOCIAL_ACCOUNT_ID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `SOCIAL_ACCOUNT_PROVIDER` VARCHAR(255) NOT NULL,
  `SOCIAL_ACCOUNT_CLIENT` VARCHAR(255) NOT NULL,
  `SOCIAL_ACCOUNT_DATA` TEXT NULL,
  `USER_USER_ID` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`SOCIAL_ACCOUNT_ID`),
  UNIQUE INDEX `SOCIAL_ACCOUNT_ID_UNIQUE` (`SOCIAL_ACCOUNT_ID` ASC),
  INDEX `fk_SOCIAL_ACCOUNT_USER1_idx` (`USER_USER_ID` ASC),
  CONSTRAINT `fk_SOCIAL_ACCOUNT_USER1`
    FOREIGN KEY (`USER_USER_ID`)
    REFERENCES `levya_identity`.`USER` (`USER_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `levya_identity`.`PROJECT`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `levya_identity`.`PROJECT` ;

CREATE TABLE IF NOT EXISTS `levya_identity`.`PROJECT` (
  `PROJECT_ID` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `PROJECT_NAME` VARCHAR(100) NOT NULL,
  `PROJECT_DESCRIPTION` TEXT NOT NULL,
  `PROJECT_WEBSITE` VARCHAR(200) NOT NULL,
  `PROJECT_LOGO` CHAR(50) NULL,
  `PROJECT_CREATIONDATE` DATETIME NOT NULL,
  `PROJECT_UPDATEDATE` DATETIME NULL,
  `PROJECT_ISACTIVE` TINYINT(1) NOT NULL,
  `PROJECT_ISDELETED` TINYINT(1) NOT NULL,
  `PROJECT_ISOPEN` TINYINT(1) NOT NULL,
  `PROJECT_PRIORITY` TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`PROJECT_ID`),
  UNIQUE INDEX `PROJECT_ID_UNIQUE` (`PROJECT_ID` ASC),
  UNIQUE INDEX `PROJECT_NAME_UNIQUE` (`PROJECT_NAME` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `levya_identity`.`DONATION`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `levya_identity`.`DONATION` ;

CREATE TABLE IF NOT EXISTS `levya_identity`.`DONATION` (
  `DONATION_ID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `USER_ID` INT UNSIGNED NULL,
  `PROJECT_PROJECT_ID` SMALLINT UNSIGNED NULL,
  `DONATION_DATE` DATETIME NOT NULL,
  `DONATION_SUM` DECIMAL NOT NULL,
  `DONATION_COMMENT` LONGTEXT NULL DEFAULT NULL,
  `DONATION_EMAIL` VARCHAR(225) NULL,
  `DONATION_NICKNAME` VARCHAR(255) NULL,
  `DONATION_ISPUBLIC` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`DONATION_ID`),
  INDEX `fk_DONATION_USER1_idx` (`USER_ID` ASC),
  UNIQUE INDEX `DONATION_ID_UNIQUE` (`DONATION_ID` ASC),
  INDEX `fk_DONATION_PROJECT1_idx` (`PROJECT_PROJECT_ID` ASC),
  CONSTRAINT `fk_DONATION_USER1`
    FOREIGN KEY (`USER_ID`)
    REFERENCES `levya_identity`.`USER` (`USER_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_DONATION_PROJECT1`
    FOREIGN KEY (`PROJECT_PROJECT_ID`)
    REFERENCES `levya_identity`.`PROJECT` (`PROJECT_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `levya_identity`.`SERVICE`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `levya_identity`.`SERVICE` ;

CREATE TABLE IF NOT EXISTS `levya_identity`.`SERVICE` (
  `SERVICE_ID` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `SERVICE_LDAPNAME` VARCHAR(45) NOT NULL,
  `SERVICE_NAME` VARCHAR(225) NOT NULL,
  `SERVICE_DESCRIPTION` LONGTEXT NOT NULL,
  `SERVICE_ISENABLE` TINYINT(1) NOT NULL,
  `SERVICE_STATE` TINYINT(1) NOT NULL,
  PRIMARY KEY (`SERVICE_ID`),
  UNIQUE INDEX `SERVICE_ID_UNIQUE` (`SERVICE_ID` ASC));


-- -----------------------------------------------------
-- Table `levya_identity`.`GROUP`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `levya_identity`.`GROUP` ;

CREATE TABLE IF NOT EXISTS `levya_identity`.`GROUP` (
  `GROUP_ID` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `GROUP_NAME` VARCHAR(225) NOT NULL,
  `GROUP_LDAPNAME` VARCHAR(45) NOT NULL,
  `GROUP_ISENABLE` TINYINT(1) NOT NULL,
  `GROUP_ISDEFAULT` TINYINT(1) NOT NULL,
  PRIMARY KEY (`GROUP_ID`),
  UNIQUE INDEX `GROUP_ID_UNIQUE` (`GROUP_ID` ASC));


-- -----------------------------------------------------
-- Table `levya_identity`.`GROUP_ACCESS_SERVICE`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `levya_identity`.`GROUP_ACCESS_SERVICE` ;

CREATE TABLE IF NOT EXISTS `levya_identity`.`GROUP_ACCESS_SERVICE` (
  `GROUP_GROUP_ID` TINYINT UNSIGNED NOT NULL,
  `SERVICE_SERVICE_ID` TINYINT UNSIGNED NOT NULL,
  INDEX `fk_GROUP_ACCESS_SERVICE_GROUPS_idx` (`GROUP_GROUP_ID` ASC),
  INDEX `fk_GROUP_ACCESS_SERVICE_SERVICE1_idx` (`SERVICE_SERVICE_ID` ASC),
  CONSTRAINT `fk_GROUP_ACCESS_SERVICE_GROUPS`
    FOREIGN KEY (`GROUP_GROUP_ID`)
    REFERENCES `levya_identity`.`GROUP` (`GROUP_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_GROUP_ACCESS_SERVICE_SERVICE1`
    FOREIGN KEY (`SERVICE_SERVICE_ID`)
    REFERENCES `levya_identity`.`SERVICE` (`SERVICE_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `levya_identity`.`BELONG`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `levya_identity`.`BELONG` ;

CREATE TABLE IF NOT EXISTS `levya_identity`.`BELONG` (
  `BELONG_ID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `BELONG_FROM` DATETIME NOT NULL,
  `BELONG_TO` DATETIME NULL,
  `USER_USER_ID` INT UNSIGNED NOT NULL,
  `GROUP_GROUP_ID` TINYINT UNSIGNED NOT NULL,
  PRIMARY KEY (`BELONG_ID`, `USER_USER_ID`, `GROUP_GROUP_ID`),
  UNIQUE INDEX `BELONG_ID_UNIQUE` (`BELONG_ID` ASC),
  INDEX `fk_BELONG_USER1_idx` (`USER_USER_ID` ASC),
  INDEX `fk_BELONG_GROUP1_idx` (`GROUP_GROUP_ID` ASC),
  CONSTRAINT `fk_BELONG_USER1`
    FOREIGN KEY (`USER_USER_ID`)
    REFERENCES `levya_identity`.`USER` (`USER_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_BELONG_GROUP1`
    FOREIGN KEY (`GROUP_GROUP_ID`)
    REFERENCES `levya_identity`.`GROUP` (`GROUP_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `levya_identity`.`POSITION`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `levya_identity`.`POSITION` ;

CREATE TABLE IF NOT EXISTS `levya_identity`.`POSITION` (
  `POSITION_ID` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `POSITION_LEVEL` SMALLINT NOT NULL,
  `POSITION_NAME` VARCHAR(45) NOT NULL,
  `POSITION_DESCRIPTION` TEXT NULL,
  `POSITION_ISOBLIGATORY` TINYINT(1) NOT NULL,
  `POSITION_ISDELETED` TINYINT(1) NOT NULL DEFAULT 0,
  `POSITION_NEEDDONATION` TINYINT(1) NOT NULL,
  `POSITION_NEEDSUBSCRIPTION` TINYINT(1) NOT NULL,
  `POSITION_ISREQVISIBLE` TINYINT(1) NOT NULL,
  `POSITION_ISDEFAULT` TINYINT(1) NOT NULL,
  `PROJECT_PROJECT_ID` SMALLINT UNSIGNED NOT NULL,
  PRIMARY KEY (`POSITION_ID`),
  UNIQUE INDEX `POSITION_ID_UNIQUE` (`POSITION_ID` ASC),
  INDEX `fk_POSITION_PROJECT1_idx` (`PROJECT_PROJECT_ID` ASC),
  CONSTRAINT `fk_POSITION_PROJECT1`
    FOREIGN KEY (`PROJECT_PROJECT_ID`)
    REFERENCES `levya_identity`.`PROJECT` (`PROJECT_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `levya_identity`.`WORK`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `levya_identity`.`WORK` ;

CREATE TABLE IF NOT EXISTS `levya_identity`.`WORK` (
  `WORK_ID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `WORK_FROM` DATETIME NULL,
  `WORK_TO` DATETIME NULL,
  `WORK_ISACCEPTED` TINYINT(1) NULL,
  `WORK_ISLOCKED` TINYINT(1) NULL,
  `USER_USER_ID` INT UNSIGNED NOT NULL,
  `PROJECT_PROJECT_ID` SMALLINT UNSIGNED NOT NULL,
  `POSITION_POSITION_ID` SMALLINT UNSIGNED NOT NULL,
  PRIMARY KEY (`WORK_ID`, `USER_USER_ID`, `PROJECT_PROJECT_ID`),
  UNIQUE INDEX `BELONG_ID_UNIQUE` (`WORK_ID` ASC),
  INDEX `fk_BELONG_USER1_idx` (`USER_USER_ID` ASC),
  INDEX `fk_WORK_PROJECT1_idx` (`PROJECT_PROJECT_ID` ASC),
  INDEX `fk_WORK_POSITION1_idx` (`POSITION_POSITION_ID` ASC),
  CONSTRAINT `fk_BELONG_USER10`
    FOREIGN KEY (`USER_USER_ID`)
    REFERENCES `levya_identity`.`USER` (`USER_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_WORK_PROJECT1`
    FOREIGN KEY (`PROJECT_PROJECT_ID`)
    REFERENCES `levya_identity`.`PROJECT` (`PROJECT_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_WORK_POSITION1`
    FOREIGN KEY (`POSITION_POSITION_ID`)
    REFERENCES `levya_identity`.`POSITION` (`POSITION_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `levya_identity`.`POSITION_ACCESS_SERVICE`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `levya_identity`.`POSITION_ACCESS_SERVICE` ;

CREATE TABLE IF NOT EXISTS `levya_identity`.`POSITION_ACCESS_SERVICE` (
  `SERVICE_SERVICE_ID` TINYINT UNSIGNED NOT NULL,
  `POSITION_POSITION_ID` SMALLINT UNSIGNED NOT NULL,
  INDEX `fk_GROUP_ACCESS_SERVICE_SERVICE1_idx` (`SERVICE_SERVICE_ID` ASC),
  INDEX `fk_POSITION_ACCESS_SERVICE_POSITION1_idx` (`POSITION_POSITION_ID` ASC),
  CONSTRAINT `fk_GROUP_ACCESS_SERVICE_SERVICE10`
    FOREIGN KEY (`SERVICE_SERVICE_ID`)
    REFERENCES `levya_identity`.`SERVICE` (`SERVICE_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_POSITION_ACCESS_SERVICE_POSITION1`
    FOREIGN KEY (`POSITION_POSITION_ID`)
    REFERENCES `levya_identity`.`POSITION` (`POSITION_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `levya_identity`.`AUTH_RULE`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `levya_identity`.`AUTH_RULE` ;

CREATE TABLE IF NOT EXISTS `levya_identity`.`AUTH_RULE` (
  `name` VARCHAR(64) NOT NULL,
  `data` TEXT NULL DEFAULT NULL,
  `created_at` INT NULL DEFAULT NULL,
  `updated_at` INT NULL DEFAULT NULL,
  PRIMARY KEY (`name`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `levya_identity`.`AUTH_ITEM`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `levya_identity`.`AUTH_ITEM` ;

CREATE TABLE IF NOT EXISTS `levya_identity`.`AUTH_ITEM` (
  `name` VARCHAR(64) NOT NULL,
  `type` INT NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `rule_name` VARCHAR(64) NULL DEFAULT NULL,
  `data` TEXT NULL DEFAULT NULL,
  `created_at` INT NULL DEFAULT NULL,
  `updated_at` INT NULL DEFAULT NULL,
  PRIMARY KEY (`name`),
  INDEX `type` (`type` ASC),
  INDEX `fk_{FB0EF0F3-058A-4413-AEB5-9DD95C4C7263}` (`rule_name` ASC),
  CONSTRAINT `fk_{FB0EF0F3-058A-4413-AEB5-9DD95C4C7263}`
    FOREIGN KEY (`rule_name`)
    REFERENCES `levya_identity`.`AUTH_RULE` (`name`)
    ON DELETE set null
    ON UPDATE cascade)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `levya_identity`.`AUTH_ITEM_CHILD`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `levya_identity`.`AUTH_ITEM_CHILD` ;

CREATE TABLE IF NOT EXISTS `levya_identity`.`AUTH_ITEM_CHILD` (
  `parent` VARCHAR(64) NOT NULL,
  `child` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`parent`, `child`),
  INDEX `fk_{A2E51F76-1396-4FFA-AC92-34328014255A}` (`child` ASC),
  CONSTRAINT `fk_{1CBAF976-D0D1-413D-A3F7-4C507637CAB7}`
    FOREIGN KEY (`parent`)
    REFERENCES `levya_identity`.`AUTH_ITEM` (`name`)
    ON DELETE cascade
    ON UPDATE cascade,
  CONSTRAINT `fk_{A2E51F76-1396-4FFA-AC92-34328014255A}`
    FOREIGN KEY (`child`)
    REFERENCES `levya_identity`.`AUTH_ITEM` (`name`)
    ON DELETE cascade
    ON UPDATE cascade)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `levya_identity`.`AUTH_ASSIGNMENT`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `levya_identity`.`AUTH_ASSIGNMENT` ;

CREATE TABLE IF NOT EXISTS `levya_identity`.`AUTH_ASSIGNMENT` (
  `item_name` VARCHAR(64) NOT NULL,
  `user_id` VARCHAR(64) NOT NULL,
  `created_at` INT NULL DEFAULT NULL,
  PRIMARY KEY (`item_name`, `user_id`),
  CONSTRAINT `fk_{47E2E24F-CE91-41BD-999A-4527FC82C6C4}`
    FOREIGN KEY (`item_name`)
    REFERENCES `levya_identity`.`AUTH_ITEM` (`name`)
    ON DELETE cascade
    ON UPDATE cascade)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `levya_identity`.`ACTION_HISTORY`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `levya_identity`.`ACTION_HISTORY` ;

CREATE TABLE IF NOT EXISTS `levya_identity`.`ACTION_HISTORY` (
  `ACTION_HISTORY_ID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ACTION_HISTORY_DATE` DATETIME NOT NULL,
  `ACTION_HISTORY_ACTION` TINYINT(3) NOT NULL,
  `ACTION_HISTORY_IP` VARBINARY(16) NOT NULL,
  `USER_USER_ID` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`ACTION_HISTORY_ID`),
  UNIQUE INDEX `ACTION_HISTORY_ID_UNIQUE` (`ACTION_HISTORY_ID` ASC),
  INDEX `fk_ACTION_HISTORY_USER1_idx` (`USER_USER_ID` ASC),
  CONSTRAINT `fk_ACTION_HISTORY_USER1`
    FOREIGN KEY (`USER_USER_ID`)
    REFERENCES `levya_identity`.`USER` (`USER_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `levya_identity`.`EVENT_TYPE`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `levya_identity`.`EVENT_TYPE` ;

CREATE TABLE IF NOT EXISTS `levya_identity`.`EVENT_TYPE` (
  `EVENT_TYPE_ID` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `EVENT_TYPE_NAME` VARCHAR(45) NOT NULL,
  `EVENT_TYPE_COLOR` VARCHAR(20) NOT NULL,
  `EVENT_TYPE_ICON` VARCHAR(50) NULL,
  PRIMARY KEY (`EVENT_TYPE_ID`),
  UNIQUE INDEX `EVENT_TYPE_NAME_UNIQUE` (`EVENT_TYPE_NAME` ASC),
  UNIQUE INDEX `EVENT_TYPE_COLOR_UNIQUE` (`EVENT_TYPE_COLOR` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `levya_identity`.`EVENT`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `levya_identity`.`EVENT` ;

CREATE TABLE IF NOT EXISTS `levya_identity`.`EVENT` (
  `EVENT_ID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `EVENT_TYPE_EVENT_TYPE_ID` TINYINT UNSIGNED NOT NULL,
  `EVENT_ISPUBLISHED` TINYINT(1) NOT NULL,
  `EVENT_TEXT` VARCHAR(255) NOT NULL,
  `EVENT_DATE` DATETIME NOT NULL,
  PRIMARY KEY (`EVENT_ID`, `EVENT_TYPE_EVENT_TYPE_ID`),
  UNIQUE INDEX `EVENT_ID_UNIQUE` (`EVENT_ID` ASC),
  INDEX `fk_EVENT_EVENT_TYPE1_idx` (`EVENT_TYPE_EVENT_TYPE_ID` ASC),
  CONSTRAINT `fk_EVENT_EVENT_TYPE1`
    FOREIGN KEY (`EVENT_TYPE_EVENT_TYPE_ID`)
    REFERENCES `levya_identity`.`EVENT_TYPE` (`EVENT_TYPE_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `levya_identity`.`PARAM`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `levya_identity`.`PARAM` ;

CREATE TABLE IF NOT EXISTS `levya_identity`.`PARAM` (
  `PARAM_ID` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `PARAM_NAME` VARCHAR(255) NOT NULL,
  `PARAM_VALUE` VARCHAR(45) NOT NULL,
  `PARAM_TYPE` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`PARAM_ID`),
  UNIQUE INDEX `PARAM_NAME_UNIQUE` (`PARAM_NAME` ASC),
  UNIQUE INDEX `PARAM_ID_UNIQUE` (`PARAM_ID` ASC))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
