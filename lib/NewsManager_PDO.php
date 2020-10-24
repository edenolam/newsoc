<?php


class NewsManager_PDO extends NewsManager
{
    /**
     * Attribut contenant l'instance representant la bdd
     * @type PDO
     */
    protected $db;

    /**
     * Constructeur étant chargé d'enregistrer l'instance de PDO dans l'attribut $db.
     * NewsManager_PDO constructor.
     * @param PDO $db le DAO
     * @return void
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }


    /**
     * @see NewsManager::add()
     * @inheritDoc
     */
    protected function add(News $news)
    {
        $requete = $this->db->prepare('INSERT INTO news SET auteur = :auteur, titre = :titre, contenu = :contenu, dateAjout = NOW(), dateModif = NOW()');
        $requete->bindValue(':titre, $news->titre()');
        $requete->bindValue(':auteur, $news->auteur()');
        $requete->bindValue(':contenu, $news->contenu()');
        $requete->execute();
    }

    /**
     * @see NewsManager::count()
     * @inheritDoc
     */
    public function count()
    {
        return $this->db->query('SELECT COUNT(*) FROM news')->fetchColumn();
    }

    /**
     * @see NewsManager::delete()
     * @inheritDoc
     */
    public function delete(int $id)
    {
        $this->db->exec('DELETE FROM news WHERE id = '.(int) $id);
    }

    /**
     * @see NewsManager::getList()
     * @inheritDoc
     */
    public function getList($debut = -1, $limite = -1)
    {
        $sql = 'SELECT id, auteur, titre, contenu, DATE_FORMAT (dateAjout, \'le %d/m%/%Y à %Hh%i\') AS dateAjout, DATE_FORMAT (dateModif, \'le %d/m%/%Y à %Hh%i\') AS dateModif FROM news ORDER BY id DESC';
        // on verifie l'integrite des parametres fournis
        if ($debut != -1 || $limite != -1){
            $sql .= ' LIMIT '.(int) $limite. 'OFFSET' .(int) $debut;
        }
        $requete = $this->db->query($sql);
        $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'News');
        $listeNews = $requete->fetchAll();
        $requete->closeCursor();
        return $listeNews;
    }

    /**
     * @param $id
     * @return mixed
     * @see NewsManager::getUnique()
     */
    public function getUnique ($id)
    {
        $requete = $this->db->prepare( 'SELECT id, auteur, titre, contenu, DATE_FORMAT (dateAjout, \'le %d/%m/%Y à %Hh%i\') AS dateAjout, DATE_FORMAT (dateModif, \'le %d/%m/%Y à %Hh%i\') AS dateModif FROM news WHERE id = :id');
        $requete -> bindValue (':id', (int) $id, PDO::PARAM_INT);
        $requete -> execute ();
        $requete -> setFetchMode (PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE , 'News');
        return $requete->fetch();
    }

    /**
     * @see NewsManager::update()
     * @inheritDoc
     */
    protected function update(News $news)
    {
        $requete = $this->db->prepare('UPDATE news SET auteur = :auteur, titre = :titre, contenu = :contenu, dateModif = NOW() WHERE id = :id');
        $requete->bindValue(':titre', $news->getTitre());
        $requete->bindValue(':auteur', $news->getAuteur());
        $requete->bindValue(':contenu', $news->getContenu());
        $requete->bindValue(':id', $news->getId(), PDO::PARAM_INT);
        $requete->execute();
    }
}
