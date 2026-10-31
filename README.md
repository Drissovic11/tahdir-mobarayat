# التحضير لمباريات التوظيف

Projet indépendant : interface arabe RTL mobile first de préparation aux concours publics marocains.

## Technologies reprises de Lampharma

- Interface : Vue 3, Vite, CSS personnalisé, GSAP, Lucide.
- Socle serveur : Laravel 12, PHP 8.2+, MySQL, Filament 5.
- Aucun contenu métier, compte client ou secret de Lampharma n'est repris.

## Interface

```sh
cd frontend
npm ci
npm run dev
```

Ouvrir http://127.0.0.1:5173. `npm run build` produit `frontend/dist` ; `npm run preview` sert la compilation.

Accueil intégré avec le nom définitif, la photographie choisie, recherche arabe/latine par organisme, logos, pages de spécialités, guide, FAQ et menu mobile. Le parcours DGAPR contient un exemple pédagogique de cinq questions, avec correction expliquée ou simulation de cinq minutes. Le score inclut les questions non répondues. Le chronomètre utilise une échéance absolue et tient compte du temps passé dans un onglet masqué. Les réponses restent en mémoire pendant la visite ; aucun compte candidat n'est encore implémenté.

La spécialité DGAPR est un exemple fourni pendant la conception, à valider et compléter. Les rubriques Sécurité nationale et Justice affichent un état vide plutôt que des grades inventés. Les cinq questions sont générales et non officielles. L'indexation est bloquée pendant la démonstration.

## Serveur et administration

L'administration Laravel/Filament gère organismes, logos, spécialités, questions avec quatre choix et explication, examens et consultation des résultats. MySQL conserve les tentatives, les questions figées au démarrage, les réponses sauvegardées et les scores calculés côté serveur. Aucun compte candidat n'est requis.

PHP est indisponible localement. Les dépendances, migrations et tests sont vérifiés par GitHub Actions avec PHP 8.2 et MySQL 8. Le compte InfinityFree dispose de PHP 8.4 et des extensions nécessaires. Le paquet complet est produit par la CI ; voir [le guide InfinityFree](docs/INFINITYFREE.md).

```sh
cd backend
composer install
cp .env.example .env
php artisan key:generate
# Créer la base MySQL tahdir_mobarayat puis renseigner DB_* dans .env.
php artisan migrate
php artisan admin:create
php artisan serve
```

La commande demande le nom, l'adresse et un mot de passe confirmé de 12 caractères minimum et crée un administrateur habilité. Aucun compte ni mot de passe par défaut n'est livré. Sur InfinityFree, l'installation initiale par navigateur remplace la ligne de commande.

Créer les organismes et spécialités dans Filament et les activer. Les slugs identifient les routes ; les noms des logos correspondent à des fichiers dans `frontend/public/images` (par exemple `dgapr.png`). Les organismes inactifs et spécialités inactives ne sortent pas dans l'API.

Pour connecter l'interface complète, servir API et interface sur le même domaine, définir `VITE_CATALOG_API=/api/organizations` et `VITE_CONTENT_API=/api` puis recompiler. Sans ces variables, le site utilise ses données de démonstration. Le mode connecté charge les contenus publiés depuis MySQL ; les corrections d'un examen sont renvoyées après sa remise.

## Vérification

Compilation Vite et contrôles navigateur du mode démonstration réussis. Les tests PHP/MySQL dans GitHub Actions couvrent l'accès administrateur, l'édition des questions, la confidentialité des corrections avant remise, les scores immuables, l'échéance, les réponses sauvegardées, le catalogue actif et l'installation initiale protégée.

## Publication

Le dépôt est indépendant de Lampharma. La démonstration est publiée sur Sites ; l'installation MySQL cible InfinityFree. Les fichiers `.env`, dépendances et compilations sont ignorés par Git. `composer.lock` fixe les dépendances validées. Le guide précise les étapes de configuration et de création du premier compte administrateur.
