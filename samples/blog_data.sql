SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `humilheme` ;
CREATE SCHEMA IF NOT EXISTS `humilheme` DEFAULT CHARACTER SET latin1 ;
USE `humilheme` ;

-- -----------------------------------------------------
-- Table `humilheme`.`profiles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `humilheme`.`profiles` ;

CREATE  TABLE IF NOT EXISTS `humilheme`.`profiles` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `Name` VARCHAR(100) NULL ,
  `Picture` VARCHAR(255) NULL ,
  `AuthCode` VARCHAR(255) NULL ,
  PRIMARY KEY (`Id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `humilheme`.`friends`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `humilheme`.`friends` ;

CREATE  TABLE IF NOT EXISTS `humilheme`.`friends` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `Id_Profile` INT NOT NULL ,
  `Id_Profile_Amigo` INT NOT NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_friends_profiles` (`Id_Profile` ASC) ,
  INDEX `fk_friends_profiles1` (`Id_Profile_Amigo` ASC) ,
  CONSTRAINT `fk_friends_profiles`
    FOREIGN KEY (`Id_Profile` )
    REFERENCES `humilheme`.`profiles` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_friends_profiles1`
    FOREIGN KEY (`Id_Profile_Amigo` )
    REFERENCES `humilheme`.`profiles` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `humilheme`.`humilacoes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `humilheme`.`humilacoes` ;

CREATE  TABLE IF NOT EXISTS `humilheme`.`humilacoes` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `Descricao` VARCHAR(45) NULL ,
  `Id_profile` INT NOT NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_humilacoes_profiles1` (`Id_profile` ASC) ,
  CONSTRAINT `fk_humilacoes_profiles1`
    FOREIGN KEY (`Id_profile` )
    REFERENCES `humilheme`.`profiles` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
