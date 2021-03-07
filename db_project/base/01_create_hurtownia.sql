


DROP SCHEMA hurtownia CASCADE;

CREATE SCHEMA hurtownia;
SET search_path TO hurtownia;



CREATE SEQUENCE hurtownia.producent_id_producent_seq;

CREATE TABLE hurtownia.Producent (
                id_producent INTEGER NOT NULL DEFAULT nextval('hurtownia.producent_id_producent_seq'),
                nazwa VARCHAR NOT NULL,
                email VARCHAR NOT NULL,
                telefon VARCHAR(9) NOT NULL,
                CONSTRAINT producent_pk PRIMARY KEY (id_producent)
);

ALTER SEQUENCE hurtownia.producent_id_producent_seq OWNED BY hurtownia.Producent.id_producent;



CREATE SEQUENCE hurtownia.kategoria_id_kategoria_seq;

CREATE TABLE hurtownia.Kategoria (
                id_kategoria INTEGER NOT NULL DEFAULT nextval('hurtownia.kategoria_id_kategoria_seq'),
                nazwa VARCHAR NOT NULL,
                opis VARCHAR,
                CONSTRAINT kategoria_pk PRIMARY KEY (id_kategoria)
);

ALTER SEQUENCE hurtownia.kategoria_id_kategoria_seq OWNED BY hurtownia.Kategoria.id_kategoria;



CREATE SEQUENCE hurtownia.produkt_id_produkt_seq;

CREATE TABLE hurtownia.Produkt (
                id_produkt INTEGER NOT NULL DEFAULT nextval('hurtownia.produkt_id_produkt_seq'),
                id_kategoria INTEGER NOT NULL,
                id_producent INTEGER NOT NULL,
                nazwa VARCHAR NOT NULL,
                cena NUMERIC(10,2) NOT NULL,
                CONSTRAINT produkt_pk PRIMARY KEY (id_produkt)
);

ALTER SEQUENCE hurtownia.produkt_id_produkt_seq OWNED BY hurtownia.Produkt.id_produkt;



CREATE SEQUENCE hurtownia.zatrudnienie_id_zatrudnionie_seq;

CREATE TABLE hurtownia.Zatrudnienie (
                id_zatrudnienie INTEGER NOT NULL DEFAULT nextval('hurtownia.zatrudnienie_id_zatrudnionie_seq'),
                poczatek_zatrudnienia DATE NOT NULL,
                koniec_zatrudnienia DATE,
                CONSTRAINT zatrudnienie_pk PRIMARY KEY (id_zatrudnienie)
);

ALTER SEQUENCE hurtownia.zatrudnienie_id_zatrudnionie_seq OWNED BY hurtownia.Zatrudnienie.id_zatrudnienie;



CREATE SEQUENCE hurtownia.adres_id_adres_seq;

CREATE TABLE hurtownia.Adres (
                id_adres INTEGER NOT NULL DEFAULT nextval('hurtownia.adres_id_adres_seq'),
                ulica VARCHAR NOT NULL,
                nr_budynku VARCHAR NOT NULL,
                kod_pocztowy VARCHAR(6) NOT NULL,
                miasto VARCHAR NOT NULL,
                CONSTRAINT adres_pk PRIMARY KEY (id_adres)
);

ALTER SEQUENCE hurtownia.adres_id_adres_seq OWNED BY hurtownia.Adres.id_adres;



CREATE SEQUENCE hurtownia.klient_id_klient_seq;

CREATE TABLE hurtownia.Klient (
                id_firma INTEGER NOT NULL DEFAULT nextval('hurtownia.klient_id_klient_seq'),
                nazwa VARCHAR NOT NULL,
                NIP VARCHAR(10) NOT NULL,
                email VARCHAR NOT NULL,
                telefon VARCHAR(9) NOT NULL,
                id_adres INTEGER NOT NULL,
                CONSTRAINT klient_pk PRIMARY KEY (id_firma)
);

