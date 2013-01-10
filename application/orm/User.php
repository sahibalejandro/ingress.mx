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
  
  public $faction_name;
  public $screenshot;
  public $Role;
  public $url;

  public function __construct()
  {
    parent::__construct();
    if (!$this->is_new) {
      $this->faction_name = $this->faction == 'R' ? 'RESISTANCE' : 'ENLIGTHENED';
      $this->screenshot   = $this->user.'.jpg';
      $this->Role         = $this->getParent('Roles');

      // Create user url
      $Url = new QuarkURL();
      $this->url = $Url->getURL('profile/'.$this->id);

      if ($this->states_id != null) {
        $this->State = States::query()->findByPk($this->states_id);
        $this->City  = Cities::query()->findByPk(array(
          'id'        => $this->cities_id,
          'states_id' => $this->states_id,
        ));
      }
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
