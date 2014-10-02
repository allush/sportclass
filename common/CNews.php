<?php

include 'CRole.php';

class CNews
{

    protected $id = false;
    protected $owner_id = false;
    protected $from_id = false;
    protected $signer_id = false;
    protected $date = false;
    protected $text = false;

    public function __construct($id)
    {
        if (!$id)
            return;

        $result = mysql_query('SELECT * FROM `news` WHERE `id`=' . $id);
        if (!$result)
            return;
        $row = mysql_fetch_array($result);
        if (!$row)
            return;


        $this->id = $id;
        $this->owner_id = $row['owner_id'];
        $this->from_id = $row['from_id'];
        $this->signer_id = $row['signer_id'];
        $this->date = $row['date'];
        $this->text = $row['text'];

    }

    public function __get($property)
    {
        switch ($property) {
            case 'id':
                return $this->id;
            case 'owner_id':
                return $this->owner_id;
            case 'from_id':
                return $this->from_id;
            case 'signer_id':
                return $this->signer_id;
            case 'text':
                return $this->text;
            case 'date':
                return $this->date;
        }
    }

    public function __set($property, $value)
    {
        switch ($property) {

            case 'id':
                $this->id = $value;
                break;
            case 'owner_id':
                $this->owner_id = $value;
                break;
            case 'from_id':
                $this->from_id = $value;
                break;
            case 'signer_id':
                $this->signer_id = $value;
                break;
            case 'text':
                $this->text = $value;
                break;
            case 'date':
                $this->date = $value;
                break;
        }
    }

    public function save()
    {
//        $result = mysql_query('INSERT INTO `news` WHERE `id`=' . $id);
    }
}

?>