ALTER SEQUENCE hurtownia.klient_id_klient_seq OWNED BY hurtownia.Klient.id_firma;



CREATE SEQUENCE hurtownia.zamowienie_id_zamowienie_seq;

CREATE TABLE hurtownia.Zamowienie (
                id_zamowienie INTEGER NOT NULL DEFAULT nextval('hurtownia.zamowienie_id_zamowienie_seq'),
                status VARCHAR NOT NULL,
                id_firma INTEGER NOT NULL,
                data_zamowienia DATE NOT NULL,
                kwota NUMERIC(10,2) NOT NULL,
                CONSTRAINT zamowienie_pk PRIMARY KEY (id_zamowienie)
);

ALTER SEQUENCE hurtownia.zamowienie_id_zamowienie_seq OWNED BY hurtownia.Zamowienie.id_zamowienie;



CREATE SEQUENCE hurtownia.platnosc_id_platnosc_seq;

CREATE TABLE hurtownia.Platnosc (
                id_platnosc INTEGER NOT NULL DEFAULT nextval('hurtownia.platnosc_id_platnosc_seq'),
                id_zamowienie INTEGER NOT NULL,
                status VARCHAR NOT NULL,
                timestamp_wykonania TIMESTAMP,
                CONSTRAINT platnosc_pk PRIMARY KEY (id_platnosc)
);

ALTER SEQUENCE hurtownia.platnosc_id_platnosc_seq OWNED BY hurtownia.Platnosc.id_platnosc;



CREATE TABLE hurtownia.Zamowienie_szczegoly (
                id_zamowienie INTEGER NOT NULL,
                id_produkt INTEGER NOT NULL,
                id_magazyn INTEGER NOT NULL,
                ilosc INTEGER NOT NULL,
                CONSTRAINT zamowienie_szczegoly_pk PRIMARY KEY (id_zamowienie, id_produkt, id_magazyn)
);



CREATE SEQUENCE hurtownia.magazyn_id_magazyn_seq;

CREATE TABLE hurtownia.Magazyn (
                id_magazyn INTEGER NOT NULL DEFAULT nextval('hurtownia.magazyn_id_magazyn_seq'),
                glowny BOOLEAN NOT NULL,
                id_adres INTEGER NOT NULL,
                CONSTRAINT magazyn_pk PRIMARY KEY (id_magazyn)
);

ALTER SEQUENCE hurtownia.magazyn_id_magazyn_seq OWNED BY hurtownia.Magazyn.id_magazyn;



CREATE TABLE hurtownia.Magazyn_stan (
                id_produkt INTEGER NOT NULL,
                id_magazyn INTEGER NOT NULL,
                ilosc INTEGER NOT NULL,
                CONSTRAINT magazyn_stan_pk PRIMARY KEY (id_produkt, id_magazyn)
);



CREATE SEQUENCE hurtownia.uprawnienia_id_uprawnienia_seq;

CREATE TABLE hurtownia.Uprawnienia (
                id_uprawnienia INTEGER NOT NULL DEFAULT nextval('hurtownia.uprawnienia_id_uprawnienia_seq'),
                nazwa_stanowiska VARCHAR NOT NULL,
                opis VARCHAR NOT NULL,
                CONSTRAINT uprawnienia_pk PRIMARY KEY (id_uprawnienia)
);

ALTER SEQUENCE hurtownia.uprawnienia_id_uprawnienia_seq OWNED BY hurtownia.Uprawnienia.id_uprawnienia;



CREATE SEQUENCE hurtownia.pracownik_id_pracownik_seq;

CREATE TABLE hurtownia.Pracownik (
                id_pracownik INTEGER NOT NULL DEFAULT nextval('hurtownia.pracownik_id_pracownik_seq'),
                id_zatrudnienie INTEGER NOT NULL,
                id_uprawnienia INTEGER NOT NULL,
                imie VARCHAR NOT NULL,
                nazwisko VARCHAR NOT NULL,
                email VARCHAR NOT NULL,
                telefon VARCHAR(9) NOT NULL,
                id_adres INTEGER NOT NULL,
                id_magazyn INTEGER,
                CONSTRAINT pracownik_pk PRIMARY KEY (id_pracownik)
);

