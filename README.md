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

Renforcement de la sécurité : Avec des techniques avancées de hashage des mots de passe, la mise en œuvre de requêtes préparées.

Ajout de nouvelles fonctionnalités et amélioration du design du site : Pour enrichir l'expérience utilisateur et rendre le site plus attrayant visuellement.


Remplacement des fichiers JSON par des relations de table
**Avantages** :
  - Réduction de la redondance des données.
  - Simplification des accès et des manipulations des données.
  - Amélioration de la sécurité et de la scalabilité de l'application.
- **Suppression des mots de passe en clair** : Élimination des colonnes stockant les mots de passe lisibles.
- **Hachage des mots de passe** : Utilisation de fonctions de hachage cryptographique avancées avec un sel unique pour rendre les mots de passe indéchiffrables.
- **Avantages** :
  - Réduction du risque de fuites de données.
  - Renforcement de la confiance des utilisateurs dans la sécurité de la plateforme.
  - Conformité avec les réglementations sur la protection des données, comme le RGPD.

- **Refonte du design avec Tailwind CSS** : Nous avons revu le design et l'interface du site en utilisant Tailwind CSS, ce qui a considérablement amélioré l'esthétique et l'ergonomie de la plateforme, la rendant plus intuitive et agréable à utiliser.

- **Implémentation de composants Popups** : Les Popups permettent aux utilisateurs d'effectuer toutes les actions liées aux tableaux sans quitter la page principale. Par exemple, en cliquant sur une carte, une popup s'affiche, offrant la possibilité de modifier directement son titre, sa description et sa couleur.

- **Fonctionnalité de glisser-déposer** : Les utilisateurs peuvent désormais déplacer les cartes d'une colonne à l'autre facilement. Si une carte est déposée sur le bouton "nouvelle colonne", une popup s'ouvre, permettant à l'utilisateur de créer une nouvelle colonne et d'y attribuer la carte.

- **Interface épurée et réactive** : Des éléments interactifs apparaissent lorsque l'utilisateur survole une colonne, tels qu'un bouton pour créer une nouvelle carte ou pour modifier la colonne. De plus, les popups réactives sont très intuitives d’utilisation.

- **Gestion améliorée des tableaux** : La page de la liste des tableaux de l'utilisateur a été restructurée pour plus de praticité, avec l'ajout d'un bouton "plus" pour créer rapidement un nouveau tableau et des fonctionnalités telles que la suppression d'un tableau désormais facilement accessibles.

- **Authentification basée sur les jetons JWT** : Nous avons adopté un système basé sur les jetons JWT pour vérifier l'identité des utilisateurs lors de leur connexion et sécuriser les échanges ultérieurs avec l'application.

- **Système de routage basé sur Symfony** : Le système de routage assure que chaque requête utilisateur est correctement dirigée vers la fonctionnalité appropriée, facilitant ainsi une expérience utilisateur fluide et intuitive.

**Traitement des injections SQL** :

Pour contrer les injections SQL, nous avons adopté l'utilisation systématique des requêtes préparées avec des paramètres liés, au lieu de concaténer des chaînes pour construire les requêtes SQL. Cette approche, recommandée par les bonnes pratiques de sécurité, permet d'éviter que les entrées utilisateur malveillantes soient interprétées comme du code SQL par le serveur de base de données. En outre, tous les inputs des utilisateurs sont désormais rigoureusement validés et nettoyés avant toute utilisation dans une requête SQL, afin d'assurer qu'ils ne contiennent pas de tentatives d'injection. Cela a notamment été possible grâce à l’utilisation de la class Database qui permet d'automatiser le bind des éléments a son exécution.

**Traitement des attaques XSS** :

