<?php
namespace App\ControleFinanceiro\Model\User;

require_once '../Db/Database.php';
use App\ControleFinanceiro\Db\Database;
use \PDO;

class  User
{
    public static function findByEmail($email)
    {

        $conn = Database::getConnection();

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");

        $stmt->bindValue(':email', $email);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}