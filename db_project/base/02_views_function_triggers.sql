-- tworzenie widoku listy pracowników z informacjami: login, imie, nazwisko, pozycja, id magazynu, email, telefon, adres, zatrudnienie (od,do)

CREATE VIEW pracownicy_lista AS 
  SELECT p.id_pracownik,
    w.login,
    p.imie,
    p.nazwisko,
    u.nazwa_stanowiska,
    p.id_magazyn,
    p.email,
    p.telefon,
    a.ulica,
    a.nr_budynku,
    a.kod_pocztowy,
    a.miasto,
    z.poczatek_zatrudnienia,
    z.koniec_zatrudnienia
    FROM pracownik p JOIN adres a USING(id_adres) JOIN zatrudnienie z USING(id_zatrudnienie) JOIN weryfikacja w USING(id_pracownik) JOIN uprawnienia u USING(id_uprawnienia);



-- tworzenie widoku listy magazynów z informacjami: id, czy jest główny (t/f), adres, dane magazyniera odpowiedzialnego za magazyn

CREATE VIEW magazyny_lista AS
 SELECT m.id_magazyn,
    m.glowny,
    a.ulica,
    a.nr_budynku,
    a.kod_pocztowy,
    a.miasto,
    p.imie,
    p.nazwisko
   FROM magazyn m
	LEFT JOIN adres a USING(id_adres) LEFT JOIN pracownik p USING(id_magazyn) ORDER BY id_magazyn;


-- tworzenie widoku listy klientów z informacjami: id pracownika, login, okres zatrudnienia	
	
CREATE VIEW pracownicy_zatrudnienie_okres AS
 SELECT p.id_pracownik,
    w.login,
    z.poczatek_zatrudnienia,
    z.koniec_zatrudnienia 
   FROM pracownik p,
    zatrudnienie z,
    weryfikacja w
   WHERE w.id_pracownik=p.id_pracownik AND p.id_zatrudnienie=z.id_zatrudnienie;
	

-- tworzenie widoku listy klientów z informacjami: id firmy, nazwa firmy, nip, email, telefon, adres

CREATE VIEW klienci_lista AS
 SELECT k.id_firma,
    k.nazwa,
    k.nip,
    k.email,
    k.telefon,
    a.ulica,
    a.nr_budynku,
    a.kod_pocztowy,
    a.miasto
   FROM klient k
	LEFT JOIN adres a USING(id_adres) ORDER BY k.id_firma;
	

-- tworzenie widoku listy produktów z informacjami: id produkktu, ketegoria, nazwa, producent, cena

CREATE VIEW produkty_lista AS
 SELECT prkt.id_produkt,
    k.nazwa as kategoria,
    prkt.nazwa,
    prnt.nazwa as producent, 
    prkt.cena 
  FROM 
    produkt prkt,
    producent prnt, 
	kategoria k 
  WHERE 
    k.id_kategoria=prkt.id_kategoria AND 
	prkt.id_producent=prnt.id_producent;


-- tworzenie widoku stanu magazynów z informacjami: 

CREATE VIEW pelny_stan_magazynow AS 
  SELECT 
    prkt.id_produkt,
    k.nazwa AS kategoria,
    prkt.nazwa AS nazwa_produktu,
    prkt.cena,
    prkt.id_producent,
    prnt.nazwa AS producent,
	ms.id_magazyn,
	p.id_pracownik,
	ms.ilosc
  FROM 
	pracownik p,
	magazyn_stan ms, 
	produkt prkt, 
	kategoria k,
	producent prnt
  WHERE 
	p.id_magazyn=ms.id_magazyn AND 
	prkt.id_produkt=ms.id_produkt AND
	k.id_kategoria=prkt.id_kategoria AND
	prkt.id_producent=prnt.id_producent;
		

-- widok listy klientów z id magazynów, w których już coś zamówili
CREATE VIEW lista_klientow_z_magazynami AS
 SELECT DISTINCT  
    k.id_firma, 
	k.nazwa, 
	k.nip, 
	k.email,
	k.telefon,
	k.id_adres,
	a.ulica,
	a.nr_budynku,
	a.kod_pocztowy,
	a.miasto,
	zs.id_magazyn
  FROM 
    klient k, 
	zamowienie z, 
	zamowienie_szczegoly zs,
	adres a
  WHERE 
    k.id_firma=z.id_firma AND 
	z.id_zamowienie=zs.id_zamowienie AND
	k.id_adres=a.id_adres;

	
