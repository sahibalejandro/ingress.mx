<?php
class Posts extends QuarkORM
{
  public static $table      = 'posts';
  public static $connection = 'default';

  private $resume;
  private $resume_max_words;

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

  public function getResume($max_words = 150)
  {
    // Generate resume
    if ($this->resume == null || $this->resume_max_words != $max_words) {
      
      $no_tags_content = trim(strip_tags($this->content));
      $words = str_word_count($no_tags_content, 1);
      
      if (count($words) <= $this->resume_max_words) {
        $this->resume = $no_tags_content;
      } else {
        $this->resume = implode(' ', array_slice($words, 0, $max_words)).'...';
      }

      /* Save max words to re-generate the resume if more or less words is required
       * in future calls */
      $this->resume_max_words = $max_words;
    }
    return $this->resume;
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
