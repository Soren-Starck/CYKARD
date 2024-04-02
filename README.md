<h1>Répartition des tâches</h1>
<p>Daniil : Réalisation des API, du système de routage et des vues Twig</p>
<p>Clément : Réalisation des API, des tests et de la base de donnée</p>
<p>Giovanni : Conception et déploiement de la base de donnée, réalisation de tests et d’API</p>
<p>Marius : élaboration des librairies réactive et des store utilisés en front-end, réalisation du front-end et configuration de Docker pour déployer le site web</p>
<p>Soren : Réalisation des composants réactive et implémentation dans le front-end, structuration dans les vues twig et réalisation du design graphique avec TailwindCSS</p>

<h1>Améliorations du site web "Trello-Trollé"</h1>
Le projet "Trello-Trollé" avait pour objectif d'auditer et d'améliorer une application web existante, similaire à Trello mais initialement développée uniquement en PHP, présentant des limitations majeures en termes d'expérience utilisateur, de sécurité et de performances de base de données.

Les améliorations implémentées dans le cadre de ce projet comprenaient :

Intégration de JavaScript et AJAX : Pour dynamiser l'interface utilisateur, éliminer les rechargements de page et offrir une expérience plus fluide et réactive.

Normalisation et optimisation de la base de données PostgreSQL : Pour améliorer la sécurité, les performances et la maintenabilité de la base de données.

Renforcement de la sécurité : Avec des techniques avancées de hashage des mots de passe, la mise en œuvre de requêtes préparées et le nettoyage systématique des entrées utilisateur.

Ajout de nouvelles fonctionnalités et amélioration du design du site : Pour enrichir l'expérience utilisateur et rendre le site plus attrayant visuellement.

### Optimisation de la Structure de Données

#### Remplacement des fichiers JSON par des relations de table

L'optimisation de la structure de données dans notre projet "Trello-Trollé" a été cruciale pour améliorer la performance, la sécurité et l'expérience utilisateur de l'application. Nous avons remplacé les fichiers JSON par des relations de table plus structurées pour résoudre les problèmes de redondance des données et de complexité des accès. Voici les étapes de mise en œuvre et les avantages significatifs :

- **Analyse des structures de données JSON** : Identification des informations clés à migrer vers des tables relationnelles.
- **Conception d'un schéma de base de données relationnelle** : Création de tables spécifiques pour les utilisateurs, les tableaux, les colonnes et les cartes, avec des relations claires entre elles.
- **Scripts de migration des données** : Transfert des données des fichiers JSON vers les nouvelles tables tout en assurant l'intégrité et la cohérence des données.
- **Avantages** :
  - Réduction de la redondance des données.
  - Simplification des accès et des manipulations des données.
  - Amélioration de la sécurité et de la scalabilité de l'application.

#### Suppression des attributs sensibles

La sécurisation de l'application "Trello-Trollé" a été une priorité absolue, notamment en ce qui concerne la protection des informations sensibles des utilisateurs. Nous avons supprimé les attributs sensibles, comme les mots de passe en clair, de la base de données pour adopter des pratiques de stockage des données plus robustes et conformes aux standards de sécurité modernes. Voici les étapes et les bénéfices de cette démarche :

- **Suppression des mots de passe en clair** : Élimination des colonnes stockant les mots de passe lisibles.
- **Hachage des mots de passe** : Utilisation de fonctions de hachage cryptographique avancées avec un sel unique pour rendre les mots de passe indéchiffrables.
- **Révision des méthodes d'authentification** : Implémentation de vérifications basées sur les versions hachées des mots de passe pour renforcer la sécurité de l'authentification.
- **Avantages** :
  - Réduction du risque de fuites de données.
  - Renforcement de la confiance des utilisateurs dans la sécurité de la plateforme.
  - Conformité avec les réglementations sur la protection des données, comme le RGPD.

Ces actions témoignent de notre engagement envers la sécurisation et l'amélioration continue de l'application "Trello-Trollé".

### Normalisation et Sécurité de la Base de Données

#### Processus de normalisation