-- pelny widok szczegolow zamowień, zawiera informacje o produkcie (w tym z jakiego magazynu), zamowieniu oraz ilości produktu
CREATE VIEW pelny_widok_szczegoly_zamowienia AS
  SELECT zs.id_magazyn, 
    zs.id_zamowienie, 
	zs.id_produkt, 
	psm.kategoria, 
	psm.nazwa_produktu, 
	psm.id_producent, 
	psm.producent, 
	psm.cena, 
	zs.ilosc 
  FROM 
    pelny_stan_magazynow psm,
	zamowienie_szczegoly zs
  WHERE 
    zs.id_produkt=psm.id_produkt AND 
	zs.id_magazyn=psm.id_magazyn 
  ORDER BY 
    id_magazyn, 
	id_zamowienie;		
	
	
-- wszystkie zamowienia widok z pełnymi informacjami
CREATE VIEW zamowienia_lista AS
  SELECT 
	DISTINCT z.id_zamowienie,
	zs.id_magazyn,
	z.status,
	z.id_firma,
	k.nazwa,
	k.nip,
	k.email,
	k.telefon,
	a.id_adres,
	a.ulica,
	a.nr_budynku,
	a.kod_pocztowy,
	a.miasto,
	z.data_zamowienia,
	z.kwota
  FROM zamowienie_szczegoly zs JOIN 
	zamowienie z USING(id_zamowienie) JOIN 
	klient k USING(id_firma) 
	JOIN adres a USING(id_adres)
	WHERE z.kwota >0
  ORDER BY data_zamowienia, z.status DESC;
  
  
 -- wszystkie zamowienia widok z pełnymi informacjami
CREATE VIEW platnosci_lista AS
  SELECT 
	DISTINCT p.id_platnosc,
	p.status as status_platnosci,
	p.timestamp_wykonania,
	z.id_zamowienie,
	zs.id_magazyn,
	z.status as status_zamowienia,
	z.id_firma,
	k.nazwa,
	k.nip,
	k.email,
	k.telefon,
	a.id_adres,
	a.ulica,
	a.nr_budynku,
	a.kod_pocztowy,
	a.miasto,
	z.data_zamowienia,
	z.kwota
  FROM zamowienie_szczegoly zs JOIN 
	platnosc p USING(id_zamowienie) JOIN 
	zamowienie z USING(id_zamowienie) JOIN 
	klient k USING(id_firma) JOIN 
	adres a USING(id_adres) 
	WHERE z.kwota >0
  ORDER BY timestamp_wykonania, p.status;
 
 
 
		
----------------------------------------------------------------------------------- FUNKCJE -----------------------------------------------------------------------------------


-- dodawanie nowego adresu

CREATE OR REPLACE FUNCTION dodajAdres (ul VARCHAR ,nr VARCHAR,kp VARCHAR(6), m VARCHAR) RETURNS BOOLEAN AS
$$
BEGIN
	IF NOT EXISTS (SELECT * FROM adres a WHERE a.ulica=$1 AND a.nr_budynku=$2 AND a.kod_pocztowy=$3 AND a.miasto=$4)
	THEN
		INSERT INTO adres VALUES(DEFAULT, $1, $2 , $3, $4);
		RETURN TRUE;
	ELSE
		RETURN false;
	END IF;
END;

$$
  LANGUAGE 'plpgsql';
  


-- dodawanie nowego magazynu 

CREATE OR REPLACE FUNCTION dodajMagazyn (main BOOLEAN, ul VARCHAR, nr VARCHAR, kp VARCHAR(6), m VARCHAR) RETURNS BOOLEAN AS
$$
DECLARE adresId integer;
DECLARE magId integer;
BEGIN
	adresId := (SELECT id_adres FROM adres a WHERE a.ulica=$2 AND a.nr_budynku=$3 AND a.kod_pocztowy=$4 AND a.miasto=$5);
	IF adresId IS NOT NULL THEN
		magId := (SELECT id_magazyn FROM magazyn WHERE id_adres=adresId);
	ELSE
		PERFORM dodajAdres($2,$3,$4,$5);
		adresId := (SELECT id_adres FROM adres a WHERE a.ulica=$2 AND a.nr_budynku=$3 AND a.kod_pocztowy=$4 AND a.miasto=$5);
		magId := (SELECT id_magazyn FROM magazyn WHERE id_adres=adresId);
	END IF;
	IF magId IS NOT NULL THEN
		RETURN FALSE;
	ELSE
		INSERT INTO magazyn VALUES(DEFAULT,$1,adresId);
		RETURN TRUE;
	END IF;	
