<?php
class Comments extends QuarkORM
{
  /**
   * Table name related to this object
   * @var string
   */
  public static $table = 'comments';

  /**
   * Connection name for this object
   * @var string
   */
  public static $connection = 'default';

  public $User;
  
  public function __construct()
  {
    parent::__construct();
    if ($this->is_new == false) {
      $this->populate(); 
    }
  }

  /**
   * Populate object with extra properties, like $this->User
   */
  private function populate()
  {
    if (!isset($this->User)) {
      $this->User = $this->getParent('User');
    }
  }
  
  /**
   * To validate your data before save to database
   *
   * @return boolean
   */
  protected function validate()
  {
    // Sanitize comment data
    settype($this->posts_id, 'int');
    settype($this->users_id, 'int');
    $this->faction = trim($this->faction);
    $this->content = trim($this->content);

    // Validate data
    if ($this->posts_id == 0 || $this->users_id == 0) {
      return false;
    }

    if ($this->faction == '' || $this->content == '') {
      return false;
    }

    return true;
  }

  public function save()
  {
    if (parent::save() == false){
      return false;
    } else {
      $this->populate();
      return true;
    }
  }

  /**
   * Return a QuarkORMQueryBuilder instance prepared to run queries on the table
   * related to this object.
   *
   * @return QuarkORMQueryBuilder
   */
  public static function query()
  {
    return new QuarkORMQueryBuilder(__CLASS__);
  }

  public static function create()
  {
    return new self();
  }
}