La normalisation de la base de données pour le projet "Trello-Trollé" a été une étape cruciale pour optimiser la structure des données et renforcer la sécurité. Notre objectif était d'atteindre la Troisième Forme Normale (3FN), pour laquelle nous avons suivi une méthodologie structurée en plusieurs étapes clés, accompagnées par des modifications spécifiques au niveau du code SQL.

- **Étape 1: Analyse initiale et identification des anomalies** : Analyse détaillée de la structure existante de la base de données pour identifier les anomalies telles que la redondance des données et les clés primaires mal définies.
- **Étape 2: Application de la Première Forme Normale (1FN)** : Modification de la structure pour s'assurer que chaque attribut de table soit atomique et introduction d'identifiants uniques pour chaque table.
- **Étape 3: Deuxième Forme Normale (2FN)** : Élimination des dépendances partielles en s'assurant que tous les attributs non-clés soient pleinement fonctionnels et dépendants de la clé primaire dans chaque table.
- **Étape 4: Troisième Forme Normale (3FN)** : Élimination des dépendances transitives pour assurer que les attributs non-clés ne dépendent que de la clé primaire.

Cette démarche de normalisation a non seulement amélioré la structure et l'efficacité de notre base de données mais a également renforcé sa sécurité en évitant la duplication des données et en clarifiant les relations entre les différentes entités.

#### Renforcement de la sécurité

Dans le cadre du renforcement de la sécurité pour notre projet "Trello-Trollé", nous avons mis en place plusieurs mesures pour garantir la protection des données et améliorer l'ensemble de la sécurité de l'application. Voici les principaux axes d'amélioration que nous avons implémentés :

- **Hachage des mots de passe** : Adoption de l'algorithme de hachage BCrypt avec un sel unique pour rendre les mots de passe indéchiffrables, renforçant ainsi la protection contre les attaques par force brute et les attaques par dictionnaire.
- **Sécurisation des cookies** : Implémentation de flags sécurisés sur tous les cookies, notamment `HttpOnly` et `Secure`, pour empêcher l'accès via JavaScript et garantir l'envoi uniquement via des connexions HTTPS sécurisées. Ajout de l'attribut `SameSite` à `Strict` pour protéger contre les attaques CSRF.
- **Mesures supplémentaires** : Mise en place de contrôles d'accès stricts, validation et assainissement systématiques des entrées utilisateur pour prévenir les injections SQL et les attaques XSS. Utilisation de préparations de requêtes SQL et adoption d'une politique de Content Security Policy (CSP) pour minimiser les risques d'injection et d'exécution de scripts malveillants.

Ces améliorations de la sécurité, soutenues par une documentation technique détaillée et des tests rigoureux, ont grandement contribué à la fiabilité et à la robustesse de "Trello-Trollé". Non seulement elles ont adressé les vulnérabilités identifiées, mais elles ont également posé les bases d'une culture de développement axée sur la sécurité au sein de notre équipe, assurant ainsi la protection des données des utilisateurs et la confiance dans notre application.

### Optimisation des Performances et de l'Expérience Utilisateur

#### Améliorations fonctionnelles

Dans le cadre de l'amélioration de notre site de gestion de tableaux d'avancement de projet, nous avons apporté plusieurs ajustements significatifs pour améliorer l'expérience utilisateur.

- **Refonte du design avec Tailwind CSS** : Nous avons revu le design et l'interface du site en utilisant Tailwind CSS, ce qui a considérablement amélioré l'esthétique et l'ergonomie de la plateforme, la rendant plus intuitive et agréable à utiliser.

