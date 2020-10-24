<?php


class NewsManager_MySQLi extends NewsManager
{

    /**
     * Attribut contenant l'instance representant la bdd
     * @type MySQLi
     */
    protected $db;

    /**
     * constructeur etant chargé d'enregistrer l'instance de mysqli dans l'attribut $db
     * @param $db MySQLi le DAO
     * @return void
     */
    public function __construct(MySQLi $db)
    {
        $this->db = $db;
    }

    /**
     * @inheritDoc
     * @see NewsManager::add()
     */
    protected function add(News $news)
    {
        $requete = $this->db->prepare('INSERT INTO news SET auteur = ?, titre = ?, contenu = ?, dateAjout = NOW(), dateModif = NOW()');
        $auteur = $news->getAuteur();
        $titre = $news->getTitre();
        $requete->bind_param('sss', $auteur, $titre, $news->getContenu());
        $requete->execute();
    }

    /**
     * @inheritDoc
     * @see NewsManager::count()
     */
    public function count()
    {
        return $this->db->query('SELECT id FROM news')->num_rows;
    }

    /**
     * @inheritDoc
     * @see NewsManager::delete()
     */
    public function delete(int $id)
    {
        $id = (int)$id;
        $requete = $this->db->prepare('DELETE FROM news WHERE id = ?');
        $requete->bind_param('i', $id);
        $requete->execute();
    }

    /**
     * @see NewsManager::getList()
     * @inheritDoc
     */
    public function getList($debut = -1, $limite = -1)
    {
        $listeNews = array();
        $sql = 'SELECT id, auteur, titre, contenu, DATE_FORMAT (dateAjout, \'le %d/%m/%Y à %Hh%i\') AS dateAjout, DATE_FORMAT (dateModif, \'le %d/%m/%Y à %Hh%i\') AS dateModif FROM news ORDER BY id DESC';
        // on verifie l'integralite des parametres fournis
        if ($debut != -1 || $limite != -1) {
            $sql .= ' LIMIT ' . (int)$limite . ' OFFSET ' . (int)$debut;
        }
        $requete = $this->db->query($sql);
        while ($news = $requete->fetch_object('News')) {
            $listeNews[] = $news;
        }
        return $listeNews;
    }

    /**
     * @see NewsManager::getUnique()
     * @inheritDoc
     */
    public function getUnique(int $id)
    {
        $id = (int)$id;
        $requete = $this->db->prepare('SELECT id, auteur, titre, contenu, DATE_FORMAT (dateAjout, \'le %d/%m/%Y à %Hh%i\') AS dateAjout, DATE_FORMAT (dateModif, \'le %d/%m/%Y à %Hh%i\') AS dateModif FROM news WHERE id = ?');
        $requete->bind_param('i', $id);
        $requete->execute();
        $requete->bind_result($id, $auteur, $titre, $contenu, $dateAjout, $dateModif);
        $requete->fetch();

        return new News(array(
            'id' => $id,
            'auteur' => $auteur,
            'contenu' => $contenu,
            'dateAjout' => $dateAjout,
            'dateModif' => $dateModif
        ));
    }

    /**
     * @see NewsManager::update()
     * @inheritDoc
     */
    protected function update(News $news)
    {
        $requete = $this->db->prepare('UPDATE news SET auteur = ?, titre = ?, contenu = ?, dateModif = NOW() WHERE id = ?');
        $titre = $news->getTitre();
        $auteur = $news->getAuteur();
        $requete->bind_param('sssi', $auteur, $titre, $news->getContenu(), $news->getId());
        $requete->execute();
    }
}
