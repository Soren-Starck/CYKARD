-- -------------------------------------------------------------
-- TablePlus 5.9.5(545)
--
-- https://tableplus.com/
--
-- Database: iut
-- Generation Time: 2024-03-28 16:02:28.2320
-- -------------------------------------------------------------


DROP TABLE IF EXISTS "gozzog"."carte";
-- This script only contains the table creation statements and does not fully represent the table in the database. It's still missing: indices, triggers. Do not use it as a backup.

-- Table Definition
CREATE TABLE "gozzog"."carte" (
    "id" int4 NOT NULL,
    "titrecarte" varchar(50) NOT NULL DEFAULT 'New card'::character varying,
    "descriptifcarte" text,
    "couleurcarte" varchar(7) NOT NULL DEFAULT '#ffffff'::character varying CHECK (char_length((couleurcarte)::text) = 7),
    "colonne_id" int4 NOT NULL,
    "position" int4 NOT NULL DEFAULT 0,
    PRIMARY KEY ("id")
);

DROP TABLE IF EXISTS "gozzog"."colonne";
-- This script only contains the table creation statements and does not fully represent the table in the database. It's still missing: indices, triggers. Do not use it as a backup.

-- Table Definition
CREATE TABLE "gozzog"."colonne" (
    "id" int4 NOT NULL,
    "titrecolonne" varchar(50) NOT NULL DEFAULT 'New column'::character varying,
    "tableau_id" int4 NOT NULL,
    "position" int4 NOT NULL DEFAULT 0,
    PRIMARY KEY ("id")
);

DROP TABLE IF EXISTS "gozzog"."tableau";
-- This script only contains the table creation statements and does not fully represent the table in the database. It's still missing: indices, triggers. Do not use it as a backup.

-- Table Definition
CREATE TABLE "gozzog"."tableau" (
    "id" int4 NOT NULL,
    "titretableau" varchar(50) NOT NULL DEFAULT 'New table'::character varying,
    "codetableau" varchar(50) NOT NULL DEFAULT generate_random_string(16) CHECK (char_length((codetableau)::text) = 16),
    PRIMARY KEY ("id")
);

DROP TABLE IF EXISTS "gozzog"."user";
-- This script only contains the table creation statements and does not fully represent the table in the database. It's still missing: indices, triggers. Do not use it as a backup.

-- Table Definition
CREATE TABLE "gozzog"."user" (
    "login" varchar(180) NOT NULL,
    "roles" json NOT NULL,
    "password" varchar(255) NOT NULL,
    "nom" varchar(255) DEFAULT NULL::character varying,
    "prenom" varchar(255) DEFAULT NULL::character varying,
    "email" varchar(255) NOT NULL,
    "is_verified" bool NOT NULL,
    "verification_token" varchar(255) DEFAULT NULL::character varying,
    PRIMARY KEY ("login")
);

DROP TABLE IF EXISTS "gozzog"."user_carte";
-- This script only contains the table creation statements and does not fully represent the table in the database. It's still missing: indices, triggers. Do not use it as a backup.

-- Table Definition
CREATE TABLE "gozzog"."user_carte" (
    "user_login" varchar(180) NOT NULL,
    "carte_id" int4 NOT NULL,
    PRIMARY KEY ("user_login","carte_id")
);

DROP TABLE IF EXISTS "gozzog"."user_tableau";
-- This script only contains the table creation statements and does not fully represent the table in the database. It's still missing: indices, triggers. Do not use it as a backup.

-- Table Definition
CREATE TABLE "gozzog"."user_tableau" (
    "user_login" varchar(180) NOT NULL,
    "tableau_id" int4 NOT NULL,
    "user_role" varchar NOT NULL DEFAULT 'USER_EDITOR'::character varying,
    PRIMARY KEY ("user_login","tableau_id")
);

INSERT INTO "gozzog"."carte" ("id", "titrecarte", "descriptifcarte", "couleurcarte", "colonne_id", "position") VALUES
(7, 'TestCreation', 'cailloufzafeza feza', '#ffffff', 49, 0),
(9, 'New titre', 'caillllllooouu', '#ffffff', 52, 0),
(19, 'cailloutest', 'caillllllooouu', '#f96363', 16, 0),
(21, 'blabla', NULL, '#ffffff', 14, 0),
(23, 'blabla', NULL, '#ffffff', 50, 0),
(25, 'blabla', NULL, '#ffffff', 33, 0),
(26, 'blabla', NULL, '#ffffff', 47, 0);

