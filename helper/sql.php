<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/entities/User.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php";

/*
 * Основной класс взаимодействия с БД
 */

class sql extends mysqli
{
    private $log;


    public function __construct()
    {
        /*
         * Наследование от MySQLi класса для удобства вызова коннектора
         */
        Logger::configure($_SERVER["DOCUMENT_ROOT"] . '/helper/config.xml');
        $this->log = Logger::getLogger(__CLASS__);
        parent::__construct(getenv("DB_HOST") ?: "localhost",
            getenv("DB_USERNAME") ?: "root",
            getenv("DB_PASSWORD") ?: "",
            "onlinepresentations");

        $this->set_charset('utf8');
    }

    /*
     * Подготовленные запосы для устранения SQL инъекций
     * Удобство вывода ошибок на уровень выше
     */

    /*
     * Валидация пользователя по логину и паролю
     */
    public function validateUser(User $user): bool
    {
        $mysqli_stmt = $this->prepare("select count(id) from users where email=? and password=?");
        $mysqli_stmt->bind_param('ss', $user->email, $user->password);
        $mysqli_stmt->execute();
        if ($mysqli_stmt->errno != 0) throw new RuntimeException($mysqli_stmt->error);
        $result = $mysqli_stmt->get_result()->fetch_array(MYSQLI_NUM)[0];
        $mysqli_stmt->close();
        $this->log->info("validateUser | email=" . $user->email . ";password=" . $user->password . "| result =" . $result);
        return $result == 1;
    }

    /*
     * Валидация пользователя по Id в сессии
     */
    public function validateId(string $id): bool
    {
        $mysqli_stmt = $this->prepare("select count(id) from users where id=?");
        $mysqli_stmt->bind_param('i', $id);
        $mysqli_stmt->execute();
        if ($mysqli_stmt->errno != 0) throw new RuntimeException($mysqli_stmt->error);
        $result = $mysqli_stmt->get_result()->fetch_array(MYSQLI_NUM)[0];
        $mysqli_stmt->close();
        $this->log->info("validateId | id=" . $id . "| result =" . $result);
        return $result == 1;
    }

    /*
     * Получение Id пользователя по логину и паролю
     */
    public function getUserId(User $user): int
    {
        $mysqli_stmt = $this->prepare("select id from users where email=? and password=?");
        $mysqli_stmt->bind_param('ss', $user->email, $user->password);
        $mysqli_stmt->execute();
        if ($mysqli_stmt->errno != 0) throw new RuntimeException($mysqli_stmt->error);
        $result = $mysqli_stmt->get_result()->fetch_array(MYSQLI_NUM)[0];
        $mysqli_stmt->close();
        $this->log->info("getUserId | user=" . $user . "| id=" . $result);
        return $result;
    }

    public function getUserById(string $id): User
    {
        $mysqli_stmt = $this->prepare("select * from users where id=?");
        $mysqli_stmt->bind_param('i', $id);
        $mysqli_stmt->execute();
        if ($mysqli_stmt->errno != 0) throw new RuntimeException($mysqli_stmt->error);
        $result = $mysqli_stmt->get_result()->fetch_array(MYSQLI_ASSOC);
        $mysqli_stmt->close();
        $user = new User($result['name'], $result['username'], $result['password'], $result['description'], $result['email'], $result['validated']);
        $user->id = $result['id'];
        $this->log->info("getUserById | id=" . $id . "| Result :" . $user);
        return $user;
    }

    /*
     * Валидация пользователя по validated
     */
    public function checkValidated(int $id): bool
    {
        $mysqli_stmt = $this->prepare("select validated from users where id=?");
        $mysqli_stmt->bind_param('i', $id);
        $mysqli_stmt->execute();
        if ($mysqli_stmt->errno != 0) throw new RuntimeException($mysqli_stmt->error);
        $result = $mysqli_stmt->get_result()->fetch_array(MYSQLI_NUM)[0];
        $mysqli_stmt->close();
        $this->log->info("checkValidated | id=" . $id . "| Result :" . $result);
        return $result == 1;
    }

    /*
     * Установить переменную validated в true
     */
    public function setValidated(int $id, int $validated)
    {
        $stmt = $this->prepare("UPDATE users SET validated=? WHERE id=?");
        $stmt->bind_param("ii", $validated, $id);
        $stmt->execute();
        if ($stmt->errno != 0) throw new RuntimeException($stmt->error, $stmt->errno);
        $result = $stmt->insert_id;
        $stmt->close();
        $this->log->info("setValidated | id=" . $id . "| Result :" . $validated);
        return $result;
    }

    /*
     * Добавление пользователя
     */
    public function registrationUser(User $user)
    {
        $stmt = $this->prepare("insert into users (email, password, validated, name, username, description) value (?,?,?,?,?,?)");
        $stmt->bind_param("ssisss", $user->email, $user->password, $user->validated, $user->name, $user->username, $user->description);
        $stmt->execute();
        if ($stmt->errno != 0) throw new RuntimeException($stmt->error, $stmt->errno);
        $result = $stmt->insert_id;
        $stmt->close();
        $this->log->info("registrationUser | id=" . $user . "| Result :" . $result);
        return $result;
    }

