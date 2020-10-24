<?php


abstract class NewsManager
{
    /**
     * ajouter news
     * @param News $news
     * @return void
     */
    abstract protected function add(News $news);

    /**
     * renvoi nbr de news
     * @return int
     */
    abstract public function count();

    /**
     * supprimer une news
     * @param $id int id de la news
     * @return void
     */
    abstract public function delete(int $id);

    /**
     *
     * @param int $debut
     * @param int $limite
     * @return array la liste des news. chaque entre instance de news
     */
    abstract public function getList($debut = -1, $limite = -1);


    /**
     * @param $id int de la news a recuperer
     * @return News la news demandé
     */
    abstract public function getUnique(int $id);

    /**
     * enregistre une news
     * @param News $news la news a enregistrer
     * @return void
     * @see self::modify()
     * @see self::add()
     */
    public function save(News $news)
    {
        if ($news->isValid()) {
            $news->isNew() ? $this->add($news) : $this->update($news);
        } else {
            throw new \http\Exception\RuntimeException('la news doit etre valide pour etre enregistré');
        }
    }

    /**
     * @param News $news news a modifier
     * @return void
     */
    abstract protected function update(News $news);

}
