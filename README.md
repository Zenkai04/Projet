# Projet LyonEats

LyonEats est une application web permettant aux utilisateurs de rechercher, consulter et réserver des restaurants à Lyon. L'application offre également des fonctionnalités d'administration pour gérer les utilisateurs et les restaurants.

## Fonctionnalités

### **1. Accès et authentification**
- Inscription et connexion des utilisateurs.
- Tous les utilisateurs enregistrés ont le rôle **utilisateur**.
- Un administrateur dispose d'identifiants spéciaux avec des privilèges supplémentaires.

### **2. Recherche et visualisation des restaurants**
- **Carte interactive** affichant les restaurants sous forme de marqueurs géolocalisés.
- **Filtres de recherche** :
  - Nom du restaurant.
  - Type de cuisine.
  - Gamme de prix.
  - Notation des utilisateurs.
- **Affichage des détails d’un restaurant** :
  - Adresse, horaires d'ouverture, numéro de téléphone.
  - Menu et photos.
  - Notes et avis des utilisateurs.

### **3. Avis et commentaires**
- Laisser une **note et un avis** sur un restaurant.
- Répondre aux avis existants via un **système de commentaires imbriqués**.

### **4. Réservation**
- Réserver une table en indiquant :
  - **La date**.
  - **Le nombre de personnes**.
- Suivi des **réservations effectuées** via un tableau de bord personnel.

### **5. Tableau de bord utilisateur**
- Consultation des **avis postés** par l'utilisateur.
- Historique des **réservations effectuées**.

### **6. Espace administrateur**
- Accès aux mêmes fonctionnalités qu'un utilisateur standard.
- Deux onglets supplémentaires pour la **gestion des utilisateurs et des restaurants** :
  - **Gestion des utilisateurs** : afficher, modifier ou supprimer les utilisateurs inscrits.
  - **Gestion des restaurants** : afficher, modifier ou supprimer les restaurants listés.

### **7. Pages disponibles sur le site**
- **Page d'accueil** : affiche les **5 meilleurs restaurants** selon la notation.
- **Page de recherche** : carte interactive pour localiser et filtrer les restaurants.
- **Page de contact** : formulaire de contact pour contacter les propriétaires de la plateforme.

---

## Technologies utilisées

### **Backend**
- PHP
- Base de données MySQL

### **Frontend**
- CSS, JavaScript, Bootstrap
- Templates Twig

### **Cartographie**
- Leaflet avec OpenStreetMap

### **Sécurité**
- Hashage des mots de passe.

---

## Installation

1. Clonez le dépôt :
   ```bash
   git clone https://github.com/votre-utilisateur/LyonEats.git
   ```
2. Installez les dépendances :
   ```bash
   composer install
   ```
3. Configurez la base de données dans le fichier `.sql`.

---

## Problèmes rencontrés et solutions

| Problème | Solution |
|----------|----------|
| **Données API incomplètes** (absence d'adresses) | Développement d'un **script PHP** pour récupérer les positions et les associer aux adresses. |
| **Temps de récupération des données trop long** | Stockage des **données en local** pour éviter les requêtes répétitives à l'API. |
| **Affichage des commentaires imbriqués** | Création d'un **template spécifique** pour gérer l'affichage des réponses. |
| **Manque d'informations sur les prix** | Attribution de **prix arbitraires** en fonction du nom et de l'adresse des restaurants. |

---
