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
   * Find a user record by his Google ID and return an User instance if found
   * or return false if not found.
   * 
   * @return User|false
   */
  public static function getByGoogleID($google_id)
  {
    return User::query()
      ->findOne()
      ->where(array('google_id' => $google_id))
      ->exec();
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