    /*
     * Создать презентацию от имени  пользователя
     */
    public function createPresentation($userid)
    {
        $stmt = $this->prepare("insert into presentation (author_id) values (?)");
        $stmt->bind_param("i", $userid);
        $stmt->execute();
        if ($stmt->errno != 0) throw new RuntimeException($stmt->error, $stmt->errno);
        $result = $stmt->insert_id;
        $stmt->close();
        $this->log->info("createPresentation | id=" . $userid . "| Result :" . $result);
        return $result;
    }

    /*
     * Добавление слайдов с аргументами номер презентации,
     * переданный текст
     */
    public function createSlide($presentation_id, $title, $content)
    {
        $stmt = $this->prepare("insert into slides (presentation_id, title, content) VALUES (?,?,?)");
        $stmt->bind_param("iss", $presentation_id, $title, $content);
        $stmt->execute();
        if ($stmt->errno != 0) throw new RuntimeException($stmt->error, $stmt->errno);
        $result = $stmt->insert_id;
        $stmt->close();
        $this->log->info("createSlide | presentation_id=" . $presentation_id . ";title=" . $title . ";content=" . $title . "| Result :" . $result);
        return $result;
    }

    /*
     * Добавление слайдов в базу данных от номера слайда
     */
    public function createFiles($slide_id, $path)
    {
        $stmt = $this->prepare("insert into files (slide_id, file_path) VALUES (?,?)");
        $stmt->bind_param("is", $slide_id, $path);
        $stmt->execute();
        if ($stmt->errno != 0) throw new RuntimeException($stmt->error, $stmt->errno);
        $result = $stmt->insert_id;
        $stmt->close();
        $this->log->info("createFiles | slide_id=" . $slide_id . ";file_path=" . $path . "| Result :" . $result);
        return $result;
    }

    /*
     * Получение презентаций пользователя
     */
    public function getUsersPresentations($userid)
    {
        $mysqli_stmt = $this->prepare("select * from presentation where author_id = ?");
        $mysqli_stmt->bind_param('i', $userid);
        $mysqli_stmt->execute();
        if ($mysqli_stmt->errno != 0) throw new RuntimeException($mysqli_stmt->error);
        $result = $mysqli_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $mysqli_stmt->close();
        return $result;
    }

    /*
     * Получить все презентации пользователей
     */
    public function getAllPresentations()
    {
        $mysqli_stmt = $this->prepare("select presentation.id, u.username from presentation join users u on u.id = presentation.author_id");
        $mysqli_stmt->execute();
        if ($mysqli_stmt->errno != 0) throw new RuntimeException($mysqli_stmt->error);
        $result = $mysqli_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $mysqli_stmt->close();
        return $result;
    }

    /*
     * Получение всех слайдов презентации
     */
    public function getSlides(int $presentationId)
    {
        $mysqli_stmt = $this->prepare("select * from slides where presentation_id = ?");
        $mysqli_stmt->bind_param('i', $presentationId);
        $mysqli_stmt->execute();
        if ($mysqli_stmt->errno != 0) throw new RuntimeException($mysqli_stmt->error);
        $result = $mysqli_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $mysqli_stmt->close();
        return $result;
    }

    /*
     * Получение медиафайлов презентации
     */
    public function getFiles(int $slideId, $presentationId)
    {
        $mysqli_stmt = $this->prepare("select files.id, files.file_path, s.id, p.id
from onlinepresentations.files
         left JOIN slides s on s.id = files.slide_id
         left JOIN presentation p on p.id = s.presentation_id
where s.id = ? and p.id =?");
        $mysqli_stmt->bind_param('ii', $slideId, $presentationId);
        $mysqli_stmt->execute();
        if ($mysqli_stmt->errno != 0) throw new RuntimeException($mysqli_stmt->error);
        $result = $mysqli_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $mysqli_stmt->close();
        return $result;
    }

    /*
     * Обновить слайд по полученной информации
     */
    public function updateSlide($content, $title, $presentation_id, $id)
    {
        $stmt = $this->prepare("update slides
set content = ?,
    title=?
where presentation_id = ?
  and id = ?");
        $stmt->bind_param("ssii", $content, $title, $presentation_id, $id);
        $stmt->execute();
        if ($stmt->errno != 0) throw new RuntimeException($stmt->error, $stmt->errno);
        $stmt->close();
    }

    /*
     * Поиск презентации по ключевым словам
     */
    public function searchPresentationByKeyWord($keyWord)
    {
        $query = "select p.id , u.name
from slides
         join presentation p on p.id = slides.presentation_id
         join users u on u.id = p.author_id
where content like '%{$keyWord}%'
   or title like '%{$keyWord}%'";
        $result = $this->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /*
     * Поиск презентации по имени автора
     */
    public function searchPresentationByAuthorName($authorName)
    {
        $query = "select p.id , users.name
from users
         join presentation p on users.id = p.author_id
where name like '%{$authorName}%'";
        $result = $this->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /*
     * Поиск презентации по имени презентации
     */
    public function searchPresentationByPresentationId($id)
    {
        $query = "select p.id , users.name
from users
         join presentation p on users.id = p.author_id
where p.id = {$id}";
        $result = $this->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}