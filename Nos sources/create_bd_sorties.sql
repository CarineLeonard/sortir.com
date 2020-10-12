-- Script de création de la base de données SORTIES
--   type :      SQL Server 2012
--


CREATE TABLE ETAT (                                                     -- modification des noms "id" pour respecter symfony 
    id   INTEGER NOT NULL,
    libelle   VARCHAR(30) NOT NULL
)

ALTER TABLE ETAT ADD constraint etat_pk PRIMARY KEY (id)


CREATE TABLE INSCRIPTION (
    id                              INTEGER NOT NULL,                   -- id créé automatiquement dans entity 
    date_inscription              datetime NOT NULL,                    -- datetime en adéquation avec symfony
    sortie_no_sortie             INTEGER NOT NULL,
    participant_no_participant   INTEGER NOT NULL
)

ALTER TABLE INSCRIPTION ADD constraint inscription_pk PRIMARY KEY  (SORTIE_no_sortie, PARTICIPANT_no_participant)


CREATE TABLE LIEU (
    no_lieu         INTEGER NOT NULL,
    nom_lieu        VARCHAR(30) NOT NULL,
    rue             VARCHAR(30),
    latitude           FLOAT,
    longitude          FLOAT,
    villes_no_ville   INTEGER NOT NULL,
)

ALTER TABLE LIEU ADD constraint lieu_pk PRIMARY KEY  (no_lieu)


CREATE TABLE PARTICIPANT (
    no_participant   INTEGER NOT NULL,
	pseudo           VARCHAR(30) NOT NULL,
    nom              VARCHAR(30) NOT NULL,
    prenom           VARCHAR(30) NOT NULL,
    telephone        VARCHAR(15),
    mail             VARCHAR(20) NOT NULL,
	mot_de_passe	 VARCHAR(255) NOT NULL,             -- 255 est la recommandation pour les password encodés  
    administrateur   bit NOT NULL,
    actif            bit NOT NULL,
	campus_no_campus    INTEGER NOT NULL
)

ALTER TABLE PARTICIPANT ADD constraint participant_pk PRIMARY KEY  (no_participant)

ALTER TABLE PARTICIPANT add constraint participantSortie_pseudo_uk unique (pseudo)

CREATE TABLE CAMPUS (
    no_campus       INTEGER NOT NULL,
    nom_campus      VARCHAR(30) NOT NULL
)

ALTER TABLE CAMPUS ADD constraint campus_pk PRIMARY KEY (no_campus)

CREATE TABLE SORTIE (
    no_sortie                     INTEGER NOT NULL,
    nom                           VARCHAR(30) NOT NULL,
    datedebut                     smalldatetime NOT NULL,               -- datetime en adéquation avec symfony
    duree                         INTEGER,
    datecloture                   smalldatetime NOT NULL,               -- datetime en adéquation avec symfony
    nbinscriptionsmax             INTEGER NOT NULL,
    descriptioninfos              VARCHAR(500),
    etatsortie                    INTEGER,
	urlPhoto                      VARCHAR(250),
    organisateur                  INTEGER NOT NULL,
    lieu_no_lieu                 INTEGER NOT NULL,
    etat_no_etat                 INTEGER NOT NULL
)

ALTER TABLE SORTIE ADD constraint sortie_pk PRIMARY KEY  (no_sortie)

CREATE TABLE VILLE (
    no_ville      INTEGER NOT NULL,
    nom_ville     VARCHAR(30) NOT NULL,
    code_postal   VARCHAR(10) NOT NULL
)

ALTER TABLE VILLE ADD constraint ville_pk PRIMARY KEY  (no_ville)

ALTER TABLE INSCRIPTION
    ADD CONSTRAINT inscription_participant_fk FOREIGN KEY ( participant_no_participant )
        REFERENCES participant ( no_participant )
ON DELETE NO ACTION 
    ON UPDATE no action 

ALTER TABLE INSCRIPTIONS
    ADD CONSTRAINT inscription_sortie_fk FOREIGN KEY ( sortie_no_sortie )
        REFERENCES sortie ( no_sortie )
ON DELETE NO ACTION 
    ON UPDATE no action 


ALTER TABLE LIEU
    ADD CONSTRAINT lieu_ville_fk FOREIGN KEY ( ville_no_ville )
        REFERENCES ville ( no_ville )
ON DELETE NO ACTION 
    ON UPDATE no action 
	
ALTER TABLE SORTIE
    ADD CONSTRAINT sortie_etat_fk FOREIGN KEY ( etat_no_etat )
        REFERENCES etat ( no_etat )
ON DELETE NO ACTION 
    ON UPDATE no action 

ALTER TABLE SORTIE
    ADD CONSTRAINT sortie_lieu_fk FOREIGN KEY ( lieu_no_lieu )
        REFERENCES lieu ( no_lieu )
ON DELETE NO ACTION 
    ON UPDATE no action 

ALTER TABLE SORTIES
    ADD CONSTRAINT sortie_participant_fk FOREIGN KEY ( organisateur )
        REFERENCES participant ( no_participant )
ON DELETE NO ACTION 
    ON UPDATE no action 


