<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\User;
use App\Entity\Media;
use App\Entity\Charge;
use App\Entity\Criminal;
use App\Entity\Gender;
use App\Entity\EyesColor;
use App\Entity\HairColor;
use App\Entity\SkinColor;
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
        $this->loadCriminal($manager);
        $this->loadMedia($manager);


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
            ['email' => 'user3@user.com', 'lastname' => '3', 'phone_number' => '0678932145', 'identity_number' =>
            '761305924'],
            ['email' => 'user4@user.com', 'lastname' => '4', 'phone_number' => '0698457321', 'identity_number' => '158942673'],
            ['email' => 'user5@user.com', 'lastname' => '5', 'phone_number' => '0723459816', 'identity_number' =>
            '904736281'],
        ];

        //On créer nos instances d'user
        foreach ($arrayUser as $key => $value) {
            $user = new User();
            $user->setEmail($value['email']);
            $user->setName('User');
            $user->setLastname($value['lastname']);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'user'));
            $user->setRoles(['ROLE_USER']);
            $user->setCreatedAt(new DateTime());
            $user->setIsActive(true);
            $user->setPhoneNumber($value['phone_number']);
            $user->setIdentityNumber($value['identity_number']);

            $manager->persist($user);
            $this->addReference('user_' . $key, $user);
        }
    }

    public function loadCriminal(ObjectManager $manager)
    {
        //Création des datas uniques 
        $arrayCriminal = [
            [
                'name' => 'Kassim',
                'lastname' => 'Belkacem',
                'height' => '175',
                'weight' => '70',
                'features' => 'Tatouage de scorpion sur le cou, incisive supérieure gauche manquante.',
                'birthPlace' => 'Marseille',
                'researchBy' => 'France',
            ],
            [
                'name' => 'Elena',
                'lastname' => 'Vaduva',
                'height' => '168',
                'weight' => '58',
                'features' => 'Tache de naissance importante sur l\'avant-bras droit, porte des lunettes de vue noires.',
                'birthPlace' => 'Lyon',
                'researchBy' => 'Interpol',
            ],
            [
                'name' => 'Marc-André',
                'lastname' => 'Lussier',
                'height' => '192',
                'weight' => '95',
                'features' => 'Nez cassé, plusieurs cicatrices de lacération sur le torse.',
                'birthPlace' => 'Montpellier',
                'researchBy' => 'Belgique',
            ],
            [
                'name' => 'Sonia',
                'lastname' => 'Tessier',
                'height' => '162',
                'weight' => '52',
                'features' => 'Cheveux rasés sur les côtés, piercing à l\'arcade sourcilière gauche.',
                'birthPlace' => 'Bordeaux',
                'researchBy' => 'France',
            ],
            [
                'name' => 'Dimitri',
                'lastname' => 'Volkov',
                'height' => '180',
                'weight' => '82',
                'features' => 'Cicatrice de brûlure sur la main gauche, accent étranger marqué.',
                'birthPlace' => 'Strasbourg',
                'researchBy' => 'Allemagne',
            ],
            [
                'name' => 'Lucas',
                'lastname' => 'Garnier',
                'height' => '178',
                'weight' => '74',
                'features' => 'Grain de beauté proéminent sur la joue gauche, porte souvent une casquette.',
                'birthPlace' => 'Nantes',
                'researchBy' => 'France',
            ],
            [
                'name' => 'Ismaël',
                'lastname' => 'Diop',
                'height' => '188',
                'weight' => '85',
                'features' => 'Oreille droite décollée, cicatrice horizontale sur le menton.',
                'birthPlace' => 'Dakar',
                'researchBy' => 'France',
            ],
            [
                'name' => 'Clara',
                'lastname' => 'Mendez',
                'height' => '170',
                'weight' => '63',
                'features' => 'Tatouage floral couvrant l\'intégralité de l\'épaule gauche, yeux vairons.',
                'birthPlace' => 'Toulouse',
                'researchBy' => 'Espagne',
            ],
            [
                'name' => 'Yacine',
                'lastname' => 'Haddad',
                'height' => '182',
                'weight' => '80',
                'features' => 'Manque la phalange distale de l\'index droit, cicatrice de morsure sur l\'épaule.',
                'birthPlace' => 'Grenoble',
                'researchBy' => 'France',
            ],
            [
                'name' => 'Sophie',
                'lastname' => 'Lebrun',
                'height' => '165',
                'weight' => '55',
                'features' => 'Large cicatrice chirurgicale au genou droit, porte un appareil auditif discret.',
                'birthPlace' => 'Lille',
                'researchBy' => 'Suisse',
            ]
        ];

        //Enregistre les datas 
        foreach ($arrayCriminal as $value) {
            $criminal = new Criminal();
            $criminal->setName($value['name']);
            $criminal->setLastname($value['lastname']);
            $criminal->setHeight($value['height']);
            $criminal->setWeight($value['weight']);
            $criminal->setFeatures($value['features']);
            $criminal->setBirthPlace($value['birthPlace']);
            $criminal->setResearchBy($value['researchBy']);
            $criminal->setIsCaptured(false);

            //Rand de createdAt
            $createdAt = new DateTime();
            $createdAt->modify('-' . rand(0, 30) . 'days');
            $criminal->setCreatedAt($createdAt);

            //Rand de BirthDate
            $start = strtotime('1970-01-01'); // date min
            $end   = strtotime('2006-12-31'); // date max
            $randomTimestamp = rand($start, $end);
            $birthDate = (new DateTime())->setTimestamp($randomTimestamp);
            $criminal->setBirthDate($birthDate);

            //Set des FK  
            $criminal->setHairColor($this->getReference('hair_color_' . $value['hair_color'], HairColor::class));
            $criminal->setEyesColor($this->getReference('eyes_color_' . $value['eyes_color'], EyesColor::class));
            $criminal->setSkinColor($this->getReference('skin_color_' . $value['skin_color'], SkinColor::class));
            $criminal->setGender($this->getReference('gender_' . $value['gender'], Gender::class));
            
            //ManytoMany
            $criminal->addNationality();
            $criminal->addCharge();
            $criminal->addSpokenLangage();


            //On inscrit en bdd
            $manager->persist($criminal);
            $this->addReference('criminal_' . $value, $criminal);

        }
    }

    public function loadMedia(ObjectManager $manager)
    {
        $arrayMedia = [
            ['path' => '/images/dessin.png'],
            ['path' => '/images/photo.png'],
            ['path' => '/images/code.png'],
            ['path' => '/images/projet.png'],
        ];

        foreach ($arrayMedia as $value) {
            $media = new Media();
            $media->setPath($value['path']);

            $media->setCriminal($this->addReference('criminal_' ))

            $manager->persist($media);
            $this->addReference('media_' . $value, $media);
        }
    }
}
