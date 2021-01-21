<?php

require_once 'modules/generique/modele_generique.php';

class ModelePanier extends ModeleGenerique
{

    public function creationPanier($idUtil)
    {
        //Vérification s'il y a déjà un panier
        try {
            $selectPreparee = Connexion::$bdd->prepare('SELECT * FROM paniers WHERE idUtilisateur=:idUtil;');
            $selectPreparee->execute(array(':idUtil' => $idUtil));
            $resultat = $selectPreparee->fetchAll();

            if ($selectPreparee->rowCount() < 1) {
                //Si le panier n'existe pas
                $selectPrepareeInsert = Connexion::$bdd->prepare('INSERT INTO `paniers` 
                    (`dateCreation`, `total`, `idUtilisateur`) 
                    VALUES (NOW(), \'0\', :idUtil)');
                $selectPrepareeInsert->execute(array(':idUtil' => $idUtil));

                //Assignation du nouveau panier à la variable de session du panier :
                $selectPrepareeSelect = Connexion::$bdd->prepare('SELECT * FROM paniers
                    WHERE idUtilisateur=:idUtil');
                $selectPrepareeSelect->execute(array(':idUtil' => $idUtil));
                $resultat = $selectPrepareeSelect->fetchAll();
                $_SESSION['panier'] = $resultat[0]['idPanier'];
            } else {
                //Il existe déjà un panier
                $_SESSION['panier'] = $resultat[0]['idPanier'];
            }
        } catch (PDOException $e) {
        }
    }

    public function recuperationPanier($idUtil, $idPanier)
    {
        $selectPreparee = Connexion::$bdd->prepare('SELECT 
                    T0.total AS TotalPanier,
                    T1.idProduit,
                    T1.qteProduits,
                    T2.nomProduit,
                    T2.prixHT
                    FROM paniers T0 
                    INNER JOIN produitsPanier T1 ON T0.idPanier = T1.idPanier
                    INNER JOIN produits T2 ON T1.idProduit = T2.idProduit
                    WHERE T0.idUtilisateur=:idUtil AND T0.idPanier=:idPanier');
        $reponse = array(':idUtil' => $idUtil, ':idPanier' => $idPanier);
        $selectPreparee->execute($reponse);
        $resultat = $selectPreparee->fetchAll();
        return $resultat;
    }

    function supprimerProduit($idProduit)
    {
        $selectPreparee = Connexion::$bdd->prepare('DELETE FROM produitsPanier WHERE idProduit=:idProduit');
        $reponse = array(':idProduit' => $idProduit);
        $req = $selectPreparee->execute($reponse);
        return $req;
    }

    function getIDPanier($idUtil){
        try {
            $req = Connexion::$bdd->prepare('SELECT idPanier from paniers where idUtilisateur=:idUtil');
            $req->execute(array(':idUtil' => $idUtil));
            $reponse = $req ->fetchAll();
        } catch (PDOException $e) {
        }
        return $reponse[0]['idPanier'];
    }

    function ajouterProduitPanier($idProduit, $idPanier){
        try {
            //Voir si le panier comprend déjà ce produit
            $selectPreparee = Connexion::$bdd->prepare('SELECT * FROM produitsPanier 
                WHERE idProduit=:idProduit AND idPanier=:idPanier;');
            $selectPreparee->execute(array(':idProduit' => $idProduit, ':idPanier' => $idPanier));

            if ($selectPreparee->rowCount()>0){
                //Quand le panier comprend déjà ce produit, alors on ajoute 1 à la quantité.
                $modifPreparee = Connexion::$bdd->prepare('UPDATE produitsPanier SET qteProduits=qteProduits+1
                    WHERE idProduit=:idProduit AND idPanier=:idPanier');
                $modifPreparee->execute(array(':idProduit' => $idProduit, ':idPanier' => $idPanier));
            }else{
                //TODO : revoir gestion quantité
                $insertPreparee = Connexion::$bdd->prepare('INSERT INTO produitsPanier 
                (qteProduits, idProduit, idPanier) VALUES (1, :idProduit, :idPanier)');
                $insertPreparee->execute(array(':idProduit' => $idProduit, ':idPanier' => $idPanier));
                //TODO : Peut-être faire une vérification que le produit a bien été ajouté
            }
        } catch (PDOException $e) {
        }
    }

    static function avoirNBProduitsPanier($idPanier){
        try {
            //TODO
            $selectPreparee = Connexion::$bdd->prepare('SELECT SUM(qteProduits) AS SommePanier 
                FROM produitsPanier where idPanier=:idPanier');
            $selectPreparee->execute(array(':idPanier' => $idPanier));
            $reponse = $selectPreparee ->fetchAll();
            return $reponse[0]['SommePanier'];
        } catch (PDOException $e) {
        }
    }

}
