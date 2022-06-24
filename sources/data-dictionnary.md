# Data dictionary

## Candidat (`candidate`)

|Champ|Type|Spécificités|Description|
|-|-|-|-|
|id|INT|PRIMARY KEY, NOT NULL, UNSIGNED, AUTO_INCREMENT|L'identifiant du candidat|
|lasttname|VARCHAR(64)|NOT NULL|Le nom du candidat|
|firstname|VARCHAR(64)|NULL|Le prénom du candidat|
|birthday|DATE|NULL|La date de naissance du candidat|
|genre|TINYINT|NOT NULL|Le sexe du candidat (0 pour inconnu, 1 pour féminin, 2 pour masculin)|
|phoneNumber|VARCHAR(25)|NULL|Le numéro de téléphone du candidat|
|picture|VARCHAR(255)|NULL|L'URL de la photo du candidat|
|resume|VARCHAR(255)|NULL|L'URL du CV du candidat|
|description|LONGTEXT|NULL|La description du candidat|
|positionHeld|VARCHAR(128)|NULL|L'intitulé du poste actuel|
|portfolio|VARCHAR(255)|NULL|L'URL du portfolio du candidat|
|createdAt|TIMESTAMP|NOT NULL, DEFAULT CURRENT_TIMESTAMP|La date de création du candidat|
|updatedAt|TIMESTAMP|NULL|La date de la dernière mise à jour du candidat|
|adress|entity|NOT NULL, FOREIGN KEY|L'adresse (autre entité) du candidat|
|user|entity|NOT NULL, FOREIGN KEY|L'utilisateur (autre entité) associé au candidat|
|contract|entity|NOT NULL, FOREIGN KEY|Le type de contrat (autre entité) du candidat|
|experience|entity|NOT NULL, FOREIGN KEY|Le niveau d'expérience (autre entité) du candidat|
|salary|entity|NOT NULL, FOREIGN KEY|La tranche de salaire (autre entité) du candidat|
|jobtitle|entity|NOT NULL, FOREIGN KEY|Le titre de l'emploi (autre entité) du candidat|


## Utilisateur (`user`)

