<?php
class Cities extends QuarkORM
{
  public static $table      = 'cities';
  public static $connection = 'default';
  public static function query()
  {
    return new QuarkORMQueryBuilder(__CLASS__);
  }
  public static function create()
  {
    return new self();
  }
}
