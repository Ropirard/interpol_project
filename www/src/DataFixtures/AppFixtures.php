<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\User;
use App\Entity\Media;
use App\Entity\Charge;
use App\Entity\Gender;
use App\Entity\People;
use App\Entity\Report;
use App\Entity\EyesColor;
use App\Entity\HairColor;
use App\Entity\SkinColor;
use App\Entity\TypeReport;
use App\Entity\Nationality;
use App\Entity\SpokenLangage;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/** Remplir une Entité dans la DBB avec des valeurs 'Fausses' d'ou 'Fixture'
 * Facile pour le debug.*/
class AppFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
        //On charge nos fixtures 
        //En premier celle qui n'ont pas de FK avec d'autres
        $this->loadHairColor($manager);
        $this->loadEyesColor($manager);
        $this->loadGender($manager);
        $this->loadSkinColor($manager);
        $this->loadNationality($manager);
        $this->loadSpokenLanguage($manager);
        $this->loadCharge($manager);
        $this->loadUser($manager);
        $this->loadPeople($manager);
        $this->loadMedia($manager);
        $this->loadTypeReport($manager);
        $this->loadReport($manager);

        $manager->flush();
    }

    public function loadHairColor(ObjectManager $manager)
    {
        //On créer nos 'labels' => Couleur de cheveux
        $arrayHairColor = ['Noir', 'Brun', 'Châtain foncé', 'Châtain', 'Châtain clair', 'Blond foncé', 'Blond', 'Blond polaire', 'Roux', 'Gris / Blanc'];

        //On créer une nouvelle instance de la classe HairColor pour chaque valeur du tableau
        foreach ($arrayHairColor as $value) {
            $hairColor = new HairColor();
            //On met la valeur du tableau dans le label de l'instance
            $hairColor->setLabel($value);

            //On persist dans la bdd
            $manager->persist($hairColor);
            $this->addReference("hair_color_" . $value, $hairColor);
        }
    }

    public function loadEyesColor(ObjectManager $manager)
    {
        //On créer nos 'labels' => Couleur des yeux
        $arrayEyesColor = ['Marron', 'Bleu', 'Vert', 'Gris', 'Ambre', 'Noir', 'Vairon', 'Rouge'];

        //On créer une nouvelle instance de la classe EyesColor pour chaque valeur du tableau
        foreach ($arrayEyesColor as $value) {
            $eyesColor = new EyesColor();
            //On met la valeur du tableau dans le label de l'instance
            $eyesColor->setLabel($value);

            //On persist dans la bdd
            $manager->persist($eyesColor);
            $this->addReference("eyes_color_" . $value, $eyesColor);
        }
    }

    public function loadGender(ObjectManager $manager)
    {
        //On créer nos 'labels' => Couleur de peau
        $arrayGender = ['Homme', 'Femme', 'Autre'];

        //On créer une nouvelle instance de la classe SkinColor pour chaque valeur du tableau
        foreach ($arrayGender as $value) {
            $gender = new Gender();
            //On met la valeur du tableau dans le label de l'instance
            $gender->setLabel($value);

            //On persist dans la bdd
            $manager->persist($gender);
            $this->addReference("gender_" . $value, $gender);
        }
    }

    public function loadSkinColor(ObjectManager $manager)
    {
        $arraySkinColor = ['Blanc', 'Noir', 'Métisse'];

        foreach ($arraySkinColor as $value) {
            $skinColor = new SkinColor();
            $skinColor->setLabel($value);

            $manager->persist($skinColor);
            $this->addReference("skin_color_" . $value, $skinColor);
        }
    }

    public function loadNationality(ObjectManager $manager)
    {
        //On créer nos 'labels' => Nationalité
        $arrayNationality = ['Française', 'Anglaise', 'Américaine', 'Allemande', 'Espagnole', 'Italienne', 'Portugaise', 'Belge', 'Suisse', 'Canadienne', 'Japonaise', 'Chinoise', 'Algérienne', 'Marocaine', 'Tunisienne', 'Srilankais', 'Togolais', 'Malienne'];

        //On créer une nouvelle instance de la classe Nationality pour chaque valeur du tableau
        foreach ($arrayNationality as $value) {
            $nationality = new Nationality();
            //On met la valeur du tableau dans le label de l'instance
            $nationality->setLabel($value);

            //On persist dans la bdd
            $manager->persist($nationality);
            $this->addReference("nationality_" . $value, $nationality);
        }
    }

    public function loadSpokenLanguage(ObjectManager $manager)
    {
        //On créer nos 'labels' => Langue parlée
        $arraySpokenLanguage = ['Français', 'Anglais', 'Espagnol', 'Allemand', 'Italien', 'Portugais', 'Arabe', 'Mandarin', 'Japonais', 'Russe', 'Néerlandais', 'Polonais', 'Turc', 'Coréen', 'Indonésien'];

        //On créer une nouvelle instance de la classe SpokenLangage pour chaque valeur du tableau
        foreach ($arraySpokenLanguage as $value) {
            $spokenLanguage = new SpokenLangage();
            //On met la valeur du tableau dans le label de l'instance
            $spokenLanguage->setLabel($value);

            //On persist dans la bdd
            $manager->persist($spokenLanguage);
            $this->addReference("spoken_language_" . $value, $spokenLanguage);
        }
    }

    public function loadCharge(ObjectManager $manager)
    {
        //On créer nos 'labels' => Charge criminelle
        $arrayCharge = ['Vol à main armée', 'Homicide', 'Trafic d\'organes', 'Trafic de stupéfiants', 'Blanchiment d\'argent', 'Escroquerie', 'Incendie volontaire', 'Crime contre l\'humanité', 'Crime contre la paix', 'Fraude fiscale', 'Cybercriminalité', 'Extorsion', 'Homicide en bande organisée', 'Crime de guerre', 'Enlèvement', 'Pédocriminalité', 'Corruption', 'Terrorisme', 'Trafic d\'armes à feu', 'Faux et usage de faux'];

        //On créer une nouvelle instance de la classe Charge pour chaque valeur du tableau
        foreach ($arrayCharge as $value) {
            $charge = new Charge();
            //On met la valeur du tableau dans le label de l'instance
            $charge->setLabel($value);

            //On persist dans la bdd
            $manager->persist($charge);
            $this->addReference("charge_" . $value, $charge);
        }
    }

    public function loadUser(ObjectManager $manager)
    {
        //Création de l'Admin
        $admin = new User();
        $admin->setEmail('admin@admin.com');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin'));
        $admin->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $admin->setName('Mon');
        $admin->setLastname('Admin');
        $admin->setPhoneNumber('0123456789');
        $admin->setIdentityNumber('0123456789');
        $admin->setCreatedAt(new DateTime());
        $admin->setIsActive(true);

        $manager->persist($admin);

        //Création d'utilisateur 
        $arrayUser = [
            ['email' => 'user1@user.com', 'lastname' => '1', 'phone_number' => '0684572214', 'identity_number' => '830141967'],
            ['email' => 'user2@user.com', 'lastname' => '2', 'phone_number' => '0712345678', 'identity_number' => '492817305'],
            [
                'email' => 'user3@user.com',
                'lastname' => '3',
                'phone_number' => '0678932145',
                'identity_number' =>
                '761305924'
            ],
            ['email' => 'user4@user.com', 'lastname' => '4', 'phone_number' => '0698457321', 'identity_number' => '158942673'],
            [
                'email' => 'user5@user.com',
                'lastname' => '5',
                'phone_number' => '0723459816',
                'identity_number' =>
                '904736281'
            ],
        ];

        //On créer nos instances d'user
        foreach ($arrayUser as $key => $value) {
            //On créer une nouvelle instance de User pour chaque 'ligne' du tab
            $user = new User();

            //On set ses valeurs selon les valeur du tab 
            $user->setEmail($value['email']);
            $user->setName('User');
            $user->setLastname($value['lastname']);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'user'));
            $user->setRoles(['ROLE_USER']);
            $user->setCreatedAt(new DateTime());
            $user->setIsActive(true);
            $user->setPhoneNumber($value['phone_number']);
            $user->setIdentityNumber($value['identity_number']);

            //On persiste l'entité
            $manager->persist($user);

            //On ajoute une référence pour les relations
            $this->addReference('user_' . $key, $user);
        }
    }

    public function loadPeople(ObjectManager $manager)
    {
        //Création des datas uniques 
        $arrayPeople = [
            [
                'type' => 'Criminel',
                'name' => 'Kassim',
                'lastname' => 'Belkacem',
                'height' => '175',
                'weight' => '70',
                'features' => 'Tatouage de scorpion sur le cou, incisive supérieure gauche manquante.',
                'birthPlace' => 'Marseille',
                'researchBy' => 'France',
                'hair_color' => 'Noir',
                'eyes_color' => 'Marron',
                'skin_color' => 'Blanc',
                'gender' => 'Homme',
                'nationality' => ['Française', 'Algérienne'],
                'charge' => ['Vol à main armée', 'Extorsion'],
                'spoken_language' => ['Français', 'Arabe'],
            ],
            [
                'type' => 'Disparu',
                'name' => 'Elena',
                'lastname' => 'Vaduva',
                'height' => '168',
                'weight' => '58',
                'features' => 'Tache de naissance importante sur l\'avant-bras droit, porte des lunettes de vue noires.',
                'birthPlace' => 'Lyon',
                'researchBy' => 'Interpol',
                'hair_color' => 'Brun',
                'eyes_color' => 'Bleu',
                'skin_color' => 'Blanc',
                'gender' => 'Femme',
                'nationality' => ['Anglaise', 'Italienne'],
                'charge' => [],
                'spoken_language' => ['Anglais', 'Italien'],
            ],
            [
                'type' => 'Criminel',
                'name' => 'Marc-André',
                'lastname' => 'Lussier',
                'height' => '192',
                'weight' => '95',
                'features' => 'Nez cassé, plusieurs cicatrices de lacération sur le torse.',
                'birthPlace' => 'Montpellier',
                'researchBy' => 'Belgique',
                'hair_color' => 'Châtain',
                'eyes_color' => 'Vert',
                'skin_color' => 'Blanc',
                'gender' => 'Homme',
                'nationality' => ['Belge'],
                'charge' => ['Trafic de stupéfiants', 'Trafic d\'armes à feu'],
                'spoken_language' => ['Français', 'Néerlandais', 'Allemand'],
            ],
            [
                'type' => 'Criminel',
                'name' => 'Sonia',
                'lastname' => 'Tessier',
                'height' => '162',
                'weight' => '52',
                'features' => 'Cheveux rasés sur les côtés, piercing à l\'arcade sourcilière gauche.',
                'birthPlace' => 'Bordeaux',
                'researchBy' => 'France',
                'hair_color' => 'Blond',
                'eyes_color' => 'Gris',
                'skin_color' => 'Blanc',
                'gender' => 'Femme',
                'nationality' => ['Française', 'Suisse'],
                'charge' => ['Blanchiment d\'argent'],
                'spoken_language' => ['Français', 'Anglais'],
            ],
            [
                'type' => 'Criminel',
                'name' => 'Dimitri',
                'lastname' => 'Volkov',
                'height' => '180',
                'weight' => '82',
                'features' => 'Cicatrice de brûlure sur la main gauche, accent étranger marqué.',
                'birthPlace' => 'Strasbourg',
                'researchBy' => 'Allemagne',
                'hair_color' => 'Noir',
                'eyes_color' => 'Bleu',
                'skin_color' => 'Blanc',
                'gender' => 'Homme',
                'nationality' => ['Allemande', 'Srilankais'],
                'charge' => ['Crime de guerre', 'Terrorisme', 'Homicide en bande organisée'],
                'spoken_language' => ['Russe', 'Allemand', 'Polonais'],
            ],
            [
                'type' => 'Disparu',
                'name' => 'Lucas',
                'lastname' => 'Garnier',
                'height' => '178',
                'weight' => '74',
                'features' => 'Grain de beauté proéminent sur la joue gauche, porte souvent une casquette.',
                'birthPlace' => 'Nantes',
                'researchBy' => 'France',
                'hair_color' => 'Blond foncé',
                'eyes_color' => 'Marron',
                'skin_color' => 'Blanc',
                'gender' => 'Homme',
                'nationality' => ['Française', 'Portugaise'],
                'charge' => [],
                'spoken_language' => ['Français', 'Portugais'],
            ],
            [
                'type' => 'Criminel',
                'name' => 'Ismaël',
                'lastname' => 'Diop',
                'height' => '188',
                'weight' => '85',
                'features' => 'Oreille droite décollée, cicatrice horizontale sur le menton.',
                'birthPlace' => 'Dakar',
                'researchBy' => 'France',
                'hair_color' => 'Noir',
                'eyes_color' => 'Noir',
                'skin_color' => 'Noir',
                'gender' => 'Homme',
                'nationality' => ['Togolais'],
                'charge' => ['Trafic d\'armes à feu'],
                'spoken_language' => ['Français'],
            ],
            [
                'type' => 'Disparu',
                'name' => 'Clara',
                'lastname' => 'Mendez',
                'height' => '170',
                'weight' => '63',
                'features' => 'Tatouage floral couvrant l\'intégralité de l\'épaule gauche, yeux vairons.',
                'birthPlace' => 'Toulouse',
                'researchBy' => 'Espagne',
                'hair_color' => 'Roux',
                'eyes_color' => 'Vairon',
                'skin_color' => 'Blanc',
                'gender' => 'Femme',
                'nationality' => ['Espagnole', 'Française', 'Américaine'],
                'charge' => [],
                'spoken_language' => ['Espagnol', 'Français', 'Anglais'],
            ],
            [
                'type' => 'Criminel',
                'name' => 'Yacine',
                'lastname' => 'Haddad',
                'height' => '182',
                'weight' => '80',
                'features' => 'Manque la phalange distale de l\'index droit, cicatrice de morsure sur l\'épaule.',
                'birthPlace' => 'Grenoble',
                'researchBy' => 'France',
                'hair_color' => 'Noir',
                'eyes_color' => 'Marron',
                'skin_color' => 'Métisse',
                'gender' => 'Homme',
                'nationality' => ['Algérienne'],
                'charge' => ['Cybercriminalité', 'Blanchiment d\'argent', 'Escroquerie'],
                'spoken_language' => ['Arabe'],
            ],
            [
                'type' => 'Disparu',
                'name' => 'Sophie',
                'lastname' => 'Lebrun',
                'height' => '165',
                'weight' => '55',
                'features' => 'Large cicatrice chirurgicale au genou droit, porte un appareil auditif discret.',
                'birthPlace' => 'Lille',
                'researchBy' => 'Suisse',
                'hair_color' => 'Châtain clair',
                'eyes_color' => 'Vert',
                'skin_color' => 'Blanc',
                'gender' => 'Femme',
                'nationality' => ['Suisse'],
                'charge' => [],
                'spoken_language' => ['Français', 'Allemand', 'Anglais'],
            ]
        ];

        //Enregistre les datas 
        foreach ($arrayPeople as $key => $value) {
            $people = new People();
            $people->setType($value['type']);
            $people->setName($value['name']);
            $people->setLastname($value['lastname']);
            $people->setHeight($value['height']);
            $people->setWeight($value['weight']);
            $people->setFeatures($value['features']);
            $people->setBirthPlace($value['birthPlace']);
            $people->setResearchBy($value['researchBy']);
            $people->setIsCaptured(false);

            //Rand de createdAt
            $createdAt = new DateTime();
            $createdAt->modify('-' . rand(0, 30) . 'days');
            $people->setCreatedAt($createdAt);

            //Rand de BirthDate
            $start = strtotime('1970-01-01'); // date min
            $end = strtotime('2006-12-31'); // date max
            $randomTimestamp = rand($start, $end);
            $birthDate = (new DateTime())->setTimestamp($randomTimestamp);
            $people->setBirthDate($birthDate);

            //Set des FK  
            $people->setHairColor($this->getReference('hair_color_' . $value['hair_color'], HairColor::class));
            $people->setEyesColor($this->getReference('eyes_color_' . $value['eyes_color'], EyesColor::class));
            $people->setSkinColor($this->getReference('skin_color_' . $value['skin_color'], SkinColor::class));
            $people->setGender($this->getReference('gender_' . $value['gender'], Gender::class));

            //ManytoMany
            foreach ($value['nationality'] as $nat) {
                $people->addNationality($this->getReference('nationality_' . $nat, Nationality::class));
            }
            foreach ($value['charge'] as $chg) {
                $people->addCharge($this->getReference('charge_' . $chg, Charge::class));
            }
            foreach ($value['spoken_language'] as $lang) {
                $people->addSpokenLangage($this->getReference('spoken_language_' . $lang, SpokenLangage::class));
            }

            //On inscrit en bdd
            $manager->persist($people);

            //On ajoute une référence pour les relations
            $this->addReference('people_' . $key, $people);
        }
    }

    public function loadReport(ObjectManager $manager)
    {
        $arrayReport = [
            [
                'content' => "J'ai aperçu cette personne près de la gare centrale hier soir. Elle correspond à la description d'une personne disparue depuis 3 semaines. Je pense l'avoir déjà vue dans le quartier de la vieille ville.",
                'statut'  => 'en cours',
            ],
            [
                'content' => "Cette personne me semble être le même individu que j'ai vu sur une vidéo de surveillance. Elle avait un sac à dos rouge et un tatouage visible sur l'avant-bras. Je pense qu'elle pourrait être liée à une affaire de disparition.",
                'statut'  => 'approuvé',
            ],
            [
                'content' => "Je crois reconnaître cette personne. Elle a discuté avec mon voisin il y a quelques jours. Le voisin a dit qu'elle cherchait un endroit pour dormir. Je n'ai pas plus d'infos mais je pense que ça mérite une vérification.",
                'statut'  => 'en cours',
            ],
            [
                'content' => "J'ai vu cette personne dans un bar du centre-ville. Elle semblait chercher quelqu'un et parlait d'une affaire criminelle. Je pense qu'elle pourrait être impliquée dans un vol récent. À vérifier.",
                'statut'  => 'rejecté',
            ],
            [
                'content' => "Je suis presque certain que c'est la personne recherchée. Elle portait les mêmes vêtements que ceux décrits dans l'avis de disparition. Je l'ai vue près d'une station de métro à 23h.",
                'statut'  => 'fermé',
            ],
            [
                'content' => "Cette personne m'a abordé en me demandant si je connaissais quelqu'un du nom de 'Léo'. Elle semblait nerveuse et cherchait à se cacher. Je pense qu'elle pourrait être liée à un crime récent.",
                'statut'  => 'approuvé',
            ],
            [
                'content' => "J'ai vu cette personne dans un parc, elle semblait désorientée. Elle m'a dit qu'elle avait perdu ses papiers. Je pense qu'elle pourrait être la personne disparue mentionnée dans les médias.",
                'statut'  => 'en cours',
            ],
            [
                'content' => "Je connais cette personne par un intermédiaire. Elle a déjà été impliquée dans des vols à l'étalage. Je l'ai vue aujourd'hui en train de surveiller une bijouterie. Je pense qu'il faut vérifier.",
                'statut'  => 'rejecté',
            ],
            [
                'content' => "J'ai reconnu cette personne dans un café. Elle avait une cicatrice sur la joue gauche, exactement comme décrit dans l'avis de recherche. Je pense qu'elle est la personne disparue.",
                'statut'  => 'approuvé',
            ],
            [
                'content' => "Je ne suis pas sûr, mais il se pourrait que ce soit la même personne que celle recherchée. Elle avait l'air de fuir quelqu'un et parlait au téléphone en demandant de l'aide. Je signale au cas où.",
                'statut'  => 'fermé',
            ],
        ];

        foreach ($arrayReport as $value) {
            $report = new Report();
            $report->setContent($value['content']);
            $report->setStatut($value['statut']);

            //Rand de createdAt
            $createdAt = new DateTime();
            $createdAt->modify('-' . rand(0, 30) . 'days');
            $report->setCreatedAt($createdAt);

            //Si le report a été vu ca veut dire qu'il a une date de résolution 
            if ($value['statut'] != 'en cours') {
                $resolvedAt = new DateTime();
                $resolvedAt->modify('-' . rand(0, 30) . 'days');
                $report->setResolvedAt($resolvedAt);
            }

            //Les relations (FK)
            $report->setUser($this->getReference('user_' . rand(0, 4), User::class));
            $report->setPeople($this->getReference('people_' . rand(0, 9), People::class));
            $report->setTypeReport($this->getReference('typeReport_' . rand(0, 3), TypeReport::class));

            //On persist l'entité
            $manager->persist($report);
        }
    }

    public function loadMedia(ObjectManager $manager)
    {
        $arrayMedia = [
            ['path' => '/images/dessin.png', 'people_key' => 0],
            ['path' => '/images/photo.png', 'people_key' => 1],
            ['path' => '/images/code.png', 'people_key' => 2],
            ['path' => '/images/projet.png', 'people_key' => 3],
        ];

        foreach ($arrayMedia as $key => $value) {
            $media = new Media();
            $media->setPath($value['path']);
            $media->setPeople($this->getReference('people_' . $value['people_key'], People::class));

            $manager->persist($media);
            $this->addReference('media_' . $key, $media);
        }
    }

    public function loadTypeReport(ObjectManager $manager)
    {
        $types = [
            [
                'label' => 'J’ai aperçu cette personne récemment et je pense que cette information peut être utile',
            ],
            [
                'label' => 'Je connais personnellement cette personne et je peux fournir des informations supplémentaires',
            ],
            [
                'label' => 'J’ai été témoin d’un comportement ou d’une activité suspecte impliquant cette personne',
            ],
            [
                'label' => 'Cette information m’a été rapportée par un tiers et mérite vérification',
            ],
        ];

        foreach ($types as $key => $value) {
            //On créer une nouvelle instance de TypeReport pour chaque 'lignes' du tab
            $type = new TypeReport();

            //On set la valeur de son label avec celle renseignée dans le tab
            $type->setLabel($value['label']);

            //On persist l'entité
            $manager->persist($type);

            //On ajoute une référence pour les relations
            $this->addReference('typeReport_' . $key, $type);
        }
    }
}