END;
$$ 
  LANGUAGE 'plpgsql';
  
 
-- dodawanie nowego magazynu już z istniejącym w bazie adresem
 
CREATE OR REPLACE FUNCTION dodajMagazyn_Adres_istnieje(main BOOLEAN, id INTEGER) RETURNS BOOLEAN AS
$$
BEGIN
	IF NOT EXISTS (select * from magazyn WHERE id_adres=$2)
	THEN
		INSERT INTO magazyn VALUES( DEFAULT,$1,$2);
		RETURN TRUE;
	ELSE
		RETURN FALSE;
	END IF;
END;
$$ 
  LANGUAGE 'plpgsql';


-- dodanie nowego pracownika

CREATE OR REPLACE FUNCTION dodajPracownika(stanowisko VARCHAR, dataStartu DATE, id_magazynu INTEGER, imie VARCHAR, nazwisko VARCHAR, email VARCHAR, nr_tel VARCHAR, ulica VARCHAR, nr_bud VARCHAR, kod_pocztowy VARCHAR(6), miasto VARCHAR, login VARCHAR, haslo VARCHAR) RETURNS BOOLEAN AS
$$
DECLARE adresId integer;
DECLARE zatrudId integer;
DECLARE uprawId integer;
DECLARE pracowIst integer;
DECLARE id_pracow integer;
BEGIN
	pracowIst := (SELECT id_pracownik FROM pracownik  p WHERE p.imie=$4 AND p.nazwisko=$5 AND p.email=$6 AND p.telefon=$7);
	zatrudId := (SELECT id_zatrudnienie FROM zatrudnienie WHERE poczatek_zatrudnienia=$2);
	adresId := (SELECT id_adres FROM adres a WHERE a.ulica=$8 AND a.nr_budynku=$9 AND a.kod_pocztowy=$10 AND a.miasto=$11);
	uprawId := (SELECT id_uprawnienia FROM uprawnienia WHERE nazwa_stanowiska=$1);
	IF pracowIst IS NOT NULL THEN
		RETURN false;
	END IF;
	IF zatrudId IS NULL THEN
		INSERT INTO zatrudnienie VALUES(DEFAULT,$2,NULL);
		zatrudId := (SELECT id_zatrudnienie FROM zatrudnienie WHERE poczatek_zatrudnienia=$2);
	END IF;
	
	IF adresId IS NULL THEN
		PERFORM dodajAdres($8,$9,$10,$11);
		adresId := (SELECT id_adres FROM adres a WHERE a.ulica=$8 AND a.nr_budynku=$9 AND a.kod_pocztowy=$10 AND a.miasto=$11);
	END IF;
		
	IF zatrudId IS NOT NULL AND adresId IS NOT NULL THEN
		INSERT INTO pracownik VALUES(DEFAULT,zatrudId,uprawId,$4,$5,$6,$7,adresId,$3);
		id_pracow := (SELECT p.id_pracownik FROM pracownik p WHERE p.id_zatrudnienie=zatrudId AND p.id_uprawnienia=uprawId AND p.imie=$4 AND p.nazwisko=$5 AND p.email=$6 AND p.telefon=$7 AND p.id_adres=adresId);
		INSERT INTO weryfikacja VALUES(id_pracow, $12,md5($13));
		RETURN true;
	END IF;	
END;
$$ 
  LANGUAGE 'plpgsql';
  



-- zwolnienie magazyniera (przez dodanie mu daty zakończenia zatrudnienia)

