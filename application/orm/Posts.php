<?php
class Posts extends QuarkORM
{
  public static $table      = 'posts';
  public static $connection = 'default';

  public $User;
  public $url;
  private $is_secure;

  public function __construct()
  {
    parent::__construct();
    
    if (!$this->is_new) {
      $this->populate();
    }
  }

  private function populate()
  {
    if (!isset($this->User)) {
      $this->User = $this->getParent('User');
    }

    // Define si el post actual es seguro o no
    $this->is_secure = ($this->faction != '*' || $this->secure_comments == 'S');

    // Generate post URL
    if (!isset($this->url)) {
      $Url = new QuarkURL();
      $this->url = $Url->getURL('post/'.$this->id);
    }
  }

  /**
   * Get the content resume of the post, limited to $max_words words.
   * 
   * @param int $max_words
   * @return string
   */
  public function getResume($max_words)
  {
    // Generate resume
    $no_tags_content = trim(strip_tags($this->content));
    $words = str_word_count($no_tags_content, 1);
    
    if (count($words) <= $max_words) {
      $resume = $no_tags_content;
    } else {
      $resume = implode(' ', array_slice($words, 0, $max_words)).'...';
    }

    return $resume;
  }

  protected function validate()
  {
    return true;
  }

  public function save()
  {
    if (parent::save() == false) {
      return false;
    } else {
      $this->populate();
      return true;
    }
  }

  /**
   * Devuelve el número de comentarios en el post
   * si el post es "comment_secure" solo se cuentan los comments del faction del
   * usuario firmado
   * 
   * @param User $User Usuario firmado
   * @return int
   */
  public function getCommentsCount(User $User)
  {
    $QueryBuilder = $this->countChilds('Comments');
    if ($this->is_secure) {
      $QueryBuilder->where(array('faction' => $User->faction));
    }
    return $QueryBuilder->exec();
  }

  /**
   * Devuelve un array de objeto de clase Comments, que representan los comentarios
   * del post actual, opcionalmente con un $offset y $limit para paginación.
   * Si el post es "comment_secure" solo se traen los comments del faction del
   * usuario firmado
   * 
   * @param User $User Usuario firmado
   * @return int
   */
  public function getComments(User $User, $offset = 0, $limit = 10)
  {
    $QueryBuilder = $this->getChilds('Comments');

    if ($this->is_secure) {
      $QueryBuilder->where(array('faction' => $User->faction));
    }
    return $QueryBuilder->order('id')->exec();
  }

  public function isSecure()
  {
    return $this->is_secure;
  }

  public static function query()
  {
    return new QuarkORMQueryBuilder(__CLASS__);
  }

  public static function create()
  {
    return new self();
  }
}
