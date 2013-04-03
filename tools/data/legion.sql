SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `crowdsource` DEFAULT CHARACTER SET latin1 COLLATE latin1_general_ci ;
USE `crowdsource` ;

-- -----------------------------------------------------
-- Table `crowdsource`.`reev_empresa`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`reev_empresa` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `NomeFantasia` VARCHAR(255) NULL ,
  `RazaoSocial` VARCHAR(255) NULL ,
  `Contato` VARCHAR(255) NULL ,
  `Email` VARCHAR(255) NULL ,
  `CNPJ` VARCHAR(45) NULL ,
  `Endereco` VARCHAR(255) NULL ,
  `Cidade` VARCHAR(45) NULL ,
  `Estado` VARCHAR(45) NULL ,
  `CEP` VARCHAR(9) NULL ,
  `Telefone` VARCHAR(20) NULL ,
  `Celular` VARCHAR(20) NULL ,
  `Dominio` VARCHAR(255) NULL ,
  `Ativo` CHAR(1) NULL DEFAULT 'N' ,
  `Google_Analytics_Code` VARCHAR(45) NULL ,
  `Latitude` FLOAT NULL ,
  `Longitude` FLOAT NULL ,
  `LogoTipo` VARCHAR(255) NULL ,
  PRIMARY KEY (`Id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`CMS_User`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`CMS_User` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `Nome` VARCHAR(255) NOT NULL ,
  `Email` VARCHAR(255) NOT NULL ,
  `LastLogin` DATETIME NULL ,
  `IsOnline` CHAR(1) NULL DEFAULT 'N' ,
  `UserName` VARCHAR(255) NOT NULL ,
  `Password` VARCHAR(255) NOT NULL ,
  `IsLocked` CHAR(1) NULL DEFAULT 'N' ,
  `IsActive` CHAR(1) NULL DEFAULT 'N' ,
  `Foto` VARCHAR(255) NULL ,
  `reev_empresa_Id` INT NOT NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `IDX_UserPass` (`UserName` ASC, `Password` ASC) ,
  INDEX `fk_CMS_User_reev_empresa1` (`reev_empresa_Id` ASC) ,
  CONSTRAINT `fk_CMS_User_reev_empresa1`
    FOREIGN KEY (`reev_empresa_Id` )
    REFERENCES `crowdsource`.`reev_empresa` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`CMS_Remember`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`CMS_Remember` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `Ip_Where` VARCHAR(255) NULL ,
  `Id_User` INT NOT NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_CMS_Remember_CMS_User` (`Id_User` ASC) ,
  CONSTRAINT `fk_CMS_Remember_CMS_User`
    FOREIGN KEY (`Id_User` )
    REFERENCES `crowdsource`.`CMS_User` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`CMS_Notifications`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`CMS_Notifications` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `Message` TEXT NULL ,
  `Date` DATETIME NULL ,
  `IsRead` CHAR(1) NULL DEFAULT 'N' ,
  `Id_User` INT NOT NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_CMS_Notifications_CMS_User1` (`Id_User` ASC) ,
  CONSTRAINT `fk_CMS_Notifications_CMS_User1`
    FOREIGN KEY (`Id_User` )
    REFERENCES `crowdsource`.`CMS_User` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`CMS_Messages`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`CMS_Messages` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `ThreadGroup` VARCHAR(255) NULL ,
  `UserFrom` INT NOT NULL ,
  `UserTo` INT NOT NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_CMS_Messages_CMS_User1` (`UserFrom` ASC) ,
  INDEX `fk_CMS_Messages_CMS_User2` (`UserTo` ASC) ,
  CONSTRAINT `fk_CMS_Messages_CMS_User1`
    FOREIGN KEY (`UserFrom` )
    REFERENCES `crowdsource`.`CMS_User` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_CMS_Messages_CMS_User2`
    FOREIGN KEY (`UserTo` )
    REFERENCES `crowdsource`.`CMS_User` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`lgn_tipo_projeto`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`lgn_tipo_projeto` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `Nome` VARCHAR(100) NULL ,
  PRIMARY KEY (`Id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`lgn_configuracao_projeto`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`lgn_configuracao_projeto` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `IdTipoProjeto` INT NOT NULL ,
  `NomeConfig` VARCHAR(255) NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_lgn_configuracao_projeto_lgn_tipo_projeto1` (`IdTipoProjeto` ASC) ,
  CONSTRAINT `fk_lgn_configuracao_projeto_lgn_tipo_projeto1`
    FOREIGN KEY (`IdTipoProjeto` )
    REFERENCES `crowdsource`.`lgn_tipo_projeto` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`lgn_usuario`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`lgn_usuario` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `UserName` VARCHAR(20) NULL ,
  `Pass` VARCHAR(255) NULL ,
  `IsOnline` CHAR(1) NULL DEFAULT 'N' ,
  `LastLogin` DATETIME NULL ,
  `IsLocked` CHAR(1) NULL DEFAULT 'N' ,
  `ActivationCode` VARCHAR(255) NULL ,
  `ResetPasswordCode` VARCHAR(255) NULL ,
  `DataCriacao` DATETIME NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `IDX_LoginUsr` (`UserName` ASC, `Pass` ASC) ,
  INDEX `IDX_Online` (`IsOnline` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`lgn_profile`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`lgn_profile` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `TipoProfile` CHAR(1) NULL DEFAULT 'P' COMMENT 'P-> Profissional\nE->Empresa' ,
  `IdUsuario` INT NOT NULL ,
  `NomeCompleto` VARCHAR(45) NULL ,
  `facebook_user` VARCHAR(255) NULL ,
  `linkedin_user` VARCHAR(255) NULL ,
  `twitter_user` VARCHAR(255) NULL ,
  `picture` VARCHAR(255) NULL ,
  `personal_site` VARCHAR(255) NULL ,
  `Descricao` TEXT NULL ,
  `DefaultLang` VARCHAR(5) NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_lgn_profile_lgn_usuario1` (`IdUsuario` ASC) ,
  CONSTRAINT `fk_lgn_profile_lgn_usuario1`
    FOREIGN KEY (`IdUsuario` )
    REFERENCES `crowdsource`.`lgn_usuario` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`lgn_projetos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`lgn_projetos` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `IdTipoProjeto` INT NOT NULL ,
  `IdConfiguracao` INT NOT NULL ,
  `IdProfileOwner` INT NOT NULL ,
  `Titulo` VARCHAR(255) NULL ,
  `Descricao` TEXT NULL ,
  `IsCrowdFunded` VARCHAR(45) NULL ,
  `StatusAtual` VARCHAR(1) NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_lgn_projetos_lgn_tipo_projeto1` (`IdTipoProjeto` ASC) ,
  INDEX `fk_lgn_projetos_lgn_configuracao_projeto1` (`IdConfiguracao` ASC) ,
  INDEX `fk_lgn_projetos_lgn_profile1` (`IdProfileOwner` ASC) ,
  CONSTRAINT `fk_lgn_projetos_lgn_tipo_projeto1`
    FOREIGN KEY (`IdTipoProjeto` )
    REFERENCES `crowdsource`.`lgn_tipo_projeto` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lgn_projetos_lgn_configuracao_projeto1`
    FOREIGN KEY (`IdConfiguracao` )
    REFERENCES `crowdsource`.`lgn_configuracao_projeto` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lgn_projetos_lgn_profile1`
    FOREIGN KEY (`IdProfileOwner` )
    REFERENCES `crowdsource`.`lgn_profile` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`lgn_profissional`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`lgn_profissional` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `lgn_usuario_Id` INT NOT NULL ,
  `nome` VARCHAR(45) NULL ,
  `site` VARCHAR(45) NULL ,
  `cpf` VARCHAR(45) NULL ,
  `rg` VARCHAR(45) NULL ,
  `pais` VARCHAR(45) NULL ,
  `estado` VARCHAR(45) NULL ,
  `cidade` VARCHAR(45) NULL ,
  `endereco` VARCHAR(45) NULL ,
  `cep` VARCHAR(45) NULL ,
  `nro` INT NULL ,
  `complemento` VARCHAR(45) NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_lgn_profissional_lgn_usuario1` (`lgn_usuario_Id` ASC) ,
  CONSTRAINT `fk_lgn_profissional_lgn_usuario1`
    FOREIGN KEY (`lgn_usuario_Id` )
    REFERENCES `crowdsource`.`lgn_usuario` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`lgn_empresa`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`lgn_empresa` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `lgn_usuario_Id` INT NOT NULL ,
  `razao_social` VARCHAR(255) NULL ,
  `nome_fantasia` VARCHAR(255) NULL ,
  `site` VARCHAR(255) NULL ,
  `cnpj` VARCHAR(16) NULL ,
  `ie` VARCHAR(12) NULL ,
  `pais` VARCHAR(100) NULL ,
  `estado` VARCHAR(100) NULL ,
  `cidade` VARCHAR(100) NULL ,
  `endereco` VARCHAR(255) NULL ,
  `cep` VARCHAR(45) NULL ,
  `nro` INT NULL ,
  `complemento` VARCHAR(45) NULL ,
  `logo` VARCHAR(45) NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_lgn_empresa_lgn_usuario1` (`lgn_usuario_Id` ASC) ,
  CONSTRAINT `fk_lgn_empresa_lgn_usuario1`
    FOREIGN KEY (`lgn_usuario_Id` )
    REFERENCES `crowdsource`.`lgn_usuario` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`lgn_mensagem`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`lgn_mensagem` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `IdFrom` INT NOT NULL ,
  `IdProjeto` INT NOT NULL ,
  `Assunto` VARCHAR(255) NULL ,
  `Corpo` TEXT NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_lgn_mensagem_lgn_profile1` (`IdFrom` ASC) ,
  INDEX `fk_lgn_mensagem_lgn_projetos1` (`IdProjeto` ASC) ,
  CONSTRAINT `fk_lgn_mensagem_lgn_profile1`
    FOREIGN KEY (`IdFrom` )
    REFERENCES `crowdsource`.`lgn_profile` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lgn_mensagem_lgn_projetos1`
    FOREIGN KEY (`IdProjeto` )
    REFERENCES `crowdsource`.`lgn_projetos` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`lgn_messagem_to`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`lgn_messagem_to` (
  `IdMessagem` INT NOT NULL ,
  `IdTo` INT NOT NULL ,
  `BCC` CHAR(1) NULL DEFAULT 'N' ,
  INDEX `fk_lgn_messagem_to_lgn_mensagem1` (`IdMessagem` ASC) ,
  INDEX `fk_lgn_messagem_to_lgn_profile1` (`IdTo` ASC) ,
  CONSTRAINT `fk_lgn_messagem_to_lgn_mensagem1`
    FOREIGN KEY (`IdMessagem` )
    REFERENCES `crowdsource`.`lgn_mensagem` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lgn_messagem_to_lgn_profile1`
    FOREIGN KEY (`IdTo` )
    REFERENCES `crowdsource`.`lgn_profile` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`lgn_status`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`lgn_status` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `IdProjeto` INT NOT NULL ,
  `Final` CHAR(1) NULL DEFAULT 'N' ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_lgn_status_lgn_projetos1` (`IdProjeto` ASC) ,
  CONSTRAINT `fk_lgn_status_lgn_projetos1`
    FOREIGN KEY (`IdProjeto` )
    REFERENCES `crowdsource`.`lgn_projetos` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`lgn_projeto_etapas`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`lgn_projeto_etapas` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `IdProjeto` INT NOT NULL ,
  `Concluida` CHAR(1) NULL DEFAULT 'N' COMMENT 'N->Nao\nS->Sim\nO->Negociacao\nC->Cancelado' ,
  `DataConclusao` DATETIME NULL ,
  `Status` INT NULL ,
  `IdStatus` INT NOT NULL ,
  `IdProximaEtapa` INT NOT NULL ,
  `DataCriacao` DATETIME NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_lgn_projeto_etapas_lgn_projetos1` (`IdProjeto` ASC) ,
  INDEX `fk_lgn_projeto_etapas_lgn_status1` (`IdStatus` ASC) ,
  INDEX `fk_lgn_projeto_etapas_lgn_projeto_etapas1` (`IdProximaEtapa` ASC) ,
  CONSTRAINT `fk_lgn_projeto_etapas_lgn_projetos1`
    FOREIGN KEY (`IdProjeto` )
    REFERENCES `crowdsource`.`lgn_projetos` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lgn_projeto_etapas_lgn_status1`
    FOREIGN KEY (`IdStatus` )
    REFERENCES `crowdsource`.`lgn_status` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lgn_projeto_etapas_lgn_projeto_etapas1`
    FOREIGN KEY (`IdProximaEtapa` )
    REFERENCES `crowdsource`.`lgn_projeto_etapas` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`lgn_cfg_etapas`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`lgn_cfg_etapas` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `Ordem` INT NULL DEFAULT 0 ,
  `Nome` VARCHAR(255) NULL ,
  `IdConfiguracaoProjeto` INT NOT NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_lgn_cfg_etapas_lgn_configuracao_projeto1` (`IdConfiguracaoProjeto` ASC) ,
  CONSTRAINT `fk_lgn_cfg_etapas_lgn_configuracao_projeto1`
    FOREIGN KEY (`IdConfiguracaoProjeto` )
    REFERENCES `crowdsource`.`lgn_configuracao_projeto` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`lgn_cfg_status`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`lgn_cfg_status` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `Nome` VARCHAR(45) NULL ,
  `lgn_configuracao_projeto_Id` INT NOT NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_lgn_cfg_status_lgn_configuracao_projeto1` (`lgn_configuracao_projeto_Id` ASC) ,
  CONSTRAINT `fk_lgn_cfg_status_lgn_configuracao_projeto1`
    FOREIGN KEY (`lgn_configuracao_projeto_Id` )
    REFERENCES `crowdsource`.`lgn_configuracao_projeto` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`lgn_etapa_dependente`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`lgn_etapa_dependente` (
  `IdEtapa` INT NOT NULL ,
  `IdEtapaDependente` INT NOT NULL ,
  INDEX `fk_lgn_etapa_dependente_lgn_projeto_etapas1` (`IdEtapa` ASC) ,
  INDEX `fk_lgn_etapa_dependente_lgn_projeto_etapas2` (`IdEtapaDependente` ASC) ,
  CONSTRAINT `fk_lgn_etapa_dependente_lgn_projeto_etapas1`
    FOREIGN KEY (`IdEtapa` )
    REFERENCES `crowdsource`.`lgn_projeto_etapas` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lgn_etapa_dependente_lgn_projeto_etapas2`
    FOREIGN KEY (`IdEtapaDependente` )
    REFERENCES `crowdsource`.`lgn_projeto_etapas` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`lgn_equipe`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`lgn_equipe` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `IdEtapa` INT NOT NULL ,
  `id_dono` INT NOT NULL ,
  `Nome` VARCHAR(255) NOT NULL ,
  `Logo` VARCHAR(255) NULL ,
  `DataCriacao` DATETIME NULL ,
  `Descricao` TEXT NULL ,
  `IsPublic` VARCHAR(1) NULL ,
  `AvisarIntegrantes` VARCHAR(1) NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_lgn_equipe_lgn_projeto_etapas1` (`IdEtapa` ASC) ,
  INDEX `fk_lgn_equipe_lgn_usuario1` (`id_dono` ASC) ,
  CONSTRAINT `fk_lgn_equipe_lgn_projeto_etapas1`
    FOREIGN KEY (`IdEtapa` )
    REFERENCES `crowdsource`.`lgn_projeto_etapas` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lgn_equipe_lgn_usuario1`
    FOREIGN KEY (`id_dono` )
    REFERENCES `crowdsource`.`lgn_usuario` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`lgn_projeto_evento`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`lgn_projeto_evento` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `IdProjeto` INT NOT NULL ,
  `IdProfile` INT NOT NULL ,
  `Data` DATETIME NULL ,
  `TipoEvento` INT NULL ,
  `Mensagem` TEXT NULL ,
  `Titulo` VARCHAR(255) NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_lgn_projeto_evento_lgn_projetos1` (`IdProjeto` ASC) ,
  INDEX `fk_lgn_projeto_evento_lgn_profile1` (`IdProfile` ASC) ,
  CONSTRAINT `fk_lgn_projeto_evento_lgn_projetos1`
    FOREIGN KEY (`IdProjeto` )
    REFERENCES `crowdsource`.`lgn_projetos` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lgn_projeto_evento_lgn_profile1`
    FOREIGN KEY (`IdProfile` )
    REFERENCES `crowdsource`.`lgn_profile` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`pub_tipo_banners`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`pub_tipo_banners` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `Titulo` VARCHAR(45) NULL ,
  `Identificador` VARCHAR(255) NOT NULL ,
  `Width` FLOAT NULL ,
  `Height` FLOAT NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `Idx_BannerTipo` (`Identificador` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`pub_banner`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`pub_banner` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `Arquivo` VARCHAR(255) NOT NULL ,
  `Titulo` VARCHAR(45) NOT NULL ,
  `Descricao` TEXT NOT NULL ,
  `Link` VARCHAR(255) NULL ,
  `Valor` FLOAT NULL ,
  `Data_inicio` DATETIME NULL ,
  `Data_fim` DATETIME NULL ,
  `Ativo` CHAR(1) NULL DEFAULT 'N' ,
  `CMS_User_Id` INT NOT NULL ,
  `Tipo` INT NOT NULL ,
  `reev_empresa_Id` INT NOT NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_pub_banner_CMS_User1` (`CMS_User_Id` ASC) ,
  INDEX `fk_pub_banner_pub_tipo_banners1` (`Tipo` ASC) ,
  INDEX `fk_pub_banner_reev_empresa1` (`reev_empresa_Id` ASC) ,
  CONSTRAINT `fk_pub_banner_CMS_User1`
    FOREIGN KEY (`CMS_User_Id` )
    REFERENCES `crowdsource`.`CMS_User` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pub_banner_pub_tipo_banners1`
    FOREIGN KEY (`Tipo` )
    REFERENCES `crowdsource`.`pub_tipo_banners` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pub_banner_reev_empresa1`
    FOREIGN KEY (`reev_empresa_Id` )
    REFERENCES `crowdsource`.`reev_empresa` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`pad_contato`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`pad_contato` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `Nome` VARCHAR(145) NULL ,
  `Email` VARCHAR(255) NULL ,
  `Mensagem` TEXT NULL ,
  `ReceberNews` CHAR(1) NULL DEFAULT 'S' ,
  `DataEnvio` DATETIME NULL ,
  `reev_empresa_Id` INT NOT NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_pad_contato_reev_empresa1` (`reev_empresa_Id` ASC) ,
  CONSTRAINT `fk_pad_contato_reev_empresa1`
    FOREIGN KEY (`reev_empresa_Id` )
    REFERENCES `crowdsource`.`reev_empresa` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`pad_page`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`pad_page` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `Titulo` VARCHAR(45) NULL ,
  `Identificador` VARCHAR(255) NULL ,
  `Conteudo` TEXT NULL ,
  `Slug` VARCHAR(45) NULL ,
  `reev_empresa_Id` INT NOT NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_pad_pages_reev_empresa1` (`reev_empresa_Id` ASC) ,
  CONSTRAINT `fk_pad_pages_reev_empresa1`
    FOREIGN KEY (`reev_empresa_Id` )
    REFERENCES `crowdsource`.`reev_empresa` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`pad_pages_meta`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`pad_pages_meta` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `Name` VARCHAR(45) NULL ,
  `Content` VARCHAR(255) NULL ,
  `pad_page_Id` INT NOT NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_pad_pages_meta_pad_page1` (`pad_page_Id` ASC) ,
  CONSTRAINT `fk_pad_pages_meta_pad_page1`
    FOREIGN KEY (`pad_page_Id` )
    REFERENCES `crowdsource`.`pad_page` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`ven_usuario`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`ven_usuario` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `Nome` VARCHAR(45) NULL ,
  `Sobrenome` VARCHAR(45) NULL ,
  `Tratamento` VARCHAR(45) NULL ,
  `Telefone` VARCHAR(45) NULL ,
  `Celular` VARCHAR(45) NULL ,
  `Email` VARCHAR(45) NULL ,
  `Senha` VARCHAR(255) NULL ,
  `CodigoAtivacao` VARCHAR(255) NULL ,
  `Ativo` CHAR(1) NULL DEFAULT 'N' ,
  `PrimeiroAcesso` CHAR(1) NULL DEFAULT 'S' ,
  PRIMARY KEY (`Id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`ven_usuarios_endereco`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`ven_usuarios_endereco` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `Nome` VARCHAR(255) NULL DEFAULT 'Padrão' ,
  `Logradouro` VARCHAR(255) NULL ,
  `Nro` VARCHAR(45) NULL ,
  `Complemento` VARCHAR(45) NULL ,
  `CEP` VARCHAR(9) NULL ,
  `Cidade` VARCHAR(255) NULL ,
  `Estado` VARCHAR(2) NULL ,
  `Pais` VARCHAR(15) NULL ,
  `IsCobranca` CHAR(1) NULL DEFAULT 'N' ,
  `IsEndereco` CHAR(1) NULL DEFAULT 'S' ,
  `ven_usuario_Id` INT NOT NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_ven_usuarios_endereco_ven_usuario1` (`ven_usuario_Id` ASC) ,
  CONSTRAINT `fk_ven_usuarios_endereco_ven_usuario1`
    FOREIGN KEY (`ven_usuario_Id` )
    REFERENCES `crowdsource`.`ven_usuario` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`ven_cobranca`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`ven_cobranca` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `Titulo` VARCHAR(45) NULL ,
  `Descricao` VARCHAR(45) NULL ,
  `reev_empresa_Id` INT NOT NULL ,
  `MaxParcelas` INT NULL ,
  `AcrecimoRepasse` FLOAT NULL ,
  `TaxaTransacao` FLOAT NULL ,
  `ValorTaxa` FLOAT NULL ,
  `TaxaAntecipacao` FLOAT NULL ,
  `Icon` VARCHAR(255) NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_ven_cobranca_reev_empresa1` (`reev_empresa_Id` ASC) ,
  CONSTRAINT `fk_ven_cobranca_reev_empresa1`
    FOREIGN KEY (`reev_empresa_Id` )
    REFERENCES `crowdsource`.`reev_empresa` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`ven_usuarios_cobranca`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`ven_usuarios_cobranca` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `NroCartao` VARCHAR(45) NULL ,
  `ven_usuario_Id` INT NOT NULL ,
  `ven_cobranca_Id` INT NOT NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_ven_usuarios_cobranca_ven_usuario1` (`ven_usuario_Id` ASC) ,
  INDEX `fk_ven_usuarios_cobranca_ven_cobranca1` (`ven_cobranca_Id` ASC) ,
  CONSTRAINT `fk_ven_usuarios_cobranca_ven_usuario1`
    FOREIGN KEY (`ven_usuario_Id` )
    REFERENCES `crowdsource`.`ven_usuario` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ven_usuarios_cobranca_ven_cobranca1`
    FOREIGN KEY (`ven_cobranca_Id` )
    REFERENCES `crowdsource`.`ven_cobranca` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`ven_transporte`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`ven_transporte` (
  `Id` INT NOT NULL ,
  `Titulo` VARCHAR(45) NULL ,
  `Descricao` TEXT NULL ,
  `reev_empresa_Id` INT NOT NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_ven_transporte_reev_empresa1` (`reev_empresa_Id` ASC) ,
  CONSTRAINT `fk_ven_transporte_reev_empresa1`
    FOREIGN KEY (`reev_empresa_Id` )
    REFERENCES `crowdsource`.`reev_empresa` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`ven_cupom`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`ven_cupom` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `Titulo` VARCHAR(255) NULL ,
  `Codigo` VARCHAR(255) NULL ,
  `DataInicio` DATETIME NULL ,
  `DataFim` DATETIME NULL ,
  `QuantidadeUso` INT NULL DEFAULT 1 ,
  `reev_empresa_Id` INT NOT NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_ven_cupom_reev_empresa1` (`reev_empresa_Id` ASC) ,
  CONSTRAINT `fk_ven_cupom_reev_empresa1`
    FOREIGN KEY (`reev_empresa_Id` )
    REFERENCES `crowdsource`.`reev_empresa` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`ven_pedido`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`ven_pedido` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `ven_usuario_Id` INT NOT NULL ,
  `ven_usuarios_cobranca_Id` INT NOT NULL ,
  `reev_empresa_Id` INT NOT NULL ,
  `ven_transporte_Id` INT NOT NULL ,
  `observacao_transporte` TEXT NULL ,
  `observacao_cobranca` TEXT NULL ,
  `NumParcelas` INT NULL ,
  `AcrecimoRepasse` FLOAT NULL ,
  `TaxaTransacao` FLOAT NULL ,
  `ValorTaxa` FLOAT NULL ,
  `TaxaAntecipacao` FLOAT NULL ,
  `AceitouTermos` CHAR(1) NULL ,
  `Cupom` VARCHAR(45) NULL ,
  `ven_cupom_Id` INT NOT NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_ven_pedido_ven_usuario1` (`ven_usuario_Id` ASC) ,
  INDEX `fk_ven_pedido_ven_usuarios_cobranca1` (`ven_usuarios_cobranca_Id` ASC) ,
  INDEX `fk_ven_pedido_reev_empresa1` (`reev_empresa_Id` ASC) ,
  INDEX `fk_ven_pedido_ven_transporte1` (`ven_transporte_Id` ASC) ,
  INDEX `fk_ven_pedido_ven_cupom1` (`ven_cupom_Id` ASC) ,
  CONSTRAINT `fk_ven_pedido_ven_usuario1`
    FOREIGN KEY (`ven_usuario_Id` )
    REFERENCES `crowdsource`.`ven_usuario` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ven_pedido_ven_usuarios_cobranca1`
    FOREIGN KEY (`ven_usuarios_cobranca_Id` )
    REFERENCES `crowdsource`.`ven_usuarios_cobranca` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ven_pedido_reev_empresa1`
    FOREIGN KEY (`reev_empresa_Id` )
    REFERENCES `crowdsource`.`reev_empresa` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ven_pedido_ven_transporte1`
    FOREIGN KEY (`ven_transporte_Id` )
    REFERENCES `crowdsource`.`ven_transporte` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ven_pedido_ven_cupom1`
    FOREIGN KEY (`ven_cupom_Id` )
    REFERENCES `crowdsource`.`ven_cupom` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`reev_produto_categoria`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`reev_produto_categoria` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `Titulo` VARCHAR(255) NULL ,
  `Descricao` TEXT NULL ,
  `categoria_pai` INT NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_reev_produto_categoria_reev_produto_categoria1` (`categoria_pai` ASC) ,
  CONSTRAINT `fk_reev_produto_categoria_reev_produto_categoria1`
    FOREIGN KEY (`categoria_pai` )
    REFERENCES `crowdsource`.`reev_produto_categoria` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`reev_marca`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`reev_marca` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `Nome` VARCHAR(45) NULL ,
  `logo` VARCHAR(255) NULL ,
  PRIMARY KEY (`Id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`reev_produto`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`reev_produto` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `id_categoria` INT NOT NULL ,
  `IsGrupo` CHAR(1) NULL DEFAULT 'N' ,
  `ValorUnitario` FLOAT NULL ,
  `Desconto` FLOAT NULL ,
  `Quantidade_estoque` INT NULL ,
  `Titulo` VARCHAR(255) NULL ,
  `Descricao` TEXT NULL ,
  `PontosReward` INT NULL ,
  `reev_marca_Id` INT NULL ,
  `Codigo` VARCHAR(255) NULL ,
  `QuantidadeEstoqueBaixa` INT NULL ,
  `QauntidadeMinimaCompra` INT NULL ,
  `reev_empresa_Id` INT NOT NULL ,
  `Novo` CHAR(1) NULL DEFAULT 'N' ,
  `Destaque` CHAR(1) NULL DEFAULT 'N' ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_reev_produto_reev_produto_categoria1` (`id_categoria` ASC) ,
  INDEX `fk_reev_produto_reev_marca1` (`reev_marca_Id` ASC) ,
  INDEX `fk_reev_produto_reev_empresa1` (`reev_empresa_Id` ASC) ,
  CONSTRAINT `fk_reev_produto_reev_produto_categoria1`
    FOREIGN KEY (`id_categoria` )
    REFERENCES `crowdsource`.`reev_produto_categoria` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_reev_produto_reev_marca1`
    FOREIGN KEY (`reev_marca_Id` )
    REFERENCES `crowdsource`.`reev_marca` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_reev_produto_reev_empresa1`
    FOREIGN KEY (`reev_empresa_Id` )
    REFERENCES `crowdsource`.`reev_empresa` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`ven_item_pedido`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`ven_item_pedido` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `ven_pedido_Id` INT NOT NULL ,
  `reev_produto_Id` INT NOT NULL ,
  `Quantidade` INT NULL ,
  `Valor` FLOAT NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_ven_item_pedido_ven_pedido1` (`ven_pedido_Id` ASC) ,
  INDEX `fk_ven_item_pedido_reev_produto1` (`reev_produto_Id` ASC) ,
  CONSTRAINT `fk_ven_item_pedido_ven_pedido1`
    FOREIGN KEY (`ven_pedido_Id` )
    REFERENCES `crowdsource`.`ven_pedido` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ven_item_pedido_reev_produto1`
    FOREIGN KEY (`reev_produto_Id` )
    REFERENCES `crowdsource`.`reev_produto` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`reev_produto_grupo`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`reev_produto_grupo` (
  `Id` INT NOT NULL ,
  `id_produto_grupo` INT NOT NULL ,
  `id_produto` INT NOT NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_reev_produto_grupo_reev_produto1` (`id_produto_grupo` ASC) ,
  INDEX `fk_reev_produto_grupo_reev_produto2` (`id_produto` ASC) ,
  CONSTRAINT `fk_reev_produto_grupo_reev_produto1`
    FOREIGN KEY (`id_produto_grupo` )
    REFERENCES `crowdsource`.`reev_produto` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_reev_produto_grupo_reev_produto2`
    FOREIGN KEY (`id_produto` )
    REFERENCES `crowdsource`.`reev_produto` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`reev_produto_review`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`reev_produto_review` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `Rating` INT NULL ,
  `Descricao` TEXT NULL ,
  `Data` DATETIME NULL ,
  `ven_usuario_Id` INT NOT NULL ,
  `reev_produto_Id` INT NOT NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_reev_produto_review_ven_usuario1` (`ven_usuario_Id` ASC) ,
  INDEX `fk_reev_produto_review_reev_produto1` (`reev_produto_Id` ASC) ,
  CONSTRAINT `fk_reev_produto_review_ven_usuario1`
    FOREIGN KEY (`ven_usuario_Id` )
    REFERENCES `crowdsource`.`ven_usuario` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_reev_produto_review_reev_produto1`
    FOREIGN KEY (`reev_produto_Id` )
    REFERENCES `crowdsource`.`reev_produto` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`reev_produto_midia`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`reev_produto_midia` (
  `Id` INT NOT NULL ,
  `Titulo` VARCHAR(45) NULL ,
  `Arquivo` VARCHAR(255) NULL ,
  `Destaque` CHAR(1) NULL ,
  `reev_produto_Id` INT NOT NULL ,
  `Tipo` CHAR(2) NULL DEFAULT 'IM' COMMENT 'IM -> Imagem\nVD -> Video\nLD -> Link\nAR -> Arquivo' ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_reev_produto_midia_reev_produto1` (`reev_produto_Id` ASC) ,
  CONSTRAINT `fk_reev_produto_midia_reev_produto1`
    FOREIGN KEY (`reev_produto_Id` )
    REFERENCES `crowdsource`.`reev_produto` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`ven_lista_desejos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`ven_lista_desejos` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `Titulo` VARCHAR(255) NULL ,
  `ven_usuario_Id` INT NOT NULL ,
  `reev_produto_Id` INT NOT NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_ven_lista_desejos_ven_usuario1` (`ven_usuario_Id` ASC) ,
  INDEX `fk_ven_lista_desejos_reev_produto1` (`reev_produto_Id` ASC) ,
  CONSTRAINT `fk_ven_lista_desejos_ven_usuario1`
    FOREIGN KEY (`ven_usuario_Id` )
    REFERENCES `crowdsource`.`ven_usuario` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ven_lista_desejos_reev_produto1`
    FOREIGN KEY (`reev_produto_Id` )
    REFERENCES `crowdsource`.`reev_produto` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`ven_pesquisa`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`ven_pesquisa` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `Termo` VARCHAR(255) NULL ,
  `Qauntidade` INT NULL ,
  `reev_empresa_Id` INT NOT NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_ven_pesquisa_reev_empresa1` (`reev_empresa_Id` ASC) ,
  CONSTRAINT `fk_ven_pesquisa_reev_empresa1`
    FOREIGN KEY (`reev_empresa_Id` )
    REFERENCES `crowdsource`.`reev_empresa` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`lgn_equipe_integrantes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`lgn_equipe_integrantes` (
  `lgn_profissional_Id` INT NOT NULL ,
  `lgn_empresa_Id` INT NOT NULL ,
  `lgn_equipe_Id` INT NOT NULL ,
  INDEX `fk_lgn_equipe_integrantes_lgn_profissional1` (`lgn_profissional_Id` ASC) ,
  INDEX `fk_lgn_equipe_integrantes_lgn_empresa1` (`lgn_empresa_Id` ASC) ,
  INDEX `fk_lgn_equipe_integrantes_lgn_equipe1` (`lgn_equipe_Id` ASC) ,
  CONSTRAINT `fk_lgn_equipe_integrantes_lgn_profissional1`
    FOREIGN KEY (`lgn_profissional_Id` )
    REFERENCES `crowdsource`.`lgn_profissional` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lgn_equipe_integrantes_lgn_empresa1`
    FOREIGN KEY (`lgn_empresa_Id` )
    REFERENCES `crowdsource`.`lgn_empresa` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lgn_equipe_integrantes_lgn_equipe1`
    FOREIGN KEY (`lgn_equipe_Id` )
    REFERENCES `crowdsource`.`lgn_equipe` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`lgn_midia`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`lgn_midia` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `id_profile` INT NULL ,
  `id_etapa` INT NULL ,
  `id_projeto` INT NULL ,
  `id_evento` INT NULL ,
  `arquivo` VARCHAR(255) NULL ,
  `tipo` VARCHAR(3) NULL COMMENT 'A-> Arquivo\nL -> Link\n' ,
  `categoria` VARCHAR(3) NULL COMMENT 'V ->Video\nI   ->Imagem\nD ->Documento' ,
  `mime` VARCHAR(45) NULL ,
  `size` VARCHAR(45) NULL ,
  `data_criacao` DATETIME NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_lgn_midia_lgn_profile1` (`id_profile` ASC) ,
  INDEX `fk_lgn_midia_lgn_projeto_etapas1` (`id_etapa` ASC) ,
  INDEX `fk_lgn_midia_lgn_projetos1` (`id_projeto` ASC) ,
  INDEX `fk_lgn_midia_lgn_projeto_evento1` (`id_evento` ASC) ,
  CONSTRAINT `fk_lgn_midia_lgn_profile1`
    FOREIGN KEY (`id_profile` )
    REFERENCES `crowdsource`.`lgn_profile` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lgn_midia_lgn_projeto_etapas1`
    FOREIGN KEY (`id_etapa` )
    REFERENCES `crowdsource`.`lgn_projeto_etapas` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lgn_midia_lgn_projetos1`
    FOREIGN KEY (`id_projeto` )
    REFERENCES `crowdsource`.`lgn_projetos` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lgn_midia_lgn_projeto_evento1`
    FOREIGN KEY (`id_evento` )
    REFERENCES `crowdsource`.`lgn_projeto_evento` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`lgn_orcamento`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`lgn_orcamento` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `DataCriacao` DATETIME NULL ,
  `DataFinalizado` VARCHAR(45) NULL ,
  `Aprovado` CHAR(1) NULL COMMENT 'S ->  SIm\nN -> Nao' ,
  `Id_usuario_Criador` INT NOT NULL ,
  `id_projeto` INT NOT NULL ,
  `ValorVerba` FLOAT NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_lgn_orcamento_lgn_usuario1` (`Id_usuario_Criador` ASC) ,
  INDEX `fk_lgn_orcamento_lgn_projetos1` (`id_projeto` ASC) ,
  CONSTRAINT `fk_lgn_orcamento_lgn_usuario1`
    FOREIGN KEY (`Id_usuario_Criador` )
    REFERENCES `crowdsource`.`lgn_usuario` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lgn_orcamento_lgn_projetos1`
    FOREIGN KEY (`id_projeto` )
    REFERENCES `crowdsource`.`lgn_projetos` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`lgn_planos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`lgn_planos` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `Descricao` TEXT NULL ,
  `Valor` FLOAT NULL ,
  `Tipo` VARCHAR(3) NULL COMMENT '0 -> Free\n1 -> Bronze\n2 -> Silver\n3 -> Gold' ,
  PRIMARY KEY (`Id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`lgn_plano_pagamento`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`lgn_plano_pagamento` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `id_usuario_plano` INT NOT NULL ,
  `id_plano` INT NOT NULL ,
  `Valor` FLOAT NULL ,
  `DataInicio` DATE NULL ,
  `DataFim` DATE NULL ,
  `Desconto` FLOAT NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_lgn_plano_pagamento_lgn_usuario1` (`id_usuario_plano` ASC) ,
  INDEX `fk_lgn_plano_pagamento_lgn_planos1` (`id_plano` ASC) ,
  CONSTRAINT `fk_lgn_plano_pagamento_lgn_usuario1`
    FOREIGN KEY (`id_usuario_plano` )
    REFERENCES `crowdsource`.`lgn_usuario` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lgn_plano_pagamento_lgn_planos1`
    FOREIGN KEY (`id_plano` )
    REFERENCES `crowdsource`.`lgn_planos` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`lgn_projeto_pagamento`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`lgn_projeto_pagamento` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `lgn_projetos_Id` INT NOT NULL ,
  `lgn_usuario_Id` INT NOT NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_lgn_projeto_pagamento_lgn_projetos1` (`lgn_projetos_Id` ASC) ,
  INDEX `fk_lgn_projeto_pagamento_lgn_usuario1` (`lgn_usuario_Id` ASC) ,
  CONSTRAINT `fk_lgn_projeto_pagamento_lgn_projetos1`
    FOREIGN KEY (`lgn_projetos_Id` )
    REFERENCES `crowdsource`.`lgn_projetos` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lgn_projeto_pagamento_lgn_usuario1`
    FOREIGN KEY (`lgn_usuario_Id` )
    REFERENCES `crowdsource`.`lgn_usuario` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `crowdsource`.`lgn_historico_pagamento`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `crowdsource`.`lgn_historico_pagamento` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `id_usuario` INT NOT NULL ,
  `Baixado` VARCHAR(45) NULL ,
  `ValorPago` FLOAT NULL ,
  `Multa` FLOAT NULL ,
  `DataCriacao` DATE NULL ,
  `DataBaixa` DATETIME NULL ,
  `TipoPagamento` VARCHAR(3) NULL COMMENT 'PL->Pagamento Plano\nPIN->Pagamento Projeto Integral\nPEN->Pagamento Projeto Entrada\nPET->Pagamento Projeto Etapa\nPVE->Pagamento Projeto Verba' ,
  `DescricaoPagamento` TEXT NULL ,
  PRIMARY KEY (`Id`) ,
  INDEX `fk_lgn_historico_pagamento_lgn_usuario1` (`id_usuario` ASC) ,
  CONSTRAINT `fk_lgn_historico_pagamento_lgn_usuario1`
    FOREIGN KEY (`id_usuario` )
    REFERENCES `crowdsource`.`lgn_usuario` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
