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

Le dossier `backend` provient du squelette officiel Laravel 12. Il inclut les modèles, migrations, API de catalogue et ressources Filament pour gérer organismes et spécialités. Il ne contient pas encore de moteur serveur de questions, de comptes candidats ou de suivi durable des résultats.

L'exécution PHP a été refusée sur la machine de préparation : les dépendances Composer, migrations, tests PHP et interface Filament restent à installer et vérifier sur une machine disposant de PHP fonctionnel. Ne pas considérer le backend comme validé pour la production.

```sh
cd backend
composer install
cp .env.example .env
php artisan key:generate
# Créer la base MySQL tahdir_mobarayat puis renseigner DB_* dans .env.
php artisan migrate
php artisan make:filament-user
php artisan serve
```

Après création du compte, un opérateur habilité doit lui attribuer `is_admin=true` et une date `email_verified_at` dans la base. Aucun compte ni mot de passe par défaut n'est livré. L'accès Filament exige ces deux attributs, y compris en local. Seuls les administrateurs habilités accèdent à `/admin`.

Créer les organismes et spécialités dans Filament et les activer. Les slugs identifient les routes ; les noms des logos correspondent à des fichiers dans `frontend/public/images` (par exemple `dgapr.png`). Les organismes inactifs et spécialités inactives ne sortent pas dans l'API.

`GET /api/organizations` fournit le catalogue. Pour connecter l'interface, servir API et interface sur le même domaine, définir `VITE_CATALOG_API=/api/organizations` dans `frontend/.env` et recompiler. Sans cette variable, l'interface utilise le catalogue de démonstration. Les exemples de questions restent locaux : connecter le catalogue n'active pas un moteur de questions serveur.

## Vérification

Compilation Vite réussie et contrôles navigateur : recherche « العدل », navigation vers DGAPR et son exemple de spécialité, chronomètre actif, correction du score après remise. Vérification des largeurs mobile 390px et bureau. Les contrôles PHP restent à exécuter.

## Publication

Le dépôt doit être indépendant de Lampharma. Aucun hébergement de production n'est configuré dans cette livraison. Les fichiers `.env`, dépendances et compilations sont ignorés par Git. Un fichier `composer.lock` devra être généré et versionné après résolution des dépendances sur la machine PHP.
