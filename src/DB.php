<?php

namespace MiniUpload;

class DB
{
    private $db_host;
    private $db_user;
    private $db_pass;
    private $db_name;
    private $vk;
    private $link;

    public function __construct($vk = null) {
        $this->db_host = Config::$db_host;
        $this->db_user = Config::$db_user;
        $this->db_pass = Config::$db_pass;
        $this->db_name = Config::$db_name;
        $this->vk = $vk;
        $this->link = $this->connect();
    }

    //Подключение к БД
    public function connect() {
        $this->link = mysqli_connect($this->db_host, $this->db_user, $this->db_pass, $this->db_name)
        or die("Ошибка " . mysqli_error($this->link));
        mysqli_set_charset($this->link, "utf8");
        return $this->link;
    }

    // Занесение пользователя в БД
    public function userReg($user_id) {
        $query = mysqli_query($this->link, "INSERT INTO users (user_id) VALUES ($user_id)");
        $this->incStat('users');
    }

    public function checkReg($user_id) {
        $query = mysqli_query($this->link, "SELECT * FROM users WHERE user_id = $user_id");
        $result = mysqli_fetch_array($query);
        if (empty($result['user_id'])) {
            $this->userReg($user_id);
        }
    }

    public function incStat($field) {
        $query = mysqli_query($this->link, "UPDATE statistics SET $field = $field + 1");
    }

    public function incGift($user_id) {
        $query = mysqli_query($this->link, "UPDATE users SET gift = 1 WHERE user_id = $user_id AND gift = 0");
    }

    public function checkGift($user_id) {
        $query = mysqli_query($this->link, "SELECT * FROM users WHERE user_id = $user_id");
        $result = mysqli_fetch_array($query);
        if ($result['gift'] == 0) {
            $query = mysqli_query($this->link, "UPDATE users SET gift = 1 WHERE user_id = $user_id");
            return 1;
        }
        return 0;
    }

    public function addComment($text, $user_id) {
        $query = mysqli_query($this->link, "INSERT INTO comments (comment, user_id) VALUES ('$text', $user_id)");
        $this->incStat('comment');
    }

    public function getComments() {
        $query = mysqli_query($this->link, "SELECT * FROM comments ORDER BY visible");
        return $query;
    }

    public function setVisible($id) {
        $query = mysqli_query($this->link, "UPDATE comments SET visible = 1 WHERE id = $id");
    }

    public function setFlag($id) {
        $query = mysqli_query($this->link, "UPDATE comments SET flag = 0");
        $query = mysqli_query($this->link, "UPDATE comments SET flag = 1 WHERE id = $id");
    }

    public function getFlag() {
        $query = mysqli_query($this->link, "SELECT * FROM comments WHERE flag = 1");
        $result = mysqli_fetch_array($query);
        return $result;
    }
}