<?php

class XModel extends Model {
    // Utiliser pour le signup form
    public static function getNationalities() {
        $stmt = Model::connect()->query("SELECT country FROM countries");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $datas[] = $row['country'];
        }
        return $datas;
    }

    public static function getUsername($id) {
        $stmt = Model::connect()->prepare("SELECT username FROM users WHERE id=?");
        $stmt->execute([$id]);

        $datas = $stmt->fetch(PDO::FETCH_ASSOC);
        // Pour transformer le tableau qui contient l'username en string
        $username = implode($datas);

        return $username;
    }
}