INSERT INTO "gozzog"."colonne" ("id", "titrecolonne", "tableau_id", "position") VALUES
(14, 'Modified titre', 18, 0),
(16, 'Colonne1', 18, 0),
(33, 'prout', 18, 0),
(47, 'New colonne', 18, 0),
(48, 'New colonne', 18, 0),
(49, 'New colonne', 18, 0),
(50, 'New colonne', 18, 0),
(51, 'New colonne', 18, 0),
(52, 'New colonne', 18, 0);

INSERT INTO "gozzog"."tableau" ("id", "titretableau", "codetableau") VALUES
(17, 'Nouveau titre', 'hmCw6Mj9gscdHxv1'),
(18, 'Modified titre', 'hmCw6Mj9gscdHxv2'),
(19, 'SorenTabTest', 'hmCw6Mj9gscdHxv3'),
(20, 'TeSTES', 'hmCw6Mj9gscdHxv4'),
(22, 'Modified titre', 'hmCw6Mj9gscdHxv5'),
(49, 'Modified titre', '2JxdqZ8XEgv9Fbo2'),
(50, 'new', 'd8a9pQwj7xZbk70Q');

INSERT INTO "gozzog"."user" ("login", "roles", "password", "nom", "prenom", "email", "is_verified", "verification_token") VALUES
('cgman', '["ROLE_USER"]', '$2y$10$/fLtf.iHBLcPzId3j0sNO.uJ.i.ubyREY8Ty5lApT4YXOIJTIMPeq', NULL, NULL, 'cgman@mail.com', 't', NULL),
('cgman2', '["ROLE_USER"]', '$2y$10$Lw/Lm019HaTFgoeXp0PPgu/ClrAZloyTn4YABOcKEJpbC8kc2UXMG', NULL, NULL, 'cgman2@mail.com', 'f', '179b56a70f16057bc3c832931e694864'),
('cgman3', '["ROLE_USER"]', '$2y$10$0z5qg3JKwGKQFASxKTTeGOu8o.7FY0y2eWVo.bEWNaVC1imQqb4o6', NULL, NULL, 'cgman3@mail.com', 't', NULL),
('garroc', '["ROLE_USER"]', '$2y$10$piQrPNvZVKCFGXj2I7XSnOZJInAmyuH5Edp/N.kvk75oiRpNFsa9a', NULL, NULL, 'garro.clement83@gmail.com', 'f', '8c8129d515462bf164ba87f224d71005'),
('starck', '["ROLE_USER"]', '$2y$10$gYCxejLDpqWYEw5YXLQ9p.YAWSdZQEhOwtwqlMavZ9QVVsbLX6j7K', NULL, NULL, 'soren.starck@free.fr', 'f', '32ec072ca23d3992000d3d5889bc92cb');

INSERT INTO "gozzog"."user_carte" ("user_login", "carte_id") VALUES
('cgman', 9);

INSERT INTO "gozzog"."user_tableau" ("user_login", "tableau_id", "user_role") VALUES
('cgman', 17, 'USER_READ'),
('cgman', 18, 'USER_ADMIN'),
('cgman', 22, 'USER_ADMIN'),
('cgman', 49, 'USER_ADMIN'),
('cgman', 50, 'USER_ADMIN'),
('garroc', 18, 'USER_EDITOR'),
('garroc', 49, 'USER_READ'),
('starck', 18, 'USER_EDITOR'),
('starck', 19, 'USER_EDITOR'),
('starck', 22, 'USER_EDITOR'),
('starck', 49, 'USER_READ');

ALTER TABLE "gozzog"."carte" ADD FOREIGN KEY ("colonne_id") REFERENCES "gozzog"."colonne"("id") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "gozzog"."colonne" ADD FOREIGN KEY ("tableau_id") REFERENCES "gozzog"."tableau"("id") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "gozzog"."user_carte" ADD FOREIGN KEY ("carte_id") REFERENCES "gozzog"."carte"("id") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "gozzog"."user_carte" ADD FOREIGN KEY ("user_login") REFERENCES "gozzog"."user"("login") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "gozzog"."user_tableau" ADD FOREIGN KEY ("tableau_id") REFERENCES "gozzog"."tableau"("id") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "gozzog"."user_tableau" ADD FOREIGN KEY ("user_login") REFERENCES "gozzog"."user"("login") ON DELETE CASCADE ON UPDATE CASCADE;
