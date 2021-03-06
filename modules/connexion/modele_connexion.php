<?php

require_once 'config/connexion.php';

class ModeleConnexion extends Connexion{

    function connexion($pseudo){
        try{
            $selectPreparee =Connexion::$bdd->prepare('SELECT idUtilisateur,login,hashMdp,idTypeUtilisateur FROM utilisateurs 
            WHERE login=:pseudo');
            $reponse = array(':pseudo' => $pseudo);
            $selectPreparee->execute($reponse);
            return $selectPreparee->fetchAll();
        } catch (PDOException $e) {
        }
    }

    function inscription($login, $nom, $prenom, $mdp, $eFacturation, $eLivraison, $tel, $dateNaiss)
    {
        try{
            $selectPrepareeInsert = Connexion::$bdd->prepare('
            INSERT INTO utilisateurs 
                (login, nom, prenom, hashMdp, emailFacturation, emailLivraison,
                 telephone, dateNaissance, idTypeUtilisateur)
            VALUES (:login, :nom, :prenom, :mdp, :eFacturation, :eLivraison, :tel, :dateNaiss, "3")');
            $reponse = array(':login' => $login, ':nom' => $nom, ':prenom' => $prenom, ':mdp' => password_hash($mdp, PASSWORD_DEFAULT),
                ':eFacturation' => $eFacturation, ':eLivraison' => $eLivraison, ':tel' => $tel, ':dateNaiss' => $dateNaiss);
            $selectPrepareeInsert->execute($reponse);
        } catch (PDOException $e) {
        }
    }

    function verifInscription($login){
        //Vérification de l'inscription :
        try{
            $selectPrepareeVerif = Connexion::$bdd->prepare('
            SELECT idUtilisateur,login,idTypeUtilisateur FROM utilisateurs WHERE login =:login');
            $selectPrepareeVerif->execute(array(':login' => $login));
            return $selectPrepareeVerif->fetchAll();
        } catch (PDOException $e) {
        }
    }

    public function loginExiste($newPseudo){
        try {
            $req = Connexion::$bdd->prepare('SELECT login FROM utilisateurs WHERE login = ?');
            $req->execute(array($newPseudo));
            $nb = $req->rowCount();
        } catch (PDOException $e) {
        }
        return $nb;
    }

}