|Champ|Type|Spécificités|Description|
|-|-|-|-|
|id|INT|PRIMARY KEY, NOT NULL, UNSIGNED, AUTO_INCREMENT|L'identifiant de l'utilisateur|
|email|VARCHAR(180)|NOT NULL|L'adresse mail de l'utilisateur|
|password|VARCHAR(255)|NOT NULL|le mot de passe de l'utilisateur|
|roles|ENUM("ROLE_RECRUITER","ROLE_CANDIDATE", "ROLE_ADMIN")|NOT NULL|Le role de l'utilisateur|
|isHelped|BOOLEAN|NOT NULL|Le status permettant de savoir si l'utilisateur souhaite de l'aide (par défaut à `true`|
|createdAt|TIMESTAMP|DEFAULT CURRENT_TIMESTAMP|La date de création de l'utilisateur|
|updatedAt|TIMESTAMP|NULL|La date de la dernière mise à jour de l'utilisateur|

## Recruteur (`recruiter`)

|Champ|Type|Spécificités|Description|
|-|-|-|-|
|id|INT|PRIMARY KEY, NOT NULL, UNSIGNED, AUTO_INCREMENT|L'identifiant du recruteur|
|lasttname|VARCHAR(64)|NOT NULL|Le nom du recruteur|
|firstname|VARCHAR(64)|NULL|Le prénom du recruteur|
|phoneNumber|VARCHAR(25)|NULL|Le numéro de téléphone du recruteur|
|createdAt|TIMESTAMP|NOT NULL, DEFAULT CURRENT_TIMESTAMP|La date de création du recruteur|
|updatedAt|TIMESTAMP|NULL|La date de la dernière mise à jour du recruteur|
|company|entity|NULL, FOREIGN KEY|L'entreprise (autre entité) du recruteur|
|user|entity|NOT NULL, FOREIGN KEY|L'utilisateur (autre entité) associé au recruteur|

## Entreprise (`company`)

|Champ|Type|Spécificités|Description|
|-|-|-|-|
|id|INT|PRIMARY KEY, NOT NULL, UNSIGNED, AUTO_INCREMENT|L'identifiant de l'entreprise|
|companyName|VARCHAR(64)|NOT NULL|Le nom de l'entreprise|
|createdAt|TIMESTAMP|NOT NULL, DEFAULT CURRENT_TIMESTAMP|La date de création de l'entreprise|
|updatedAt|TIMESTAMP|NULL|La date de la dernière mise à jour de l'entreprise|
|adress|entity|NOT NULL, FOREIGN KEY|L'adresse (autre entité) de l'entreprise|
|sector|entity|NULL, FOREIGN KEY|Le secteur d'activité (autre entité) de l'entreprise|

## Offre d'emploi (`job`)

|Champ|Type|Spécificités|Description|
|-|-|-|-|
|id|INT|PRIMARY KEY, NOT NULL, UNSIGNED, AUTO_INCREMENT|L'identifiant de l'offre|
|jobName|VARCHAR(128)|NOT NULL|Le nom de l'offre|
|description|LONGTEXT|NOT NULL|La description de l'offre|
|statut|TINYINT|NOT NULL|Le statut de l'offre (0 pour active, 1 pour inactive/archivée)|
|createdAt|TIMESTAMP|NOT NULL, DEFAULT CURRENT_TIMESTAMP|La date de création de l'offre|
|updatedAt|TIMESTAMP|NULL|La date de la dernière mise à jour de l'offre|
|recruiter|entity|NOT NULL, FOREIGN KEY|Le recruteur (autre entité) pour l'ofrre|
|contract|entity|NOT NULL, FOREIGN KEY|Le type de contrat (autre entité) pour l'ofrre|
|experience|entity|NOT NULL, FOREIGN KEY|Le niveau d'expérience (autre entité) pour l'ofrre|
|salary|entity|NOT NULL, FOREIGN KEY|La tranche de salaire (autre entité) pour l'ofrre|
|jobtitle|entity|NOT NULL, FOREIGN KEY|Le titre de l'emploi (autre entité) pour l'ofrre|

## Type de contrat (`contract`)

|Champ|Type|Spécificités|Description|
|-|-|-|-|
|id|INT|PRIMARY KEY, NOT NULL, UNSIGNED, AUTO_INCREMENT|L'identifiant du type de contrat|
|name|VARCHAR(64)|NOT NULL|Le libellé du type de contrat|
|createdAt|TIMESTAMP|NOT NULL, DEFAULT CURRENT_TIMESTAMP|La date de création du type de contrat|
|updatedAt|TIMESTAMP|NULL|La date de la dernière mise à jour du type de contrat|

## Titre de l'emploi (`jobtitle`)

|Champ|Type|Spécificités|Description|
|-|-|-|-|
|id|INT|PRIMARY KEY, NOT NULL, UNSIGNED, AUTO_INCREMENT|L'identifiant du titre de l'emploi|
|title|VARCHAR(64)|NOT NULL|L'intitulé du titre de l'emploi|
|createdAt|TIMESTAMP|NOT NULL, DEFAULT CURRENT_TIMESTAMP|La date de création du titre de l'emploi|
|updatedAt|TIMESTAMP|NULL|La date de la dernière mise à jour du titre de l'emploi|

## Type de technologie (`technology`)

|Champ|Type|Spécificités|Description|
|-|-|-|-|
|id|INT|PRIMARY KEY, NOT NULL, UNSIGNED, AUTO_INCREMENT|L'identifiant de la technologie|
|technologyName|VARCHAR(64)|NOT NULL|Le nom de la technologie|
|backgroundColor|VARCHAR(7)|NULL|La couleur de fond de la technologie|
|textColor|VARCHAR(7)|NULL|La couleur du texte de la technologie|
|createdAt|TIMESTAMP|NOT NULL, DEFAULT CURRENT_TIMESTAMP|La date de création de la technologie|
|updatedAt|TIMESTAMP|NULL|La date de la dernière mise à jour de la technologie|

## Tranche de salaire (`salary`)

|Champ|Type|Spécificités|Description|
|-|-|-|-|
|id|INT|PRIMARY KEY, NOT NULL, UNSIGNED, AUTO_INCREMENT|L'identifiant de la tranche de salaire|
|name|VARCHAR(64)|NOT NULL|L'intitulé de la tranche de salaire|
|createdAt|TIMESTAMP|NOT NULL, DEFAULT CURRENT_TIMESTAMP|La date de création de la tranche de salaire|
|updatedAt|TIMESTAMP|NULL|La date de la dernière mise à jour de la tranche de salaire|

## Niveau d'expérience (`experience`)

|Champ|Type|Spécificités|Description|
|-|-|-|-|
|id|INT|PRIMARY KEY, NOT NULL, UNSIGNED, AUTO_INCREMENT|L'identifiant du niveau d'expérience|
|yearsNumber|VARCHAR(20)|NOT NULL|Le nombre d'années du niveau d'expérience|
|createdAt|TIMESTAMP|NOT NULL, DEFAULT CURRENT_TIMESTAMP|La date de création du niveau d'expérience|
|updatedAt|TIMESTAMP|NULL|La date de la dernière mise à jour du niveau d'expérience|

## Secteur d'activité (`sector`)

|Champ|Type|Spécificités|Description|
|-|-|-|-|
|id|INT|PRIMARY KEY, NOT NULL, UNSIGNED, AUTO_INCREMENT|L'identifiant du secteur d'activité|
|sectorName|VARCHAR(64)|NOT NULL|Le nom du secteur d'activité|
|createdAt|TIMESTAMP|NOT NULL, DEFAULT CURRENT_TIMESTAMP|La date de création du secteur d'activité|
|updatedAt|TIMESTAMP|NULL|La date de la dernière mise à jour du secteur d'activité|

## Match (`matchup`)

|Champ|Type|Spécificités|Description|
|-|-|-|-|
|id|INT|PRIMARY KEY, NOT NULL, UNSIGNED, AUTO_INCREMENT|L'identifiant de match|
|candidateStatus|BOOLEAN|NOT NULL|Le status d'intérêt du candidat (par défaut `false`)|
|recruiterStatus|BOOLEAN|NOT NULL|Le statut d'intérêt du recruter (par défaut `false`)|
|matchStatus|BOOLEAN|NOT NULL|Le statut du match (par défaut `false`)|
|createdAt|TIMESTAMP|NOT NULL, DEFAULT CURRENT_TIMESTAMP|La date de création du match|
|updatedAt|TIMESTAMP|NULL|La date de la dernière mise à jour du match|
|job|entity|NOT NULL, FOREIGN KEY|Le job (autre entité) pour le match|
|candidate|entity|NOT NULL, FOREIGN KEY|Le candidat (autre entité) pour le match|

## Adresse (`address`)

|Champ|Type|Spécificités|Description|
|-|-|-|-|
|id|INT|PRIMARY KEY, NOT NULL, UNSIGNED, AUTO_INCREMENT|L'identifiant de match|
|streetNumber|TINYINT|NULL, UNSIGNED|Le numero de rue|
|streeName|VARCHAR (128) |NOT NULL|Le nom de la rue|
|zip|VARCHAR (5) |NOT NULL|Le code postal|
|city|VARCHAR (128)|NOT NULL|Le nom de la ville|
|department|VARCHAR (128)|NULL|Le département|
|createdAt|DATETIME|NOT NULL, DEFAULT CURRENT_TIMESTAMP|La date de création de l'adresse|
|updatedAt|DATETIME|NULL|La date de la dernière mise à jour de l'adresse|