Pour prévenir les attaques par injection de scripts (XSS), nous avons intégré l'utilisation des templates Twig dans notre stratégie de sécurisation. Twig, un moteur de template pour PHP, apporte une couche supplémentaire de protection contre les XSS grâce à son système d'échappement automatique des variables. Par défaut, Twig traite toutes les variables insérées dans les templates comme étant potentiellement dangereuses et les échappe automatiquement. Cela signifie que, à moins d'être explicitement désactivé pour un cas d'usage particulier, Twig convertira les caractères spéciaux en entités HTML, similaires à l'effet de fonctions telles que htmlspecialchars en PHP. Cette fonctionnalité réduit le risque d'exécution de scripts malveillants en s'assurant que tout contenu injecté par l'utilisateur est rendu inoffensif avant son affichage dans le navigateur. Ainsi, l'utilisation des templates Twig complète efficacement les mesures d'échappement manuel des entrées utilisateur, offrant une approche robuste et à plusieurs niveaux contre les attaques XSS.

En parallèle, nous avons renforcé les politiques de sécurité de contenu (Content Security Policy - CSP) pour restreindre les sources de scripts exécutables, empêchant ainsi l'exécution de scripts non autorisés. Cela ajoute une couche de sécurité supplémentaire en limitant les vecteurs d'attaque possibles pour les acteurs malveillants.

**Sécurisation des Cookies** :
Notre classe Cookie est conçue pour offrir une gestion flexible et sécurisée des cookies. Voici comment nous procédons :
- Enregistrement sécurisé des cookies : Lors de la création d'un cookie, nous n’utilisons pas serialize pour éviter les PHP Object Injection au moment de deserialize. La méthode enregistrer permet d'ajuster la durée de vie du cookie, avec la possibilité de le rendre persistant au-delà de la session courante en spécifiant une duréeExpiration. Si aucune durée n'est spécifiée, le cookie expire à la fin de la session. Elle ajoute des paramètres de sécurité comme HttpOnly, SameSite en strict, ou bien secure si le serveur utilise Https. Cela permet d’éviter des attaques CSRF notamment.
- Lecture et suppression des cookies : Nous avons également des méthodes pour lire et supprimer des cookies, facilitant la récupération des données stockées et assurant que les cookies peuvent être effacés pour des raisons de sécurité ou de confidentialité.

**Gestion Avancée des Sessions** :
La gestion des sessions dans notre application est assurée par la classe Session, qui utilise un modèle Singleton pour garantir une instance unique de la session à travers l'application.
- Initialisation et maintenance des sessions : À la création de l'instance Session, nous démarrons une session PHP si aucune n'est active. Nous utilisons les mêmes paramètres que pour la classe cookie afin de sécuriser au maximum le cookie de session.
- Gestion de l'expiration de la session : Notre système contrôle rigoureusement l'expiration des sessions en se basant sur la dernière activité de l'utilisateur. Si l'utilisateur est inactif plus longtemps que la durée spécifiée, sa session est automatiquement réinitialisée, renforçant la sécurité en limitant le risque d'exploitation de sessions abandonnées.
- Destruction sécurisée de la session : Lors de la déconnexion de l'utilisateur, la session et ses cookies associés sont complètement détruits, garantissant qu'aucune donnée résiduelle ne peut être exploitée.

**Lancement de l'application** :

Pour lancer le projet avec docker compose, il suffit de cloner le dépôt GitHub, de se placer dans le répertoire du projet et d'exécuter la commande `docker-compose up`. Cela lancera les conteneurs Docker nécessaires pour exécuter l'application, y compris le serveur web, la base de données PostgreSQL et l'interface utilisateur. Une fois les conteneurs démarrés, l'application sera accessible à l'adresse `http://localhost:8080` dans un navigateur web.
Il faut avoir égelement le .env.prod dans le dossier a la racine du projet. ce fichier est dans le rapport.

Pour lancer le projet sans docker en local il faut exécuter les commandes suivantes :
npm i ( ceci installe les dépendances de node)
composer install ( ceci installe les dépendances de php)
npm run all ( ceci compile les fichiers css et js et le lance en localhost:8000)


**adresse du site** : https://sae-s4-nameless-smoke-5892.fly.dev/

