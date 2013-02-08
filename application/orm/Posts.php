<?php
class Posts extends QuarkORM
{
  public static $table      = 'posts';
  public static $connection = 'default';

  public $User;
  public $url;

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
   * Return the comments number filtered by user faction
   */
  public function getCommentsCount($faction)
  {
    return $this->countChilds('Comments')
      ->where(array('faction' => $faction))
      ->exec();
  }

  public function getComments($faction)
  {
    return $this->getChilds('Comments')
      ->where(array('faction' => $faction))
      ->order('id')
      ->exec();
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
