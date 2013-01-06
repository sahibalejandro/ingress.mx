<?php
class User extends QuarkORM
{
  /**
   * Table name related to this object
   * @var string
   */
  public static $table = 'users';

  /**
   * Connection name for this object
   * @var string
   */
  public static $connection = 'default';
  
  public function __construct()
  {
    parent::__construct();
    if (!$this->is_new) {
      $this->fraction_name = $this->fraction == 'R' ? 'RESISTANCE' : 'ENLIGTHENED';
      $this->screenshot    = $this->user.'.jpg';
    }
  }

  /**
   * To validate your data before save to database
   *
   * @return boolean
   */
  protected function validate()
  {
    /**
     * TODO:
     * Validate object properties and return true on success or false on failure
     */
    return true;
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
}
