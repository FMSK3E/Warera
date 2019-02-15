<?php

class MarketModel extends Model {

    public static function productMarket() {
        $country = $_SESSION['nationality'];

        $stmt = Model::connect()->prepare("SELECT * FROM product_market");
        $stmt->execute([$country]);

        $datas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $datas;
    }
}