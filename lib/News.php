<?php


class News
{
    protected $erreurs = array();
    protected $id;
    protected $auteur;
    protected $titre;
    protected $contenu;
    protected $dateAjout;
    protected $dateModif;

    /**
     * Constantes relatives aux erreurs possible
     */
    const AUTEUR_INVALIDE = 1;
    const TITRE_INVALIDE = 2 ;
    const CONTENU_INVALIDE = 3 ;

    /**
     * constructeur de la classe
     * @param array $valeurs
     */
    public function __construct($valeurs = array())
    {
        if (!empty($valeurs)){
            //si on specifi des valeurs alors on hydrate l'objet
            $this->hydrate($valeurs);
        }
    }

    /**
     * Méthode assignant les valeurs spécifiées aux attributs correspondant.
     * @param $donnees array Les données à assigner
     * @return void
     */
    public function hydrate(array $donnees)
    {
        foreach ($donnees as $attribut => $valeur){
            $methode = 'set'.ucfirst($attribut);
            if (is_callable(array($this, $methode))){
                $this->$methode($valeur);
            }
        }
    }

    /**
     * si la news est nouvelle
     * @return bool
     *
     */
    public function isNew()
    {
        return empty($this->id);
    }

    /**
     * si la news est valide
     * @return bool
     */
    public function isValid()
    {
        return !(empty($this->auteur) || empty($this->titre) || empty($this->contenu));
    }



    /**
     * @param array $erreurs
     */
    public function setErreurs(array $erreurs): void
    {
        $this->erreurs = $erreurs;
    }



    public function setId($id): int
    {
        $this->id = $id;
    }

    /**
     * @param mixed $auteur
     */
    public function setAuteur($auteur): void
    {
        if (!is_string($auteur) || empty($auteur)){
            $this->erreurs[] = self::AUTEUR_INVALIDE;
        }else{
            $this->auteur = $auteur;
        }
    }

    /**
     * @param mixed $titre
     */
    public function setTitre($titre): void
    {
        if (!is_string($titre) || empty($titre)){
            $this->erreurs[] = self::TITRE_INVALIDE;
        }else{
            $this->titre = $titre;
        }
    }

    /**
     * @param mixed $contenu
     */
    public function setContenu($contenu): void
    {
        if (!is_string($contenu) || empty($contenu)){
            $this->erreurs[] = self::CONTENU_INVALIDE;
        }else{
            $this->contenu = $contenu;
        }
    }

    /**
     * @param mixed $dateAjout
     */
    public function setDateAjout($dateAjout): void
    {
        if (is_string($dateAjout) && preg_match('le [0-9]{2}/[0-9]{2}/[0-9]{4} à [0-9]{2}h[0-9]{2}', $dateAjout)){
            $this->dateAjout = $dateAjout;
        }
    }

    /**
     * @param mixed $dateModif
     */
    public function setDateModif($dateModif): void
    {
        if (is_string($dateModif) && preg_match('le [0-9]{2}/[0-9]{2}/[0-9]{4} à [0-9]{2}h[0-9]{2}', $dateModif)){
            $this->dateModif = $dateModif;
        }
    }


    // getters


    public function erreurs()
    {
        return $this->erreurs;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return mixed
     */
    public function getAuteur()
    {
        return $this->auteur;
    }



    /**
     * @return mixed
     */
    public function getTitre()
    {
        return $this->titre;
    }



    /**
     * @return mixed
     */
    public function getContenu()
    {
        return $this->contenu;
    }



    /**
     * @return mixed
     */
    public function getDateAjout()
    {
        return $this->dateAjout;
    }



    /**
     * @return mixed
     */
    public function getDateModif()
    {
        return $this->dateModif;
    }





}
