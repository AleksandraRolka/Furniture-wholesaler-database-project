

---------------------------------------------------------------------------- minimum init ----------------------------------------------------------------------------

SET search_path TO hurtownia;

INSERT INTO hurtownia.Uprawnienia VALUES(DEFAULT,'kierownik','Zatrudnia pracowników, nadaje im uprawnienia do zarządzaniem konkretnym magazynem.');
INSERT INTO hurtownia.Uprawnienia VALUES(DEFAULT,'magazynier','Obsługuje przydzielony magazyn - dodaje nowe produkty, dodaje nowych klientów i realizuje ich zamowienia.');




---------------------------------------------------------------------- wypełnienie dla przykładu ----------------------------------------------------------------------

SELECT dodajMagazyn(true,'Balicka','32','30-149','Kraków');
SELECT dodajMagazyn(false,'Krakowska','87','32-050','Skawina');
SELECT dodajMagazyn(false,'Lwowska','54','33-300','Nowy Sącz');
SELECT dodajMagazyn(false,'Wielopole','64','33-300','Nowy Sącz');


-- dodanie do bazy pierwszego kierownika (będzie mógł zatrudnić (dodać do bazy) resztę pracowników: kierowników, magazynierów)

SELECT dodajPracownika('kierownik','2020-12-01',null,'Aleksandra','Rolka','arolka@hurt.com','731552440','Wroclawska','168','30-149','Kraków','aleksandra980198','rolka090183');

SELECT dodajPracownika('magazynier','2020-12-12',1,'Marcin','Wierzbicki','mwierzbicki@op.pl','712687321','Armii Krajowej','8','30-149','Kraków','marcinw9098','wierzba9');
SELECT dodajPracownika('magazynier','2021-01-14',2,'Krzysztof','Krzak','kkrzak@gmail.com','772978234','Jamnicka','67','32-987','Kraków','kkrzak145','k123krzak4');
SELECT dodajPracownika('magazynier','2021-01-14',3,'Joanna','Mazur','jmazur@interia.com','547823674','Heleny','112','32-987','Kraków','jmazur234','mazurekj212'); 



SELECT dodajproducenta('DEBARO','debaromeble@gmail.com', '535656597');
SELECT dodajproducenta('MEBIN','mebin@mebin.pl', '446821195');
SELECT dodajproducenta('MEBLE VOX','export@vox.pl', '618151700');
SELECT dodajproducenta('MINKO','minko@minko.co', '507507935');
SELECT dodajproducenta('RAGABA','kolor@ragaba.eu', '601998698');
SELECT dodajproducenta('RETROWOOD','biuro@retrowood.pl', '783783700');
SELECT dodajproducenta('WOODIES','biuro@retrowood.pl', '783783700');
SELECT dodajproducenta('Comforteo ','comforteo@meble-marzenie.pll', '609221092');


SELECT dodajkategorie('KRZESŁA', 'Krzesła do jadalni, kuchni, barowe, stołki.');
SELECT dodajkategorie('STOŁY', 'Stoły kuchenne, obiadowe, rozkładane, stoliki kawowe, stoły barowe.');
SELECT dodajkategorie('FOTELE', 'Fotele salonowe, biurowe, obrotowe, bujane, gamingowe.');
SELECT dodajkategorie('SOFY', 'Sofy, kanapy, rozkładane, modułowe, narożniki.');



SELECT dodajprodukt(1,1,'Krzesło EPC DSW',350);
SELECT dodajprodukt(1,3,'Krzesło Panton',399);
SELECT dodajprodukt(2,6,'Stolik kawowy dębowy',699.99);
SELECT dodajprodukt(3,2,'Fotel bujany',499.99);
SELECT dodajprodukt(3,8,'Fotel Gamingowy Gamvis Czarny',789);
SELECT dodajprodukt(4,2,'Sofa welurowa Slender',1299);
SELECT dodajprodukt(4,5,'Sofa modułowaCubic Aris',6999);



SELECT dodajAsortyment(1,1,80);
SELECT dodajAsortyment(1,2,75);
SELECT dodajAsortyment(1,3,40);
SELECT dodajAsortyment(1,4,28);
SELECT dodajAsortyment(1,5,56);
SELECT dodajAsortyment(1,7,42);



SELECT noweZamowienie('Agata Meble', '6340197476', 'biuro@agatameble.pl', '328887070', 'Roździeńskiego', '93', '40-203', 'Katowice');
SELECT dodajDoZamowienia(1,1,1,12);
SELECT dodajDoZamowienia(1,3,1,4);

SELECT noweZamowienie('Abra', '6842168097', 'biuro@abra-meble.pl', '660627644', 'Krakowska', '199', '30-400', 'Krosno');
SELECT dodajDoZamowienia(2,2,1,6);
SELECT dodajDoZamowienia(2,4,1,1);
SELECT dodajDoZamowienia(2,5,1,1);

SELECT noweZamowienie('Meble Bodzio', '9876543211', 'biuro@favi.pl', '675234817', 'Asnyka', '6', '32-020', 'Wieliczka');
SELECT dodajDoZamowienia(3,3,1,2);
SELECT dodajDoZamowienia(3,7,1,1);

