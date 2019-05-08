
-- -------- REQUETE 1A ---------


SELECT au.nomAuteur, au.prenomAuteur, au.idAuteur, count(oe.noOeuvre) as nbrOeuvre
FROM OEUVRE oe
INNER JOIN AUTEUR au On au.idAuteur=oe.idAuteur
GROUP BY au.nomAuteur,au.prenomAuteur,au.idAuteur
ORDER BY au.nomAuteur;

-- -------- REQUETE 1B --------

SELECT au.nomAuteur, au.prenomAuteur, au.idAuteur, count(oe.noOeuvre) as nbrOeuvre
FROM OEUVRE oe
RIGHT JOIN AUTEUR au On au.idAuteur=oe.idAuteur
GROUP BY au.nomAuteur,au.prenomAuteur,au.idAuteur
ORDER BY au.nomAuteur;

-- -------- requete 2A --------

SELECT AUTEUR.nomAuteur, OEUVRE.titre,OEUVRE.noOeuvre
, COALESCE(OEUVRE.dateParution,OEUVRE.dateParution,'')as dateParution
, COUNT(E1.noExemplaire) AS nbExemplaire
FROM OEUVRE
INNER JOIN AUTEUR ON AUTEUR.idAuteur = OEUVRE.idAuteur
inner JOIN EXEMPLAIRE E1 ON E1.noOeuvre = OEUVRE.noOeuvre
GROUP BY OEUVRE.noOeuvre
ORDER BY AUTEUR.nomAuteur ASC, OEUVRE.titre ASC;



-- -------- requete 2B --------

SELECT AUTEUR.nomAuteur, OEUVRE.titre,OEUVRE.noOeuvre
, COALESCE(OEUVRE.dateParution,OEUVRE.dateParution,'')as dateParution
, COUNT(E1.noExemplaire) AS nbExemplaire
FROM OEUVRE
INNER JOIN AUTEUR ON AUTEUR.idAuteur = OEUVRE.idAuteur
LEFT JOIN EXEMPLAIRE E1 ON E1.noOeuvre = OEUVRE.noOeuvre
GROUP BY OEUVRE.noOeuvre
ORDER BY AUTEUR.nomAuteur ASC, OEUVRE.titre ASC;
 


-- -------- REQUETE 2C --------

SELECT au.nomAuteur
FROM AUTEUR au
LEFT JOIN OEUVRE oe ON  au.idAuteur = oe.idAuteur
WHERE noOeuvre is null;



 
-- -------- REQUETE 2D --------

SELECT AUTEUR.nomAuteur, OEUVRE.titre,OEUVRE.noOeuvre
, COALESCE(OEUVRE.dateParution,OEUVRE.dateParution,'')as dateParution
, COUNT(E1.noExemplaire) AS nbExemplaire, count(E2.noExemplaire) AS nombreDispo
FROM OEUVRE
JOIN AUTEUR ON AUTEUR.idAuteur = OEUVRE.idAuteur
LEFT JOIN EXEMPLAIRE E1 ON E1.noOeuvre = OEUVRE.noOeuvre
LEFT JOIN EXEMPLAIRE E2 ON E2.noExemplaire = E1.noExemplaire AND E2.noExemplaire NOT IN (SELECT
EMPRUNT.noExemplaire FROM EMPRUNT WHERE EMPRUNT.dateRendu IS NULL)
GROUP BY OEUVRE.noOeuvre
ORDER BY AUTEUR.nomAuteur ASC, OEUVRE.titre ASC;


-- ------ REQUETE 3A ----------

SELECT ad.nomAdherent, ad.adresse, ad.datePaiement, ad.idAdherent,
 count(empr.idAdherent) AS nbrEmprunt
 , DATE_ADD(datePaiement, INTERVAL 1 YEAR) as datePaiementFutur
 FROM ADHERENT AS ad
 LEFT JOIN EMPRUNT empr ON empr.idAdherent=ad.idAdherent
 AND empr.dateRendu IS NULL
 GROUP BY ad.idAdherent
 ORDER BY ad.nomAdherent;
 