CREATE OR REPLACE FUNCTION zwolnijMagazyniera(id_prac integer) RETURNS BOOLEAN AS
$$
DECLARE id_zatr integer;
DECLARE id_upraw integer;
DECLARE num integer;
DECLARE start_date DATE;
DECLARE end_date DATE;
BEGIN
    id_zatr := (SELECT z.id_zatrudnienie FROM zatrudnienie z, pracownik p WHERE p.id_pracownik=$1 AND p.id_zatrudnienie=z.id_zatrudnienie);
    id_upraw := (SELECT p.id_uprawnienia FROM pracownik p WHERE p.id_pracownik=$1);
    start_date := (SELECT z.poczatek_zatrudnienia FROM zatrudnienie z, pracownik p WHERE p.id_pracownik=$1 AND p.id_zatrudnienie=z.id_zatrudnienie);
    num := (SELECT COUNT(*) FROM pracownik p WHERE p.id_zatrudnienie=id_zatr);
	end_date := (SELECT z.koniec_zatrudnienia FROM zatrudnienie z WHERE z.id_zatrudnienie=id_zatr);
	IF end_date IS NOT NULL THEN
		RETURN FALSE;
	END IF;
    IF (num > 1) THEN
		end_date := (SELECT CURRENT_DATE);
        INSERT into zatrudnienie VALUES(DEFAULT,start_date,end_date);
        id_zatr := (SELECT z.id_zatrudnienie FROM zatrudnienie z WHERE z.poczatek_zatrudnienia=start_date AND z.koniec_zatrudnienia=end_date); 
        UPDATE pracownik SET id_zatrudnienie=id_zatr WHERE id_pracownik=$1;
        UPDATE pracownik SET id_magazyn=null WHERE id_pracownik=$1;
        RETURN TRUE;
    ELSIF ( num = 1 ) THEN
		end_date := (SELECT CURRENT_DATE);
		UPDATE zatrudnienie SET koniec_zatrudnienia=end_date WHERE poczatek_zatrudnienia=start_date AND id_zatrudnienie=id_zatr;
        UPDATE pracownik SET id_magazyn=null WHERE id_pracownik=$1;
		RETURN TRUE;
    ELSE
		RETURN FALSE;   
    END IF;
END;
$$ 
  LANGUAGE 'plpgsql';
  
  
  
-- edycja danych pracownika (imie,nazwisko,email,telefon,login))

CREATE OR REPLACE FUNCTION edytujDanePracownika(id_prac INTEGER, im VARCHAR, nazw VARCHAR, mail VARCHAR, tel VARCHAR(6), logn VARCHAR) RETURNS BOOLEAN AS
$$
DECLARE old_log VARCHAR;
DECLARE num INTEGER;
BEGIN
	old_log := (SELECT w.login FROM weryfikacja w WHERE w.id_pracownik=$1);
    num := (SELECT COUNT(*) FROM weryfikacja w WHERE  w.login=old_log);
    IF ( num = 1 ) THEN 
        UPDATE pracownik SET imie=$2, nazwisko=$3, email=$4, telefon=$5 WHERE id_pracownik=$1;
        UPDATE weryfikacja SET login=$6 WHERE id_pracownik=$1;
        RETURN TRUE;
    ELSE
		RETURN FALSE;   
    END IF;
END;
$$ 
  LANGUAGE 'plpgsql';
  

   
-- edycja adresu pracownika 

CREATE OR REPLACE FUNCTION edytujAdresPracownika(id_mag INTEGER, ul VARCHAR, nr VARCHAR, kod VARCHAR(6), miasto VARCHAR) RETURNS BOOLEAN AS
$$
DECLARE id_adr INTEGER;
DECLARE id_adr_stary INTEGER;
DECLARE n_pracownik INTEGER;
DECLARE n_magazyn INTEGER;
DECLARE n_klient INTEGER;
DECLARE n_wszystkie INTEGER;
BEGIN
	id_adr_stary := (SELECT p.id_adres FROM pracownik p WHERE p.id_magazyn=$1);
	n_pracownik := (SELECT COUNT(*) FROM pracownik p WHERE p.id_adres=id_adr_stary);
	n_magazyn := (SELECT COUNT(*) FROM magazyn m WHERE m.id_adres=id_adr_stary);
	n_klient := (SELECT COUNT(*) FROM klient k WHERE k.id_adres=id_adr_stary);
	n_wszystkie := n_pracownik + n_magazyn + n_klient;
    IF ( n_wszystkie = 1 ) THEN 
        UPDATE adres SET ulica=$2, nr_budynku=$3, kod_pocztowy=$4, miasto=$5 WHERE id_adres=id_adr_stary;
        RETURN TRUE;
    ELSIF( n_wszystkie > 1 OR n_wszystkie=0 ) THEN
		PERFORM dodajAdres($2,$3,$4,$5);
		id_adr := (SELECT a.id_adres FROM adres a WHERE a.ulica=$2 AND nr_budynku=$3 AND kod_pocztowy=$4 AND a.miasto=$5);
		UPDATE pracownik SET id_adres=id_adr WHERE id_magazyn=$1;
		RETURN TRUE;   
	ELSE
		RETURN FALSE;
    END IF;
