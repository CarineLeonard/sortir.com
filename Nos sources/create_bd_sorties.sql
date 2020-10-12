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
    sortie_id             INTEGER NOT NULL,
    participant_id   INTEGER NOT NULL
)

ALTER TABLE INSCRIPTION ADD constraint inscription_pk PRIMARY KEY  (SORTIE_id, PARTICIPANT_id)


CREATE TABLE LIEU (
    id         INTEGER NOT NULL,
    nom_lieu        VARCHAR(30) NOT NULL,
    rue             VARCHAR(30),
    latitude           FLOAT,
    longitude          FLOAT,
    villes_id   INTEGER NOT NULL,
)

ALTER TABLE LIEU ADD constraint lieu_pk PRIMARY KEY  (id)


CREATE TABLE PARTICIPANT (
    id   INTEGER NOT NULL,
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

ALTER TABLE PARTICIPANT ADD constraint participant_pk PRIMARY KEY  (id)

ALTER TABLE PARTICIPANT add constraint participantSortie_pseudo_uk unique (pseudo)

CREATE TABLE CAMPUS (
    id             INTEGER NOT NULL,
    nom_campus      VARCHAR(30) NOT NULL
)

ALTER TABLE CAMPUS ADD constraint campus_pk PRIMARY KEY (id)

CREATE TABLE SORTIE (
    id                           INTEGER NOT NULL,
    nom                           VARCHAR(30) NOT NULL,
    datedebut                     smalldatetime NOT NULL,               -- datetime en adéquation avec symfony
    duree                         INTEGER,
    datecloture                   smalldatetime NOT NULL,               -- datetime en adéquation avec symfony
    nbinscriptionsmax             INTEGER NOT NULL,
    descriptioninfos              VARCHAR(500),
    etatsortie                    INTEGER,
	urlPhoto                      VARCHAR(250),
    organisateur                  INTEGER NOT NULL,
    lieu_id                 INTEGER NOT NULL,
    etat_id                INTEGER NOT NULL
)

ALTER TABLE SORTIE ADD constraint sortie_pk PRIMARY KEY  (id)

CREATE TABLE VILLE (
    id      INTEGER NOT NULL,
    nom_ville     VARCHAR(30) NOT NULL,
    code_postal   VARCHAR(10) NOT NULL
)

ALTER TABLE VILLE ADD constraint ville_pk PRIMARY KEY  (id)

ALTER TABLE INSCRIPTION
    ADD CONSTRAINT inscription_participant_fk FOREIGN KEY ( participant_id )
        REFERENCES participant ( id )
ON DELETE NO ACTION 
    ON UPDATE no action 

ALTER TABLE INSCRIPTIONS
    ADD CONSTRAINT inscription_sortie_fk FOREIGN KEY ( sortie_id)
        REFERENCES sortie ( id )
ON DELETE NO ACTION 
    ON UPDATE no action 


ALTER TABLE LIEU
    ADD CONSTRAINT lieu_ville_fk FOREIGN KEY ( ville_id )
        REFERENCES ville ( id )
ON DELETE NO ACTION 
    ON UPDATE no action 
	
ALTER TABLE SORTIE
    ADD CONSTRAINT sortie_etat_fk FOREIGN KEY ( etat_id )
        REFERENCES etat ( id )
ON DELETE NO ACTION 
    ON UPDATE no action 

ALTER TABLE SORTIE
    ADD CONSTRAINT sortie_lieu_fk FOREIGN KEY ( lieu_id)
        REFERENCES lieu ( id )
ON DELETE NO ACTION 
    ON UPDATE no action 

ALTER TABLE SORTIES
    ADD CONSTRAINT sortie_participant_fk FOREIGN KEY ( organisateur )
        REFERENCES participant ( id )
ON DELETE NO ACTION 
    ON UPDATE no action 