-- ------- REQUETE 3B ------

SELECT ad.nomAdherent, ad.adresse, ad.datePaiement, ad.idAdherent,
 count(empr.idAdherent) AS nbrEmprunt
 , IF(CURRENT_DATE()>DATE_ADD(datePaiement, INTERVAL 1 YEAR),1,0) as retard
 , IF(CURRENT_DATE()>DATE_ADD(datePaiement, INTERVAL 11 MONTH),1,0) as retardProche
 , DATE_ADD(datePaiement, INTERVAL 1 YEAR) as datePaiementFutur
 FROM ADHERENT AS ad
 LEFT JOIN EMPRUNT empr ON empr.idAdherent=ad.idAdherent
 AND empr.dateRendu IS NULL
 GROUP BY ad.idAdherent
 ORDER BY ad.nomAdherent;

-- ------ REQUETE 4A --------

SELECT AUTEUR.nomAuteur, OEUVRE.titre, count(EXEMPLAIRE.noExemplaire) as nbrExemplaire
FROM AUTEUR
JOIN OEUVRE ON AUTEUR.idAuteur = OEUVRE.idAuteur
JOIN EXEMPLAIRE ON EXEMPLAIRE.noOeuvre = OEUVRE.noOeuvre
GROUP BY AUTEUR.nomAuteur, OEUVRE.titre;

SELECT count(EXEMPLAIRE.noExemplaire) as nbrExemplaire
FROM EXEMPLAIRE;

-- ------- REQUETE 4B -----
CREATE VIEW v_bibliol AS
        SELECT AUTEUR.nomAuteur, OEUVRE.titre, OEUVRE.noOeuvre,count(EXEMPLAIRE.noExemplaire) as nbrExemplaire,"N_EMP" as ETAT_EMPRUNT
        FROM AUTEUR
        JOIN OEUVRE ON AUTEUR.idAuteur = OEUVRE.idAuteur
        JOIN EXEMPLAIRE ON EXEMPLAIRE.noOeuvre = OEUVRE.noOeuvre
        WHERE EXEMPLAIRE.noExemplaire NOT IN ( SELECT distinct noExemplaire FROM EMPRUNT)
        GROUP BY AUTEUR.nomAuteur,OEUVRE.titre,OEUVRE.noOeuvre;
UNION
        SELECT AUTEUR.nomAuteur, OEUVRE.titre, OEUVRE.noOeuvre,count(EXEMPLAIRE.noExemplaire) as nbrExemplaire,"EMP" as ETAT_EMPRUNT
        FROM AUTEUR
        JOIN OEUVRE ON AUTEUR.idAuteur = OEUVRE.idAuteur
        JOIN EXEMPLAIRE ON EXEMPLAIRE.noOeuvre = OEUVRE.noOeuvre
        WHERE EXEMPLAIRE.noExemplaire IN ( SELECT distinct noExemplaire FROM EMPRUNT where dateRendu is null)
        GROUP BY AUTEUR.nomAuteur,OEUVRE.titre,OEUVRE.noOeuvre;
UNION
        SELECT AUTEUR.nomAuteur, OEUVRE.titre, OEUVRE.noOeuvre, COUNT(EXEMPLAIRE.noExemplaire) AS nbrExemplaire, "REND" as ETAT_EMPRUNT
        FROM AUTEUR
        JOIN OEUVRE ON AUTEUR.idAuteur = OEUVRE.idAuteur
        JOIN EXEMPLAIRE ON EXEMPLAIRE.noOeuvre = OEUVRE.noOeuvre
        WHERE EXEMPLAIRE.noExemplaire IN ( SELECT DISTINCT noExemplaire FROM EMPRUNT WHERE
                                              EMPRUNT.noExemplaire NOT IN ( SELECT DISTINCT EMPRUNT.noExemplaire
                                                                             FROM EMPRUNT WHERE dateRendu is NULL)
                                                )
        GROUP BY AUTEUR.nomAuteur, OEUVRE.titre, OEUVRE.noOeuvre;

