<?php
class Categories extends QuarkORM
{
  public static $table      = 'categories';
  public static $connection = 'default';

  const FOR_MAIN_MENU      = 1;
  const FOR_SECONDARY_MENU = 2;

  public function __construct()
  {
    parent::__construct();
    $this->populate();
  }

  /**
   * Guarda los datos de la categoria en DB
   * 
   * @return bool TRUE en caso de exito, de lo contrario FALSE
   */
  public function save()
  {
    $return = parent::save();
    $this->populate();
    return $return;
  }

  /**
   * Crea nuevas propieades al objeto con información adicional a lo que se
   * encuentra en DB
   */
  private function populate()
  {
    if ($this->is_new == false) {
      $QuarkURL = new QuarkURL();
      $this->url = $QuarkURL->getURL('categories/'.$this->id);
    }
  }

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
  public static function getAvailableCategoriesForUser(
    User $User,
    $menu_filter = null
  ) {
    // Crear el SQL general
    $sql = 'SELECT C.*, CP.* FROM categories C
      LEFT JOIN categories_permissions CP ON (CP.categories_id = C.id)
      WHERE (CP.roles_id IS NULL OR CP.roles_id = :role_id)';

    // Agregar filtro de menu
    if ($menu_filter == self::FOR_MAIN_MENU) {
      $sql .= ' AND on_main_menu = 1';
    } elseif ($menu_filter == self::FOR_SECONDARY_MENU) {
      $sql .= ' AND on_secondary_menu = 1';
    }

    // Agregar el modo de orden
    $sql .= ' ORDER BY C.order ASC';

    // Ejecutar SQL y obtener el PDOStatement
    $St = QuarkORMEngine::query(
      $sql,
      array(':role_id' => $User->roles_id),
      'default'
    );

    /* Configurar el PDOStatement para hacer un fetch de objetos de
     * clase 'Categories' y devolver el fetch */
    $St->setFetchMode(PDO::FETCH_CLASS, 'Categories');
    return $St->fetchAll();
  }

  /**
   * Devuelve el número de posts que estan dentro de esta categoria, los posts
   * son filtrados por el faction del usuario firmado
   * 
   * @param User $User Usuario firmado, para filtrar los posts de su faction
   * @return int
   */
  public function getPostsCount(User $User)
  {
    return $this->countChilds('Posts')
      ->where(array('published' => 1))
      ->where("faction='*' OR faction=:user_faction", array(
        ':user_faction' => $User->faction,
      ))
      ->exec();
  }

  /**
   * Devuelve un objeto de clase Posts que representa el último post publicado
   * dentro de esta categoría, filtrado por el faction del usuario
   * 
   * @param User $User Usuario firmado, para filtrar por su faction
   * @return Posts
   */
  public function getLastPost(User $User)
  {
    return Posts::query()->findOne()
      ->where(array('categories_id' => $this->id, 'published' => 1))
      ->where("faction='*' OR faction=:user_faction", array(
        ':user_faction' => $User->faction,
      ))
      ->order('creation_date', 'desc')
      ->exec();
  }
  
  public static function create()
  {
    return new self();
  }
}
