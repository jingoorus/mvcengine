<?php
final class Extendmysqli
{
    public $curent_limitless_count = null;

    private $link = null;

    public function __construct($host, $user, $password, $db)
    {
        $this->link = new \mysqli($host, $user, $password, $db);
    }

    public function query(string $query)
    {
        $query_result = $this->link->query($query);

        if (!$this->link->errno) {

            if ($query_result instanceof \mysqli_result) {
                /**
                 * В большинстве случаев нам нужен массив с данными
                 * после чего мы высвобождаем память от результатов запроса
                 */
                $data = $query_result->fetch_all(MYSQLI_ASSOC);

                $result = new ExtendmysqliResult;

                $result->num_rows = $query_result->num_rows;

                $result->row = isset($data[0]) ? $data[0] : [];

                $result->rows = $data;

                $query_result->free();

                return $result;

            } else {
                /**
                 * Или же это будет true || false или ошибка
                 */
                return $query_result;
            }

        } else {
            /**
             * или ошибка
             */
            throw new Exception($this->link->error);
        }
    }

    public function getLastId()
    {
        return $this->link->insert_id;
    }

    public function escape($value)
    {
        return $this->link->real_escape_string($value);
    }

    public function limitlessSelect($query)
    {
        $query_result = $this->link->query($query);

        if (!$this->link->errno) {

            if ($query_result instanceof \mysqli_result) {

                $this->curent_limitless_count = $query_result->num_rows;

                while ($data = $query_result->fetch_assoc()) {

                    yield $data;
                }

                $query_result->close();
            }

        } else {

            throw new Exception($this->error);
        }
    }
}

final class ExtendmysqliResult
{
    public $num_rows = null;

    public $row = [];

    public $rows = [];
}