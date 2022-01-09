<?php

class deletepresentation implements request
{
    private $request;

    public function __construct($request = null)
    {
        $this->request = $request;

        if (!is_numeric($request['presentation_id']))
            throw  new RuntimeException("Is not a number!");
    }

    public function invoke()
    {
        session_start();

        $sql = new sql();
        $sql->begin_transaction();
        try {
            /*
             * Получение файлов по презентации
             */
            $stmt = $sql->prepare('select file_path, files.id
from onlinepresentations.files
         inner join slides s on files.slide_id = s.id
         inner join presentation p on s.presentation_id = p.id
where presentation_id = ?
  and author_id = ?;');
            $stmt->bind_param('ii', $this->request['presentation_id'], $_SESSION['id']);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

            /*
             * Удаление файлов
             */
            foreach ($result as $item) {
                unlink(realpath($_SERVER['DOCUMENT_ROOT'] . "/" . $item['file_path']));
            }
            $stmt->close();

            /*
             * Очистка бд от ссылок на файлы
             */
            $delfiles = $sql->prepare('delete from onlinepresentations.files where files.id in (select files.id
from onlinepresentations.files
         inner join slides s on files.slide_id = s.id
         inner join presentation p on s.presentation_id = p.id
where presentation_id = ?
  and author_id = ?);');
            $delfiles->bind_param('ii', $this->request['presentation_id'], $_SESSION['id']);
            $delfiles->execute();
            $delfiles->close();

            /*
             * Удаление слайдов
             */
            $delslides = $sql->prepare('delete from slides where slides.id in (select slides.id
from slides
         inner JOIN presentation p on slides.presentation_id = p.id
         inner join users u on p.author_id = u.id
        where presentation_id = ? and u.id = ?);');
            $delslides->bind_param('ii', $this->request['presentation_id'], $_SESSION['id']);
            $delslides->execute();
            $delslides->close();

            /*
             * Удаление презентаций
             */
            $delpresentation = $sql->prepare('delete from presentation where id = ? and author_id = ?');
            $delpresentation->bind_param('ii', $this->request['presentation_id'], $_SESSION['id']);
            $delpresentation->execute();
            $delpresentation->close();

            $sql->commit();
            print json_encode(['done' => $_SESSION['id']]);
        } catch (mysqli_sql_exception $exception) {
            $sql->rollback();
            throw new mysqli_sql_exception($exception);
        }
    }
}