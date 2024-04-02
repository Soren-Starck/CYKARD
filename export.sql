SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: gozzog; Type: SCHEMA; Schema: -; Owner: gozzog
--

CREATE SCHEMA gozzog;


ALTER SCHEMA gozzog OWNER TO gozzog;

--
-- Name: generate_random_string(integer); Type: FUNCTION; Schema: gozzog; Owner: gozzog
--

CREATE FUNCTION gozzog.generate_random_string(length integer) RETURNS text
    LANGUAGE plpgsql
    AS $$
DECLARE
    chars text[] := '{0,1,2,3,4,5,6,7,8,9,A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z,a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z}';
    result text := '';
    i integer := 0;
BEGIN
    IF length < 0 THEN
        RAISE EXCEPTION 'Given length cannot be less than 0';
    END IF;
    FOR i IN 1..length LOOP
        result := result || chars[1+random()*(array_length(chars, 1)-1)];
    END LOOP;
    RETURN result;
END;
$$;


ALTER FUNCTION gozzog.generate_random_string(length integer) OWNER TO gozzog;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: carte; Type: TABLE; Schema: gozzog; Owner: gozzog
--

CREATE TABLE gozzog.carte (
    id integer NOT NULL,
    titrecarte character varying(50) DEFAULT 'New card'::character varying NOT NULL,
    descriptifcarte text,
    couleurcarte character varying(7) DEFAULT '#ffffff'::character varying NOT NULL,
    colonne_id integer NOT NULL,
    CONSTRAINT couleurcarte_length_check CHECK ((char_length((couleurcarte)::text) = 7))
);


ALTER TABLE gozzog.carte OWNER TO gozzog;

--
-- Name: carte_idcarte_seq; Type: SEQUENCE; Schema: gozzog; Owner: gozzog
--

ALTER TABLE gozzog.carte ALTER COLUMN id ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME gozzog.carte_idcarte_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: colonne; Type: TABLE; Schema: gozzog; Owner: gozzog
--

CREATE TABLE gozzog.colonne (
    id integer NOT NULL,
    titrecolonne character varying(50) DEFAULT 'New column'::character varying NOT NULL,
    tableau_id integer NOT NULL
);


ALTER TABLE gozzog.colonne OWNER TO gozzog;

--
-- Name: colonne_idcolonne_seq; Type: SEQUENCE; Schema: gozzog; Owner: gozzog
--

ALTER TABLE gozzog.colonne ALTER COLUMN id ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME gozzog.colonne_idcolonne_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: tableau; Type: TABLE; Schema: gozzog; Owner: gozzog
--

CREATE TABLE gozzog.tableau (
    id integer NOT NULL,
    titretableau character varying(50) DEFAULT 'New table'::character varying NOT NULL,
    codetableau character varying(50) DEFAULT gozzog.generate_random_string(16) NOT NULL,
    CONSTRAINT tableau_codetableau_check CHECK ((char_length((codetableau)::text) = 16))
);


ALTER TABLE gozzog.tableau OWNER TO gozzog;

--
-- Name: tableau_idtableau_seq; Type: SEQUENCE; Schema: gozzog; Owner: gozzog
--

ALTER TABLE gozzog.tableau ALTER COLUMN id ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME gozzog.tableau_idtableau_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: user; Type: TABLE; Schema: gozzog; Owner: gozzog
--

CREATE TABLE gozzog."user" (
    login character varying(180) NOT NULL,
    roles json NOT NULL,
    password character varying(255) NOT NULL,
    nom character varying(255) DEFAULT NULL::character varying,
    prenom character varying(255) DEFAULT NULL::character varying,
    email character varying(255) NOT NULL,
    is_verified boolean NOT NULL,
    verification_token character varying(255) DEFAULT NULL::character varying
);


ALTER TABLE gozzog."user" OWNER TO gozzog;

--
-- Name: user_carte; Type: TABLE; Schema: gozzog; Owner: gozzog
--