END;
$$ 
  LANGUAGE 'plpgsql';
 
  
   
-- edycja adresu magazynu 

CREATE OR REPLACE FUNCTION edytujAdresMagazynu(id_mag INTEGER, ul VARCHAR, nr VARCHAR, kod VARCHAR(6), miasto VARCHAR) RETURNS BOOLEAN AS
$$
DECLARE id_adr INTEGER;
DECLARE id_adr_stary INTEGER;
DECLARE n_pracownik INTEGER;
DECLARE n_magazyn INTEGER;
DECLARE n_klient INTEGER;
DECLARE n_wszystkie INTEGER;
BEGIN
	id_adr_stary := (SELECT m.id_adres FROM magazyn m WHERE m.id_magazyn=$1);
	n_pracownik := (SELECT COUNT(*) FROM pracownik p WHERE p.id_adres=id_adr_stary);
	n_magazyn := (SELECT COUNT(*) FROM magazyn m WHERE m.id_adres=id_adr_stary);
	n_klient := (SELECT COUNT(*) FROM klient k WHERE k.id_adres=id_adr_stary);
	n_wszystkie := n_pracownik + n_magazyn + n_klient;
    IF ( n_wszystkie = 1 ) THEN 
        UPDATE adres SET ulica=$2, nr_budynku=$3, kod_pocztowy=$4, miasto=$5 WHERE id_adres=id_adr_stary;
        RETURN TRUE;
    ELSIF( n_wszystkie > 1 OR n_wszystkie=0 ) THEN
		PERFORM dodajAdres($2,$3,$4,$5);
		id_adr := (SELECT a.id_adres FROM adres a WHERE a.ulica=$2 AND nr_budynku=$3 AND kod_pocztowy=$4 AND a.miasto=$5);
		UPDATE Magazyn SET id_adres=id_adr WHERE id_magazyn=$1;
		RETURN TRUE;   
	ELSE
		RETURN FALSE;
    END IF;
END;
$$ 
  LANGUAGE 'plpgsql';
 
  


-- dodawanie nowej kategorii

CREATE OR REPLACE FUNCTION dodajKategorie (nazw VARCHAR ,opis VARCHAR) RETURNS BOOLEAN AS
$$
BEGIN
	IF NOT EXISTS (SELECT * FROM kategoria k WHERE k.nazwa=UPPER($1))
	THEN
		INSERT INTO kategoria VALUES(DEFAULT, UPPER($1), $2);
		RETURN TRUE;
	ELSE
		RETURN false;
	END IF;
END;
$$
  LANGUAGE 'plpgsql';
  

-- dodawanie nowego producenta

CREATE OR REPLACE FUNCTION dodajProducenta (nazw VARCHAR, mail VARCHAR, tel VARCHAR(9)) RETURNS BOOLEAN AS
$$
BEGIN
	IF NOT EXISTS (SELECT * FROM producent p WHERE p.nazwa=UPPER($1))
	THEN
		INSERT INTO producent VALUES(DEFAULT, UPPER($1), $2, $3);
		RETURN TRUE;
	ELSE
		RETURN false;
	END IF;
END;
$$
  LANGUAGE 'plpgsql';
  
  

-- dodawanie nowego produktu do katalogu

CREATE OR REPLACE FUNCTION dodajProdukt(id_kateg INTEGER, id_produc INTEGER, nazwa VARCHAR, cena NUMERIC(10,2) ) RETURNS BOOLEAN AS
$$
BEGIN
	IF NOT EXISTS (SELECT * FROM produkt p WHERE p.nazwa=UPPER($3))
	THEN
		INSERT INTO produkt VALUES(DEFAULT, $1, $2, UPPER($3), $4);
		RETURN TRUE;
	ELSE
		RETURN false;
	END IF;
END;
$$
  LANGUAGE 'plpgsql';
  
  
  
-- dodawanie nowych egzemplarzy produktu do stanu magazynu

