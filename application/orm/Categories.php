<?php
class Categories extends QuarkORM
{
  public static $table      = 'categories';
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
