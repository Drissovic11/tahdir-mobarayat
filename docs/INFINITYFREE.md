# Installation sur InfinityFree

Compte sélectionné : `if0_43322517`, site `https://qcmcouncours.site.je`.
Base créée : `if0_43322517_tahdir`, hôte MySQL `sql307.infinityfree.com`.

Le compte a été vérifié avec PHP 8.4 et les extensions PDO MySQL, mbstring, intl, fileinfo, OpenSSL, bcmath, GD, ZIP, DOM et XML. Les tests automatisés utilisent PHP 8.2 et MySQL 8.

Le paquet `infinityfree-installation` est produit par GitHub Actions après réussite des tests. Il inclut les dépendances Composer et les fichiers publics de Filament ; il ne contient aucun secret. Le site Vue est compilé avec `VITE_CATALOG_API=/api/organizations` et `VITE_CONTENT_API=/api`.

## Organisation des fichiers

Les fichiers publics se placent dans `htdocs`. Laravel et ses dépendances se trouvent dans `htdocs/_app`, protégé par deux règles Apache : refus au niveau de `htdocs/.htaccess` et refus de tout accès dans `_app/.htaccess`. Vérifier que `/_app/.env` est inaccessible avant de configurer les secrets. Le contrôleur d’entrée définit explicitement le répertoire public pour Filament et les logos.

## Configuration et première installation

1. Depuis le paquet décompressé, exécuter `node PREPARER-LES-CLES.mjs`. Une clé applicative et un jeton d’installation propres à cette installation sont écrits dans `htdocs/_app/.env`, sans être affichés.
2. Renseigner les valeurs MySQL du compte dans ce fichier privé. Le mot de passe est celui du compte d’hébergement, pas celui de connexion au tableau de bord InfinityFree. Ne pas versionner `.env`.
3. Téléverser les fichiers dans le dossier du domaine. Le téléversement FTP est préférable aux grandes archives du gestionnaire web. Ne pas remplacer le `.htaccess` de la racine du compte ; celui de notre application est dans `htdocs`.
4. Ouvrir `/installation`. Saisir le jeton présent dans `.env`, puis le nom, l’adresse e-mail et le mot de passe du premier administrateur. L’installation exécute les migrations et crée uniquement les trois organismes de départ, sans grades ou questions officiels inventés.
5. Se connecter à `/admin`, puis désactiver `INSTALLATION_ENABLED` dans `.env`. Un verrou serveur et la présence d’un administrateur empêchent déjà la répétition de l’installation.

Dans l’administration, créer les spécialités, les questions (quatre choix, bonne réponse et explication), puis les examens. L’activation des organismes/spécialités/questions et la publication des examens contrôlent leur visibilité. Les résultats d’examens sont consultables en lecture seule.

La version Sites utilise les données de démonstration. InfinityFree bloque les appels API inter-domaines ; la version reliée à MySQL sert donc le front et le backend sous le même domaine. Référence : https://forum.infinityfree.com/t/browser-security-system-features-and-limitations/49353

## Fonctionnement des examens

Les bonnes réponses ne sont pas envoyées au démarrage d’un examen. Le serveur conserve un instantané des questions, une échéance et les réponses enregistrées au fil de l’examen. Une soumission tardive ne peut pas modifier les réponses sauvegardées à temps. Une seconde soumission ne change pas le score. Le mode entraînement expose volontairement les corrections pour l’apprentissage.

Aucun compte candidat ni donnée personnelle de candidat n’est collecté. Le jeton d’une tentative reste en mémoire dans le navigateur ; une actualisation ne reprend pas automatiquement l’examen.
