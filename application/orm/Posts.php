<?php
class Posts extends QuarkORM
{
  public static $table      = 'posts';
  public static $connection = 'default';

  public $User;
  public $url;

  public function __construct()
  {
    if (!$this->is_new) {
      $this->User = $this->getParent('User');

      // Generate post URL
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

  public function getCommentsCount()
  {
    return $this->countChilds('Posts')->exec();
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