CREATE TABLE gozzog.user_carte (
    user_login character varying(180) NOT NULL,
    carte_id integer NOT NULL
);


ALTER TABLE gozzog.user_carte OWNER TO gozzog;

--
-- Name: user_tableau; Type: TABLE; Schema: gozzog; Owner: gozzog
--

CREATE TABLE gozzog.user_tableau (
    user_login character varying(180) NOT NULL,
    tableau_id integer NOT NULL,
    user_role character varying DEFAULT 'USER_EDITOR'::character varying NOT NULL
);


ALTER TABLE gozzog.user_tableau OWNER TO gozzog;

--
-- Data for Name: user; Type: TABLE DATA; Schema: gozzog; Owner: gozzog
--

COPY gozzog."user" (login, roles, password, nom, prenom, email, is_verified, verification_token) FROM stdin;
admin	["ROLE_USER"]	$2y$10$d1Bc3VW6jELefBk1Fqgat.iBdyUKbBl8i0S0dGdwlNBf6DBAK15hO	\N	\N	admin@gmail.com	f	6b46eec3589766dfde5108657789e070
\.

--
-- Name: carte_idcarte_seq; Type: SEQUENCE SET; Schema: gozzog; Owner: gozzog
--

SELECT pg_catalog.setval('gozzog.carte_idcarte_seq', 48, true);


--
-- Name: colonne_idcolonne_seq; Type: SEQUENCE SET; Schema: gozzog; Owner: gozzog
--

SELECT pg_catalog.setval('gozzog.colonne_idcolonne_seq', 79, true);


--
-- Name: tableau_idtableau_seq; Type: SEQUENCE SET; Schema: gozzog; Owner: gozzog
--

SELECT pg_catalog.setval('gozzog.tableau_idtableau_seq', 55, true);


--
-- Name: carte carte_pkey; Type: CONSTRAINT; Schema: gozzog; Owner: gozzog
--

ALTER TABLE ONLY gozzog.carte
    ADD CONSTRAINT carte_pkey PRIMARY KEY (id);


--
-- Name: colonne colonne_pkey; Type: CONSTRAINT; Schema: gozzog; Owner: gozzog
--

ALTER TABLE ONLY gozzog.colonne
    ADD CONSTRAINT colonne_pkey PRIMARY KEY (id);


--
-- Name: tableau tableau_pkey; Type: CONSTRAINT; Schema: gozzog; Owner: gozzog
--

ALTER TABLE ONLY gozzog.tableau
    ADD CONSTRAINT tableau_pkey PRIMARY KEY (id);


--
-- Name: user_carte user_carte_pkey; Type: CONSTRAINT; Schema: gozzog; Owner: gozzog
--

ALTER TABLE ONLY gozzog.user_carte
    ADD CONSTRAINT user_carte_pkey PRIMARY KEY (user_login, carte_id);


--
-- Name: user user_login_unique; Type: CONSTRAINT; Schema: gozzog; Owner: gozzog
--

ALTER TABLE ONLY gozzog."user"
    ADD CONSTRAINT user_login_unique UNIQUE (login);


--
-- Name: user user_pkey; Type: CONSTRAINT; Schema: gozzog; Owner: gozzog
--

ALTER TABLE ONLY gozzog."user"
    ADD CONSTRAINT user_pkey PRIMARY KEY (login);


--
-- Name: user_tableau user_tableau_pkey; Type: CONSTRAINT; Schema: gozzog; Owner: gozzog
--

ALTER TABLE ONLY gozzog.user_tableau
    ADD CONSTRAINT user_tableau_pkey PRIMARY KEY (user_login, tableau_id);


--
-- Name: idx_581604a5a76ed395; Type: INDEX; Schema: gozzog; Owner: gozzog
--

CREATE INDEX idx_581604a5a76ed395 ON gozzog.user_carte USING btree (user_login);