CREATE OR REPLACE FUNCTION dodajAsortyment(id_mag INTEGER, id_produk INTEGER, szt INTEGER ) RETURNS BOOLEAN AS
$$
DECLARE ilosc_s INTEGER;
DECLARE ilosc_n INTEGER;
BEGIN
	IF NOT EXISTS (SELECT * FROM magazyn_stan WHERE id_magazyn=$1 AND id_produkt=$2)
	THEN
		RETURN FALSE;
	ELSE
		ilosc_s := (SELECT ilosc FROM magazyn_stan WHERE id_magazyn=$1 AND id_produkt=$2);
		ilosc_n := ilosc_s + $3;
		UPDATE magazyn_stan SET ilosc=ilosc_n WHERE id_produkt=$2 AND id_magazyn=$1;
		RETURN TRUE;
	END IF;
END;
$$
  LANGUAGE 'plpgsql';
  
  
  
 -- inicjacja nowego produktu do stanu magazynu z początkową iloscią = 0
 
CREATE OR REPLACE FUNCTION inicjacja_produktu() 
 RETURNS trigger AS
$$
DECLARE id_pr INTEGER;
DECLARE temprow RECORD;
BEGIN
	id_pr := new.id_produkt;
	FOR temprow IN 
		SELECT m.id_magazyn FROM magazyn m ORDER BY m.id_magazyn
	LOOP
		IF NOT EXISTS (SELECT * FROM magazyn_stan ms WHERE ms.id_magazyn=temprow.id_magazyn AND ms.id_produkt=id_pr)
		THEN	
			INSERT INTO magazyn_stan VALUES(id_pr, temprow.id_magazyn,0);
		END IF;
	END LOOP;
	RETURN new;
END;
$$
  LANGUAGE 'plpgsql';

CREATE TRIGGER inicjuj_produkt_magazyn_stan AFTER INSERT ON produkt FOR EACH ROW EXECUTE PROCEDURE inicjacja_produktu();
  
  
  
  

-- dodanie nowego klienta

CREATE OR REPLACE FUNCTION dodajKlienta (nazwa VARCHAR, nip VARCHAR(10), email VARCHAR, tel VARCHAR(9), ulica VARCHAR, nr_bud VARCHAR, kod VARCHAR, miasto VARCHAR) RETURNS BOOLEAN AS
$$
DECLARE adresId integer;
DECLARE kliId integer;
BEGIN
	PERFORM dodajAdres($5,$6,$7,$8);
	adresId := (SELECT id_adres FROM adres a WHERE a.ulica=$5 AND a.nr_budynku=$6 AND a.kod_pocztowy=$7 AND a.miasto=$8);
	kliId := (SELECT id_firma FROM klient WHERE id_adres=adresId);
	IF kliId IS NULL THEN
		INSERT INTO klient VALUES(DEFAULT,$1,$2,$3,$4,adresId);
	END IF;	
	RETURN TRUE;
END;
$$ 
  LANGUAGE 'plpgsql';
  
  

-- dodanie nowego zamówienia (inicjacja zamówienia, początkowo niepowiązanego z żadnymi produktami oraz z kwotą sumaryczną równą 0.00)

CREATE OR REPLACE FUNCTION noweZamowienie (nazwa VARCHAR, nip VARCHAR(10), email VARCHAR, tel VARCHAR(9), ulica VARCHAR, nr_bud VARCHAR, kod VARCHAR, miasto VARCHAR) RETURNS INTEGER AS
$$
DECLARE adresId INTEGER;
DECLARE kliId INTEGER;
DECLARE today DATE;
DECLARE id_now_zam INTEGER;
BEGIN
	today := CURRENT_DATE;
	adresId := (SELECT id_adres FROM adres a WHERE a.ulica=$5 AND a.nr_budynku=$6 AND a.kod_pocztowy=$7 AND a.miasto=$8);
	kliId := (SELECT k.id_firma FROM klient k WHERE k.nazwa=$1 AND k.nip=$2 AND k.email=$3 AND k.telefon=$4);
	IF kliId IS NULL THEN
		PERFORM dodajKlienta($1,$2,$3,$4,$5,$6,$7,$8);
		kliId := (SELECT k.id_firma FROM klient k WHERE k.nazwa=$1 AND k.nip=$2 AND k.email=$3 AND k.telefon=$4);
	END IF;	
	IF adresId IS NULL THEN 
		PERFORM dodajAdres($5,$6,$7,$8);
		adresId := (SELECT id_adres FROM adres a WHERE a.ulica=$5 AND a.nr_budynku=$6 AND a.kod_pocztowy=$7 AND a.miasto=$8);
	END IF;
	IF adresId IS NOT NULL AND kliId IS NOT NULL THEN
		id_now_zam := (SELECT nextval('zamowienie_id_zamowienie_seq'));
		INSERT INTO zamowienie VALUES(id_now_zam,'złożone',kliId,today,0.00);
		INSERT INTO platnosc VALUES(DEFAULT,id_now_zam,'oczekująca',null);
		RETURN id_now_zam;
	ELSE
		RETURN NULL;
	END IF;
