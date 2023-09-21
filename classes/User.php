<?php
require_once __DIR__ . "/DataValidation.php";
class User extends DataValidation
{
    private $input;
    function __construct(private Database $DB)
    {
        $this->input = json_decode(file_get_contents('php://input'));
        if ($this->input === null) $this->input = json_decode('{}');
    }

    // Login User
    public function login()
    {
        static::validate(['email', 'password'], $this->input);

        $email = trim($this->input->email);
        $password = trim($this->input->password);

        static::isEmail($email);
        static::minLength($password, 'password', 4);

        $sql = "SELECT * FROM `users` WHERE `email`=:email";
        $result = $this->DB->run_prepare($sql, [
            ":email" => [$email, PDO::PARAM_STR]
        ]);

        if ($result->rowCount() === 0) :
            static::sendJson(404, 'User not found! (Email is not registered)');
        endif;

        $user = $result->fetch(PDO::FETCH_OBJ);

        if (!password_verify($password, $user->password)) :
            static::sendJson(401, 'Incorrect Password!');
        endif;

        return $user->id;
    }

    // Register a New User
    public function register()
    {
        static::validate(['name', 'email', 'password'], $this->input);

        $name = trim($this->input->name);
        $email = trim($this->input->email);
        $password = trim($this->input->password);

        static::isEmail($email);
        static::minLength($name, 'name', 3);
        static::minLength($password, 'password', 4);

        $sql = "SELECT `email` FROM `users` WHERE `email`=:email";
        $result = $this->DB->run_prepare($sql, [
            ':email' => [$email, PDO::PARAM_STR]
        ]);

        if ($result->rowCount()) :
            static::sendJson(409, 'This E-mail already in use!');
        endif;

        $sql = "INSERT INTO `users`(`name`,`email`,`password`) VALUES(:name,:email,:password)";

        $this->DB->run_prepare($sql, [
            ':name' => [htmlspecialchars(strip_tags($name)), PDO::PARAM_STR],
            ':email' => [$email, PDO::PARAM_STR],
            ':password' => [password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR]
        ]);

        static::sendJson(201, 'You have been successfully registered.');
    }
}
