<?php
class Categories extends QuarkORM
{
  public static $table      = 'categories';
  public static $connection = 'default';

  const FOR_MAIN_MENU = 1;
  const FOR_SECONDARY_MENU = 2;

  public static function query()
  {
    return new QuarkORMQueryBuilder(__CLASS__);
  }

  /**
   * Devuelve un array de objetos Categories que pertenecen a un role especifico,
   * opcionalmente se pueden filtrar por "menu principal" o "menu secundario"
   * 
   * @param int $role_id ID del role
   * @param int $menu_filter Filtrado de menu:
   *                         Categories::FOR_MAIN_MENU
   *                         Categories::FOR_SECONDARY_MENU
   * @return array(Categories)
   */
  public static function getCategoriesForRole($role_id, $menu_filter = null)
  {
    $sql = 'SELECT C.* FROM categories AS C, categories_permissions AS CP
      WHERE CP.categories_id = C.id AND CP.roles_id = :role_id';

    if ($menu_filter == self::FOR_MAIN_MENU) {
      $sql .= ' AND on_main_menu = 1';
    } elseif ($menu_filter == self::FOR_SECONDARY_MENU) {
      $sql .= ' AND on_secondary_menu = 1';
    }

    $sql .= ' ORDER BY C.name ASC';

    $St = QuarkORMEngine::query($sql, array(':role_id' => $role_id), 'default');
    $St->setFetchMode(PDO::FETCH_CLASS, 'Categories');
    return $St->fetchAll();
  }
  
  public static function create()
  {
    return new self();
  }
}
