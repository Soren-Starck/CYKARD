CREATE TABLE app_db
(
    login             VARCHAR(30),
    nom               VARCHAR(30),
    prenom            VARCHAR(30),
    email             VARCHAR(255),
    mdphache          VARCHAR(255),
    mdp               VARCHAR(50),
    idtableau         INT,
    codetableau       VARCHAR(255),
    titretableau      VARCHAR(50),
    participants      jsonb,
    idcolonne         INT,
    titrecolonne      VARCHAR(50),
    idcarte           INT,
    titrecarte        VARCHAR(50),
    descriptifcarte   TEXT,
    couleurcarte      VARCHAR(7),
    affectationscarte jsonb,
    CONSTRAINT pk_db PRIMARY KEY (login, idcarte)
);