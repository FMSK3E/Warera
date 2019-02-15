<?php

class MarketController {

    public static function productMarket() {
        $products = MarketModel::productMarket();
        // Pour récupérer l'username du vendeur (c'est plus pratique que de l'inscrire dans la db product_market)
        for ($i=0; $i < count($products); $i++)
            $products[$i]['username_seller'] = XModel::getUsername($products[$i]['id_seller']);
        require 'views/productMarket.php';
    }
}