<?php
    class MySQL {

        private static $pdo = null;

        public static function Connection () {
            
            if (self::$pdo == null) {

                self::$pdo = new PDO ('mysql:host=localhost;dbname=Search_PHP', 'root', '');

                return self::$pdo;
            }
            else {
                return self::$pdo;
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Search with PHP</title>
        <link rel="stylesheet" href="./style.css">
    </head>
    <body>
        <header>
            <a href=""><h1>Search</h1></a>

            <form method="post">
                <input  type="text"   name="term" placeholder="Search..." required>
                <button type="submit" name="go">Go!</button>
            </form>
            <?php
                if (isset($_POST['term']) && isset($_POST['go'])) {

                    $term = strip_tags($_POST['term']);

                    $sql = MySQL::Connection()->prepare("SELECT * FROM Users WHERE UserUsername LIKE ?");
                    $sql->execute(["%$term%"]);

                    $results = $sql->fetchAll(PDO::FETCH_OBJ);

                    if (count($results) === 0) {
                        $results = "Not Found";
                    }
                }
            ?>
        </header>
        <main>

            <?php
                if (isset($results)) {

                    if (is_string($results) === true) {
                        echo "<p class='results'>" . $results . "</p>";
                    }
                    else if (is_array($results) === true) {

                        if (count($results) === 1) {
                            echo "<p class='results'>Found: " . count($results) . " result.<p>";
                        }
                        else {
                            echo "<p class='results'>Found: " . count($results) . " results.<p>";
                        }

                        foreach ($results as $result) {
                            echo "<div class='card'>";
                                echo "<p class='username'>" . $result->UserUsername . "</p>";
                            echo "</div>";
                        }
                    }
                    else {
                        echo "<p class='results'>ERROR</p>";
                    }
                }
                else {

                    $sql = MySQL::Connection()->prepare("SELECT * FROM Users");
                    $sql->execute();
    
                    $users = $sql->fetchALL(PDO::FETCH_OBJ);
    
                    foreach ($users as $user) {
                        echo "<div class='card'>";
                            echo "<p class='id'>"         . $user->UserId       . "</p>";
                            echo "<p class='name'>| "     . $user->UserName     . "</p>";
                            echo "<p class='username'>| " . $user->UserUsername . "</p>";
                        echo "</div>";
                    }
                }
            ?>

        </main>
        <footer>
            <p>By <a href="https://github.com/lucas-catto/" target="_blank">Lucas Catto</a></p>
        </footer>
    </body>
</html>
