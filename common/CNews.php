<?php

include 'CRole.php';

class CNews
{

    public $id = false;
    public $owner_id = false;
    public $from_id = false;
    public $signer_id = false;
    public $date = false;
    public $text = false;

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
        mysql_query("INSERT  INTO `news` (`id`, `owner_id`, `from_id`, `signer_id`, `text`, `date`)
         VALUES ('$this->id','$this->owner_id','$this->from_id','$this->signer_id','$this->text','$this->date');");
    }

    public function update()
    {
        mysql_query("UPDATE `news` SET
        `owner_id`='.$this->owner_id,
        `from_id`='.$this->from_id,
        `signer_id=`.$this->signer_id,
        `text=`.$this->text,
        `data=`.$this->data WHERE `id`=" . $this->id);
    }

    public static function findByPk($id)
    {
        if (!(int)$id) return null;

        $result = mysql_query('SELECT * FROM `news` WHERE `id`=' . $id);
        if (!$result) return null;

        $row = mysql_fetch_array($result);
        if (!$row) return null;

        $news = new CNews();
        $news->id = $id;
        $news->owner_id = $row['owner_id'];
        $news->from_id = $row['from_id'];
        $news->signer_id = $row['signer_id'];
        $news->date = $row['date'];
        $news->text = $row['text'];

        return $news;
    }
}

?>
