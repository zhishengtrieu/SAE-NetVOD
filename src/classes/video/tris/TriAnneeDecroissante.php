<?php

namespace netvod\video\tris;

use netvod\db\ConnectionFactory;
use netvod\render\CatalogueRenderer;
use netvod\render\Renderer;
use netvod\video\catalogue\Catalogue;
use netvod\video\serie\Serie;
use \PDO;

class TriAnneeDecroissante implements Tri{

    // cette fonction permettera de trier les series du catalogue de la plus recente a la plus ancienne
    public function trier(): string
    {
        $res = "";
        $catalogue = new Catalogue();
        //requete sql permettant de recuperer l'id et le titre des series et de les trier par ordre d'annee decroissante
        $query = "select id,titre from serie order by annee desc";
        $st = ConnectionFactory::$db->prepare($query);
        $st->execute();
        //pour chaque reponse à la requete sql(trier dans l'ordre decroissant) on creer une serie et on l'ajoute au catalogue
        foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $id = $row['id'];
            $titre = $row['titre'];
            $serie = new Serie(intval($id),$titre);
            $catalogue->ajouterSerie($serie);
        }
        //on affiche le catalogue et on le retourne
        $res .= (new CatalogueRenderer($catalogue))->render(Renderer::COMPACT);
        return $res;
    }
}