END;
$$ 
  LANGUAGE 'plpgsql';
 
  
-- SELECT noweZamowienie ('Meble Bodzio','9876543211','biuro@favi.pl','675234817','Asnyka','6','32-020','Wieliczka');
  
  
-- funkcja dodająca produkty do zamownienia
CREATE OR REPLACE FUNCTION dodajDoZamowienia (id_zam INTEGER, id_prod INTEGER, id_mag INTEGER, il INTEGER) RETURNS BOOLEAN AS
$$
DECLARE il_s INTEGER;
DECLARE il_n INTEGER;
DECLARE cena_p NUMERIC;
DECLARE kwota_z NUMERIC;
DECLARE suma NUMERIC;
BEGIN
	IF ($4=0) THEN
		RETURN FALSE;
	END IF;
	il_s := (SELECT ms.ilosc FROM magazyn_stan ms WHERE ms.id_magazyn=$3 AND ms.id_produkt=$2);
	cena_p := (SELECT p.cena FROM produkt p WHERE p.id_produkt=$2);
	suma := (cena_p * $4);
	kwota_z := (SELECT kwota FROM zamowienie WHERE id_zamowienie=$1);
	kwota_z := kwota_z + suma;
	il_n := (il_s - $4);
	IF(il_n > 0) THEN
		UPDATE magazyn_stan SET ilosc=il_n WHERE id_magazyn=$3 AND id_produkt=$2;
		INSERT INTO zamowienie_szczegoly VALUES($1,$2,$3,$4);
		UPDATE zamowienie SET kwota=kwota_z WHERE id_zamowienie=$1;
		RETURN TRUE;
	ELSE
		RETURN FALSE;
	END IF;
END;
$$ 
  LANGUAGE 'plpgsql';
  
  
-- funkcja zmieniająca status płatności z 'oczekująca' na 'zrealizowana' lub 'nieudana', w przypadku nieudanej wraca produkty z zamówienia do magazynu
CREATE OR REPLACE FUNCTION zaktualizujPlatnosc (id_plat INTEGER, id_zam INTEGER, id_kli INTEGER, nowy_status VARCHAR(12)) RETURNS BOOLEAN AS
$$
DECLARE obecny_status VARCHAR;
DECLARE temprow RECORD;
DECLARE ilosc_s INTEGER;
BEGIN
	obecny_status := (SELECT status FROM platnosc WHERE id_zamowienie=$2 AND id_platnosc=$1);
	IF (obecny_status='oczekująca') THEN
		IF (nowy_status='zrealizowana') THEN
			UPDATE platnosc SET status='zrealizowana', timestamp_wykonania=NOW() WHERE id_platnosc=$1 AND id_zamowienie=$2;
			UPDATE zamowienie SET  status='zakończone' WHERE id_zamowienie=$2;
			RETURN TRUE;
		ELSIF (nowy_status='nieudana') THEN
			UPDATE platnosc SET status='nieudana', timestamp_wykonania=NOW() WHERE id_platnosc=$1 AND id_zamowienie=$2;
			UPDATE zamowienie SET  status='anulowane' WHERE id_zamowienie=$2;
				FOR temprow IN 
					SELECT zs.id_magazyn, zs.id_produkt, zs.ilosc FROM zamowienie_szczegoly zs WHERE zs.id_zamowienie=$2
				LOOP
					ilosc_s := (SELECT ms.ilosc FROM magazyn_stan ms WHERE ms.id_magazyn=temprow.id_magazyn AND ms.id_produkt=temprow.id_produkt);
					UPDATE magazyn_stan SET ilosc=(ilosc_s+temprow.ilosc) WHERE id_magazyn=temprow.id_magazyn AND id_produkt=temprow.id_produkt;
				END LOOP;
			RETURN TRUE;
		ELSE
			RETURN FALSE;
		END IF;
	ELSE
		RETURN FALSE;
	END IF;
END;
$$ 
  LANGUAGE 'plpgsql';
  