ALTER SEQUENCE hurtownia.pracownik_id_pracownik_seq OWNED BY hurtownia.Pracownik.id_pracownik;



CREATE TABLE hurtownia.Weryfikacja (
                id_pracownik INTEGER NOT NULL,
                login VARCHAR NOT NULL,
                haslo VARCHAR NOT NULL,
                CONSTRAINT weryfikacja_pk PRIMARY KEY (id_pracownik)
);



ALTER TABLE hurtownia.Produkt ADD CONSTRAINT producent_produkt_fk
FOREIGN KEY (id_producent)
REFERENCES hurtownia.Producent (id_producent);

ALTER TABLE hurtownia.Produkt ADD CONSTRAINT kategoria_produkt_fk
FOREIGN KEY (id_kategoria)
REFERENCES hurtownia.Kategoria (id_kategoria);

ALTER TABLE hurtownia.Zamowienie_szczegoly ADD CONSTRAINT magazyn_stan_zamowienie_szczegoly_fk
FOREIGN KEY (id_produkt, id_magazyn)
REFERENCES hurtownia.Magazyn_stan (id_produkt, id_magazyn);

ALTER TABLE hurtownia.Magazyn_stan ADD CONSTRAINT produkt_magazyn_stan_fk
FOREIGN KEY (id_produkt)
REFERENCES hurtownia.Produkt (id_produkt);

ALTER TABLE hurtownia.Pracownik ADD CONSTRAINT zatrudnienie_pracownik_fk
FOREIGN KEY (id_zatrudnienie)
REFERENCES hurtownia.Zatrudnienie (id_zatrudnienie);

ALTER TABLE hurtownia.Magazyn ADD CONSTRAINT adres_magazyn_fk
FOREIGN KEY (id_adres)
REFERENCES hurtownia.Adres (id_adres);

ALTER TABLE hurtownia.Pracownik ADD CONSTRAINT adres_pracownik_fk
FOREIGN KEY (id_adres)
REFERENCES hurtownia.Adres (id_adres);

ALTER TABLE hurtownia.Klient ADD CONSTRAINT adres_klient_fk
FOREIGN KEY (id_adres)
REFERENCES hurtownia.Adres (id_adres);

ALTER TABLE hurtownia.Zamowienie ADD CONSTRAINT klient_zamowienie_fk
FOREIGN KEY (id_firma)
REFERENCES hurtownia.Klient (id_firma);

ALTER TABLE hurtownia.Zamowienie_szczegoly ADD CONSTRAINT zamowienie_zamowienie_szczegoly_fk
FOREIGN KEY (id_zamowienie)
REFERENCES hurtownia.Zamowienie (id_zamowienie);

ALTER TABLE hurtownia.Platnosc ADD CONSTRAINT zamowienie_platnosc_fk
FOREIGN KEY (id_zamowienie)
REFERENCES hurtownia.Zamowienie (id_zamowienie);

ALTER TABLE hurtownia.Magazyn_stan ADD CONSTRAINT magazyn_magazyn_stan_fk
FOREIGN KEY (id_magazyn)
REFERENCES hurtownia.Magazyn (id_magazyn);

ALTER TABLE hurtownia.Pracownik ADD CONSTRAINT magazyn_pracownik_fk
FOREIGN KEY (id_magazyn)
REFERENCES hurtownia.Magazyn (id_magazyn);

ALTER TABLE hurtownia.Pracownik ADD CONSTRAINT uprawnienia_pracownik_fk
FOREIGN KEY (id_uprawnienia)
REFERENCES hurtownia.Uprawnienia (id_uprawnienia);

ALTER TABLE hurtownia.Weryfikacja ADD CONSTRAINT pracownik_weryfikacja_fk
FOREIGN KEY (id_pracownik)
REFERENCES hurtownia.Pracownik (id_pracownik);