--
-- Name: idx_581604a5c9c7ceb6; Type: INDEX; Schema: gozzog; Owner: gozzog
--

CREATE INDEX idx_581604a5c9c7ceb6 ON gozzog.user_carte USING btree (carte_id);


--
-- Name: idx_65f87c44b062d5bc; Type: INDEX; Schema: gozzog; Owner: gozzog
--

CREATE INDEX idx_65f87c44b062d5bc ON gozzog.colonne USING btree (tableau_id);


--
-- Name: idx_9e7953bba76ed395; Type: INDEX; Schema: gozzog; Owner: gozzog
--

CREATE INDEX idx_9e7953bba76ed395 ON gozzog.user_tableau USING btree (user_login);


--
-- Name: idx_9e7953bbb062d5bc; Type: INDEX; Schema: gozzog; Owner: gozzog
--

CREATE INDEX idx_9e7953bbb062d5bc ON gozzog.user_tableau USING btree (tableau_id);


--
-- Name: idx_bad4fffd213eac9d; Type: INDEX; Schema: gozzog; Owner: gozzog
--

CREATE INDEX idx_bad4fffd213eac9d ON gozzog.carte USING btree (colonne_id);


--
-- Name: unique_codetableau; Type: INDEX; Schema: gozzog; Owner: gozzog
--

CREATE UNIQUE INDEX unique_codetableau ON gozzog.tableau USING btree (codetableau);


--
-- Name: carte carte_colonne_id_fkey; Type: FK CONSTRAINT; Schema: gozzog; Owner: gozzog
--

ALTER TABLE ONLY gozzog.carte
    ADD CONSTRAINT carte_colonne_id_fkey FOREIGN KEY (colonne_id) REFERENCES gozzog.colonne(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: colonne colonne_tableau_id_fkey; Type: FK CONSTRAINT; Schema: gozzog; Owner: gozzog
--

ALTER TABLE ONLY gozzog.colonne
    ADD CONSTRAINT colonne_tableau_id_fkey FOREIGN KEY (tableau_id) REFERENCES gozzog.tableau(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: carte fk_colonne_id; Type: FK CONSTRAINT; Schema: gozzog; Owner: gozzog
--

ALTER TABLE ONLY gozzog.carte
    ADD CONSTRAINT fk_colonne_id FOREIGN KEY (colonne_id) REFERENCES gozzog.colonne(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: user_carte user_carte_carte_id_fkey; Type: FK CONSTRAINT; Schema: gozzog; Owner: gozzog
--

ALTER TABLE ONLY gozzog.user_carte
    ADD CONSTRAINT user_carte_carte_id_fkey FOREIGN KEY (carte_id) REFERENCES gozzog.carte(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: user_carte user_carte_user_login_fkey; Type: FK CONSTRAINT; Schema: gozzog; Owner: gozzog
--

ALTER TABLE ONLY gozzog.user_carte
    ADD CONSTRAINT user_carte_user_login_fkey FOREIGN KEY (user_login) REFERENCES gozzog."user"(login) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: user_tableau user_tableau_tableau_id_fkey; Type: FK CONSTRAINT; Schema: gozzog; Owner: gozzog
--

ALTER TABLE ONLY gozzog.user_tableau
    ADD CONSTRAINT user_tableau_tableau_id_fkey FOREIGN KEY (tableau_id) REFERENCES gozzog.tableau(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: user_tableau user_tableau_user_login_fkey; Type: FK CONSTRAINT; Schema: gozzog; Owner: gozzog
--

ALTER TABLE ONLY gozzog.user_tableau
    ADD CONSTRAINT user_tableau_user_login_fkey FOREIGN KEY (user_login) REFERENCES gozzog."user"(login) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: DATABASE iut; Type: ACL; Schema: -; Owner: postgres
--

GRANT CONNECT ON DATABASE iut TO etudiantlp;


--
-- PostgreSQL database dump complete
--