![Nouvelle Page d'Accueil](homepage.png)
*Figure 1: Nouvelle Page d'Accueil*

- **Implémentation de composants Popups** : Les Popups permettent aux utilisateurs d'effectuer toutes les actions liées aux tableaux sans quitter la page principale. Par exemple, en cliquant sur une carte, une popup s'affiche, offrant la possibilité de modifier directement son titre, sa description et sa couleur.

- **Fonctionnalité de glisser-déposer** : Les utilisateurs peuvent désormais déplacer les cartes d'une colonne à l'autre facilement. Si une carte est déposée sur le bouton "nouvelle colonne", une popup s'ouvre, permettant à l'utilisateur de créer une nouvelle colonne et d'y attribuer la carte.

- **Interface épurée et réactive** : Des éléments interactifs apparaissent lorsque l'utilisateur survole une colonne, tels qu'un bouton pour créer une nouvelle carte ou pour modifier la colonne. De plus, les popups réactives sont très intuitives d’utilisation.

- **Gestion améliorée des tableaux** : La page de la liste des tableaux de l'utilisateur a été restructurée pour plus de praticité, avec l'ajout d'un bouton "plus" pour créer rapidement un nouveau tableau et des fonctionnalités telles que la suppression d'un tableau désormais facilement accessibles.

#### Gestion des rôles, des droits d'accès et système de routage

Dans notre projet, la gestion des rôles et des droits d'accès est cruciale pour assurer une navigation sécurisée et une expérience utilisateur optimisée.

- **Authentification basée sur les jetons JWT** : Nous avons adopté un système basé sur les jetons JWT pour vérifier l'identité des utilisateurs lors de leur connexion et sécuriser les échanges ultérieurs avec l'application.

- **Gestion des rôles et des permissions** : Chaque utilisateur se voit attribuer des rôles spécifiques définissant les permissions associées, organisant ainsi l'accès aux fonctionnalités en fonction des responsabilités de chaque utilisateur. Un système de droits d'accès offre une granularité plus fine dans la gestion des permissions, permettant de contrôler l'accès aux ressources à un niveau individuel.

- **Système de routage basé sur Symfony** : Le système de routage assure que chaque requête utilisateur est correctement dirigée vers la fonctionnalité appropriée, facilitant ainsi une expérience utilisateur fluide et intuitive.

#### Reactive et store

Afin de créer les fonctionnalités réactives, nous avons développé une bibliothèque JavaScript inspirée de React pour créer des composants réactifs et réutilisables. Voici un exemple de component :

```javascript
// Exemple de component réactif
const Popup = (props) => {
    return (
        <div className="popup">
            <div className="popup-content">
                {props.children}
            </div>
        </div>
    );
};
```

Cette bibliothèque nous a permis de créer des composants dynamiques et interactifs, améliorant ainsi l'expérience utilisateur sur notre site.

### Tests Automatisés

#### Mise en place des tests unitaires

Dans le cadre de ce projet, une stratégie de tests automatisés a été mise en place pour garantir la qualité du code et la fiabilité des fonctionnalités. Les tests automatisés sont essentiels pour détecter rapidement les régressions et les erreurs, et pour faciliter les modifications et les améliorations du code.

Pour les tests unitaires, le framework PHPUnit a été utilisé, étant largement adopté dans le développement PHP. PHPUnit permet de créer des tests pour chaque fonction et méthode dans le code, en isolant chaque unité de code pour un test individuel. Cela permet de vérifier que chaque partie du code fonctionne comme prévu, indépendamment du reste du système.

**Exemples de tests unitaires** :

1. **Test de la méthode `modifyColonne` dans la classe `ColonneServiceTest`** :

```php
public function testModifyColonneAccessDenied(): void
{
    // Arrange
    $user = new User();
    $user->setRoles(['ROLE_USER']); // Utilisateur sans les droits nécessaires
    $colonne = new Colonne();
    $colonne->setId(1);

    $colonneService = new ColonneService();

    // Act & Assert
    $this->expectException(AccessDeniedException::class);
    $colonneService->modifyColonne($user, $colonne, 'newTitle');
}
```

Ce test vérifie que la méthode `modifyColonne` retourne une erreur d'accès refusé lorsque l'utilisateur n'a pas les droits nécessaires.

2. **Test de la méthode `createCarte` dans la classe `CarteServiceTest`** :

```php
public function testCreateCarteTitleNotProvided(): void
{
    // Arrange
    $carteService = new CarteService();

    // Act & Assert
    $this->expectException(CarteCreationException::class);
    $carteService->createCarte(1, null); // Titre non fourni
}
```

Ce test vérifie que la méthode `createCarte` retourne une erreur lorsque le titre de la carte n'est pas fourni.

Ces tests unitaires sont exécutés automatiquement à chaque modification du code, permettant ainsi de détecter rapidement toute régression ou erreur introduite.

#### Tests d'intégration et de sécurité

**Tests d'intégration** : Les tests d'intégration ont été réalisés pour valider les interactions entre composants. Ils garantissent le bon fonctionnement des différents modules de l'application ensemble. Des scénarios d'utilisation ont été testés pour s'assurer que toutes les parties de l'application fonctionnent correctement lorsqu'elles sont intégrées.

**Tests de sécurité** : Les tests de sécurité ont été effectués pour identifier et corriger les vulnérabilités potentielles dans l'application. Cela comprend les tests d'injections SQL, les tests XSS (Cross-Site Scripting) et les tests CSRF (Cross-Site Request Forgery). Des mesures correctives ont été appliquées pour renforcer la sécurité de l'application et prévenir toute exploitation malveillante de ces vulnérabilités.

### Correction des Failles de Sécurité

#### Traitement des injections SQL et XSS

Dans le cadre de l'amélioration de l'application Trello-Trollé, une attention particulière a été accordée à la sécurisation contre les injections SQL et les attaques XSS, des vulnérabilités majeures identifiées lors de l'analyse initiale du code et de l'architecture du site. Ces failles, si elles sont exploitées, peuvent gravement compromettre à la fois la sécurité des données de l'application et la confiance des utilisateurs.

**Traitement des injections SQL** :

Pour contrer les injections SQL, nous avons adopté l'utilisation systématique des requêtes préparées avec des paramètres liés, au lieu de concaténer des chaînes pour construire les requêtes SQL. Cette approche, recommandée par les bonnes pratiques de sécurité, permet d'éviter que les entrées utilisateur malveillantes soient interprétées comme du code SQL par le serveur de base de données. En outre, tous les inputs des utilisateurs sont désormais rigoureusement validés et nettoyés avant toute utilisation dans une requête SQL, afin d'assurer qu'ils ne contiennent pas de tentatives d'injection. Cela a notamment été possible grâce à l’utilisation de la class Database qui permet d'automatiser le bind des éléments a son exécution.

**Traitement des attaques XSS** :

Pour prévenir les attaques par injection de scripts (XSS), nous avons intégré l'utilisation des templates Twig dans notre stratégie de sécurisation. Twig, un moteur de template pour PHP, apporte une couche supplémentaire de protection contre les XSS grâce à son système d'échappement automatique des variables. Par défaut, Twig traite toutes les variables insérées dans les templates comme étant potentiellement dangereuses et les échappe automatiquement. Cela signifie que, à moins d'être explicitement désactivé pour un cas d'usage particulier, Twig convertira les caractères spéciaux en entités HTML, similaires à l'effet de fonctions telles que htmlspecialchars en PHP. Cette fonctionnalité réduit le risque d'exécution de scripts malveillants en s'assurant que tout contenu injecté par l'utilisateur est rendu inoffensif avant son affichage dans le navigateur. Ainsi, l'utilisation des templates Twig complète efficacement les mesures d'échappement manuel des entrées utilisateur, offrant une approche robuste et à plusieurs niveaux contre les attaques XSS.

En parallèle, nous avons renforcé les politiques de sécurité de contenu (Content Security Policy - CSP) pour restreindre les sources de scripts exécutables, empêchant ainsi l'exécution de scripts non autorisés. Cela ajoute une couche de sécurité supplémentaire en limitant les vecteurs d'attaque possibles pour les acteurs malveillants.

#### Amélioration de la gestion des sessions et des cookies

**Sécurisation des Cookies** :

Notre classe Cookie est conçue pour offrir une gestion flexible et sécurisée des cookies. Voici comment nous procédons :
- Enregistrement sécurisé des cookies : Lors de la création d'un cookie, nous n’utilisons pas serialize pour éviter les PHP Object Injection au moment de deserialize. La méthode enregistrer permet d'ajuster la durée de vie du cookie, avec la possibilité de le rendre persistant au-delà de la session courante en spécifiant une duréeExpiration. Si aucune durée n'est spécifiée, le cookie expire à la fin de la session. Elle ajoute des paramètres de sécurité comme HttpOnly, SameSite en strict, ou bien secure si le serveur utilise Https. Cela permet d’éviter des attaques CSRF notamment.
- Lecture et suppression des cookies : Nous avons également des méthodes pour lire et supprimer des cookies, facilitant la récupération des données stockées et assurant que les cookies peuvent être effacés pour des raisons de sécurité ou de confidentialité.

**Gestion Avancée des Sessions** :

La gestion des sessions dans notre application est assurée par la classe Session, qui utilise un modèle Singleton pour garantir une instance unique de la session à travers l'application.
- Initialisation et maintenance des sessions : À la création de l'instance Session, nous démarrons une session PHP si aucune n'est active. Nous utilisons les mêmes paramètres que pour la classe cookie afin de sécuriser au maximum le cookie de session.
- Gestion de l'expiration de la session : Notre système contrôle rigoureusement l'expiration des sessions en se basant sur la dernière activité de l'utilisateur. Si l'utilisateur est inactif plus longtemps que la durée spécifiée, sa session est automatiquement réinitialisée, renforçant la sécurité en limitant le risque d'exploitation de sessions abandonnées.
- Destruction sécurisée de la session : Lors de la déconnexion de l'utilisateur, la session et ses cookies associés sont complètement détruits, garantissant qu'aucune donnée résiduelle ne peut être exploitée.

En résumé, notre approche vers l'amélioration de la gestion des sessions et des cookies repose sur des principes de sécurité stricts et des pratiques de programmation avancées. En utilisant des mécanismes de sérialisation et des contrôles d'expiration intelligents, nous veillons à protéger les données des utilisateurs et à minimiser les risques de sécurité. Ces exemples de code illustrent notre engagement à maintenir une expérience utilisateur sécurisée et fiable sur “Trello-Trollé”.

### Refonte de l'Architecture du Code

#### 3.7.1 Application des principes SOLID, DRY, KISS

**Principe de responsabilité unique (SRP)** :
- La classe `TableauApiController` est un exemple respectant le SRP. Elle est responsable uniquement de la gestion des requêtes API liées à l'entité Tableau, laissant la gestion des opérations de base de données ou de la logique métier au `TableauService`.

**Principe ouvert/fermé (OCP)** :
- L'interface `I_TableauService` illustre le respect du OCP. Cette interface définit un contrat que tout `TableauService` doit suivre, permettant ainsi l'extension et la modification des implémentations sans altérer l'interface elle-même.

**Principe de substitution de Liskov (LSP)** :
- La classe `GeneriqueController` et sa classe dérivée `TableauApiController` respectent le LSP. Le `TableauApiController` peut substituer le `GeneriqueController` sans impacter le fonctionnement du programme.

**Principe de ségrégation des interfaces (ISP)** :
- L'interface `I_TableauService` respecte le ISP en fournissant un contrat spécifique pour les opérations liées à Tableau, sans forcer l'implémentation de méthodes non liées.

**Principe d'inversion des dépendances (DIP)** :
- La classe `TableauApiController` dépend de l'interface `I_TableauService` plutôt que d'une implémentation concrète, respectant ainsi le DIP.

**Principe DRY (Don't Repeat Yourself)** :
- Utilisation d'interfaces pour définir des méthodes communes à plusieurs classes, regroupant ainsi les méthodes communes dans une interface.
- Centralisation de la logique de l'application dans le fichier `conteneur.yaml`, évitant ainsi la duplication de code.
- Réutilisation de la méthode `toSnakeCase` de la classe `AttributeRouteControllerLoader` pour convertir les noms de contrôleurs en snake_case, évitant ainsi la répétition de code.

**Principe KISS (Keep It Simple, Stupid)** :
- Utilisation d'interfaces pour définir les méthodes des repositories, simplifiant ainsi la compréhension de chaque repository.
- Injection de dépendances dans le fichier de configuration `conteneur.yaml`, simplifiant ainsi la gestion des dépendances et rendant le code plus facile à tester et à maintenir.
- Utilisation de la classe `AttributeRouteControllerLoader` pour la configuration des routes, évitant ainsi la configuration manuelle de chaque route dans chaque contrôleur.
- Organisation du code en différentes couches (contrôleurs, services, repositories), rendant ainsi le code plus facile à comprendre et à maintenir.

#### 3.7.2 Documentation et Nomination

Dans le cadre de notre projet de refonte et d'amélioration du projet "Trello-Trollé", une attention particulière a été portée à la documentation et au nommage du code. Initialement confrontés à un code peu documenté et à des conventions de nommage incohérentes, nous avons rapidement réalisé l'importance de clarifier et standardiser notre base de code pour améliorer sa maintenabilité et sa compréhensibilité, tant pour notre équipe actuelle que pour les futurs contributeurs.

Pour améliorer la documentation, nous avons systématiquement intégré des commentaires explicatifs et des résumés à travers notre code. Chaque fonction, méthode, et classe a été accompagnée de descriptions claires détaillant leurs objectifs, paramètres, et types de retour. Cela a permis de fournir un aperçu immédiat de la logique du code sans nécessiter une exploration approfondie du code lui-même. En outre, des blocs de documentation plus étendus ont été ajoutés pour les composants cruciaux de l'application, offrant une vue d'ensemble de leur fonctionnement interne et de leurs interactions au sein du système.

Parallèlement à l'enrichissement de la documentation, une refonte complète du nommage a été entreprise pour éliminer l'ambiguïté et aligner notre code sur les conventions de développement reconnues. En adoptant des conventions de nommage standardisées, telles que l'utilisation du camelCase pour les variables et méthodes et du PascalCase pour les classes, et en renommant les éléments de manière à refléter fidèlement leur rôle ou contenu, nous avons grandement amélioré la lisibilité du code. Les cas où le nom ne suffisait pas à lui seul pour expliquer l'usage ou le rôle d'un élément ont été complétés par des commentaires descriptifs, fournissant ainsi le contexte nécessaire pour une compréhension approfondie.

Ces efforts dédiés à la documentation et au nommage ont transformé notre base de code, la rendant plus accessible et facile à comprendre pour l'équipe de développement actuelle. En posant ces bases solides, nous avons non seulement amélioré l'état immédiat de notre code mais avons également facilité sa maintenance future et son évolution, assurant ainsi que le projet Trello-Trollé reste un modèle de collaboration et d'innovation ouverte.

### Virtualisation et Conteneurisation de l'Application

#### 3.8.1 Conteneurisation de l'Application Web

Pour la conteneurisation de l'application web, nous avons suivi les étapes suivantes :

- **Choix de l'image de base** : Nous avons opté pour l'image officielle php:8.3-cli, adaptée à notre stack technologique basée sur PHP.

- **Installation de NodeJs** : NodeJs a été installé pour la compilation de notre CSS via Tailwind CSS et Webpack, garantissant ainsi une optimisation des fichiers CSS pour la production.

- **Gestion des dépendances avec Composer** : Nous avons automatisé la gestion des dépendances PHP en installant Composer au sein du conteneur, ce qui simplifie l'installation de l'environnement de travail pour tout développeur rejoignant le projet.

- **Configuration du Dockerfile** : Le Dockerfile a été configuré pour exposer le port 8000, sur lequel le serveur PHP intégré est lancé, permettant ainsi une accessibilité directe à l'application via le navigateur.

#### 3.8.2 Conteneurisation de la Base de Données

Pour la conteneurisation de la base de données PostgreSQL, nous avons suivi les étapes suivantes :

- **Isolation des processus et sécurité des données** : La base de données PostgreSQL a été conteneurisée pour tourner en parallèle avec notre application web, assurant ainsi une isolation des processus et une meilleure sécurité des données.

#### 3.8.3 Docker Compose

L'orchestration des services web et de la base de données a été gérée à l'aide d'un fichier docker-compose.yml, incluant les actions suivantes :

- **Initialisation de la base de données** : Un script SQL (export.sql) est exécuté pour créer la structure de la base de données et insérer des données initiales, telles que le compte administrateur.

- **Construction de l'image de l'application web** : Si l'image de l'application web n'existe pas déjà, elle est construite, incluant l'installation des dépendances PHP via Composer et la compilation des assets avec NodeJs.

- **Lancement des services** : Les services sont lancés avec les variables d'environnement appropriées, permettant de paramétrer l'application en fonction de l'environnement de production sans modifier le code source.