SELECT nomAuteur, titre,noOeuvre,nbrExemplaire,ETAT_EMPRUNT FROM v_bibliol;

SELECT nomAuteur,titre, noOeuvre,SUM(nbrExemplaire) as nombre
,SUM(IF(ETAT_EMPRUNT='REND' OR ETAT_EMPRUNT='N_EMP',nbrExemplaire, 0 )) as nombreDispo
FROM v_bibliol
GROUP BY nomAuteur,titre,noOeuvre;


-- ------------- REQUETE 4C ----------

SELECT noExemplaire,etat,dateAchat,prix,noOeuvre
FROM EXEMPLAIRE
WHERE noOeuvre
ORDER BY noExemplaire ASC;

SELECT AUTEUR.nomAuteur,OEUVRE.titre, OEUVRE.noOeuvre,OEUVRE.dateParution,COUNT(E1.noExemplaire) as nbrExemplaire,
COUNT(E2.noExemplaire) as nombreDispo
FROM OEUVRE
JOIN AUTEUR ON AUTEUR.idAuteur = OEUVRE.idAuteur
LEFT JOIN EXEMPLAIRE E1 ON E1.noOeuvre = OEUVRE.noOeuvre
LEFT JOIN EXEMPLAIRE E2 ON E2.noExemplaire = E1.noExemplaire
      AND E2.noExemplaire NOT IN (SELECT EMPRUNT.noExemplaire FROM EMPRUNT WHERE EMPRUNT.dateRendu IS NULL)
WHERE OEUVRE.noOeuvre
GROUP BY AUTEUR.nomAuteur, OEUVRE.titre, OEUVRE.noOeuvre, OEUVRE.dateParution;

-- ------------ REQUETE 6B --------

SELECT ADHERENT.idAdherent, EXEMPLAIRE.noExemplaire, OEUVRE.titre, nomAdherent,dateEmprunt,dateRendu,DATEDIFF(curdate(),dateEmprunt) as RETARD
FROM ADHERENT
JOIN EMPRUNT ON EMPRUNT.idAdherent= ADHERENT.idAdherent
JOIN EXEMPLAIRE ON EMPRUNT.noExemplaire = EXEMPLAIRE.noExemplaire
JOIN OEUVRE ON EXEMPLAIRE.noOeuvre = OEUVRE.noOeuvre
ORDER BY dateEmprunt DESC;


-- ------- REQUETE 6C ------

SELECT ADHERENT.idAdherent, EXEMPLAIRE.noExemplaire,OEUVRE.titre,nomAdherent,dateEmprunt,dateRendu
, DATEDIFF(curdate(),dateEmprunt) as nbJoursEMprunt
,DATEDIFF(curdate(),DATE_ADD(dateEmprunt, INTERVAL 90 DAY)) as RETARD
,DATE_ADD(dateEmprunt, INTERVAL 90 DAY) as dateRenduTheorique
,IF(CURRENT_DATE()>DATE_add(dateEmprunt, INTERVAL 90 DAY),1,0) as flagRetard
,IF(CURRENT_DATE()>DATE_add(dateEmprunt, INTERVAL 120 DAY),1,0) as flagPenalite
,IF( ((DATEDIFF(curdate(),DATE_add(dateEmprunt, INTERVAL 120 DAY)) * 0.5)<25),
     (DATEDIFF(curdate(),DATE_ADD(dateEmprunt, INTERVAL 120 DAY)) * 0.5),25) as dette
FROM ADHERENT
JOIN EMPRUNT ON EMPRUNT.idAdherent=ADHERENT.idAdherent
JOIN EXEMPLAIRE ON EMPRUNT.noExemplaire = EXEMPLAIRE.noExemplaire
JOIN OEUVRE ON EXEMPLAIRE.noOeuvre = OEUVRE.noOeuvre
WHERE dateRendu is NULL HAVING flagRetard=1
ORDER BY dateEmprunt DESC;