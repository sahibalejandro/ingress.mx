<?php
class CategoriesController extends IngressMXController
{
  /**
   * Display all categories
   */
  public function index()
  {

  }

  /**
   * Display posts in the specified category
   * 
   * @param int $categories_id Category ID
   */
  public function view($categories_id, $page = 1)
  {
    // Get category ORM
    $Category = Categories::query()->findByPk($categories_id);

    /* If Category not found then drops the 404 error, otherwise render
     * the categories list view */
    if ($Category == false) {
      $this->__quarkNotFound();
    } else {

      $posts_per_page = 5;
      $page = (int)$page;
      if ($page < 1) {
        $page = 1;
      }

      /* Get published posts in this category, filtered by user faction, and ordered
       * by stick and creation date date */
      $posts = $Category->getChilds('Posts')
        ->where(array('published' => 1))
        ->where("faction='*' OR faction='".$this->User->faction."'")
        ->order('stick', 'desc')
        ->order('id', 'desc')
        ->limit($posts_per_page * ($page - 1), $posts_per_page)
        ->exec();

      $this->addViewVars(array(
        'Category' => $Category,
        'posts'    => &$posts,
        'page'     => &$page,
      ))->renderView();
    }
  }
}
