# Handoff : Page « Projets » — Portfolio 2026

## Vue d'ensemble
Page listant l'ensemble des projets réalisés pendant les études (web, mobile, branding, game dev).
Elle se compose d'un **header de navigation**, d'un **hero plein-cadre** avec photo de bureau,
d'une **phrase d'intro**, puis d'une **galerie de cartes projets** (style éditorial inspiré de la
landing « Moona ») et d'une **rangée de 3 highlights**. Chaque carte renvoie vers une fiche projet
détaillée (`Projet.html?p=<slug>`).

---

## À propos des fichiers de cette archive
Les fichiers fournis (`Projets.html`, `image-slot.js`) sont des **références de design réalisées en HTML/CSS/JS**
— un prototype montrant l'apparence et le comportement souhaités, **pas du code de production à copier tel quel**.

La tâche : **recréer ce design dans l'environnement existant du projet cible** (React, Vue, Svelte, Astro,
ou simple HTML/CSS selon le portfolio), en suivant ses conventions (composants, système de styles, router).
Si aucun environnement n'existe encore, choisir le framework le plus adapté à un portfolio statique
(ex. **Astro**, **Next.js**, ou **HTML/CSS/JS vanilla** si on veut rester simple) et y implémenter le design.

> ⚠️ `image-slot.js` est un composant de prototypage (drop d'image persistant dans cet outil).
> **Ne pas le porter en production.** Le remplacer par de vraies balises `<img>` avec les photos du portfolio.

---

## Fidélité
**Haute-fidélité (hifi).** Couleurs, typographies, espacements, rayons et interactions sont définitifs.
Recréer l'UI au pixel près en réutilisant les libs/patterns du codebase cible.

---

## Écrans / Vues

### Écran unique : « Page Projets »
**But** : permettre au visiteur de parcourir tous les projets et d'accéder à chaque fiche détaillée.

**Layout global**
- Conteneur centré, `max-width: 1180px`, padding horizontal `40px` (→ `20px` en mobile).
- Empilement vertical : `NAV` (sticky) → `HERO` → `INTRO` → `PANEL projets`.

---

### 1. Barre de navigation (`<nav>`)
- **Sticky** en haut (`position: sticky; top: 0; z-index: 50`).
- Fond `rgba(255,255,255,0.82)` + `backdrop-filter: blur(12px)`, bordure basse `1px solid #ececea`.
- Hauteur `70px`, contenu en `flex` justifié `space-between`, `max-width: 1180px`.
- **Gauche — Logo** : texte « Portfolio » `font-weight: 800`, `font-size: 1.15rem`, `letter-spacing: -0.02em`.
  Le point « . » final est en orange `#F97316`.
- **Centre — Liens** : `flex`, `gap: 30px`, `font-size: 0.88rem`, `font-weight: 500`, couleur `#8a8580`.
  - Liens : `About me`, `Compétences`, `Projets`, `Expériences professionnelles`.
  - Hover → couleur `#1d1b1a`.
  - Lien **actif** (`Projets`) : couleur `#1d1b1a`, `font-weight: 600`, **soulignement** orange
    (barre `2px`, `border-radius: 2px`, `background: #F97316`) calée sur la bordure basse de la nav.
- **Droite — Bouton « Contact »** : pill orange.
  - `background: #F97316`, texte blanc, `font-size: 0.85rem`, `font-weight: 600`,
    `padding: 10px 20px`, `border-radius: 100px`.
  - Hover → `background: #ea6a0a`, `translateY(-1px)`, `box-shadow: 0 8px 20px rgba(249,115,22,0.3)`.

---

### 2. Hero (`.hero` / `.hero-frame`)
- Cadre plein-largeur, `aspect-ratio: 21 / 8`, `border-radius: 22px`, `overflow: hidden`.
- Padding haut de section : `36px`.
- **Image** : photo de bureau (top-down : clavier, plante, carnet, crayon) en `object-fit: cover`.
  → En production : `<img>` plein cadre. (Dans le proto c'est un `<image-slot>`.)
- **Overlay texte** :
  - Dégradé bas→haut : `linear-gradient(to top, rgba(20,16,14,0.62) 0%, rgba(20,16,14,0.15) 40%, transparent 70%)`.
  - Texte aligné en bas-gauche, padding `46px 50px` (→ `28px` mobile).
  - Titre `<h1>` en **serif** : `Newsreader`, `font-weight: 500`, `font-size: clamp(1.8rem, 4vw, 3.1rem)`,
    `line-height: 1.08`, `letter-spacing: -0.01em`, couleur blanche, `max-width: 560px`.
  - Contenu : « Tout commence par une idée<br>et un bon bureau ».

---

### 3. Phrase d'intro (`.intro`)
- Bloc centré, `max-width: 720px`, padding `64px 0 20px`.
- Texte serif `Newsreader`, `font-size: clamp(1.4rem, 2.8vw, 2rem)`, `font-weight: 400`, `line-height: 1.35`, couleur `#1d1b1a`.
- Les mots **web** et **game développement** sont en `<em>` : *italique* + couleur orange `#F97316`.
- Contenu : « Ici, voyagez en un clic du monde du *web* au *game développement* avec Unity. »

---

### 4. Panneau projets (`.panel-section` > `.panel`)
- Section padding `40px 0 90px`.
- **Panneau** : fond crème `#FBF3EC`, `border-radius: 30px`, padding `60px 56px 56px` (→ `44px 28px 40px` en ≤900px).

**4a. En-tête du panneau (`.panel-head`)** — centré, `max-width: 620px`, `gap: 20px` :
- **Pill** « Mes projets » : fond blanc, texte orange `#F97316`, `font-size: 0.7rem`, `font-weight: 700`,
  `letter-spacing: 0.14em`, `text-transform: uppercase`, `padding: 7px 15px`, `border-radius: 100px`,
  `box-shadow: 0 2px 8px rgba(0,0,0,0.04)`. Petit point `6px` orange à gauche.
- **Titre `<h2>`** serif : `Newsreader`, `font-weight: 600`, `font-size: clamp(2rem, 4.2vw, 3rem)`,
  `line-height: 1.05`, `letter-spacing: -0.015em`.
  Contenu : « Découvrez ce que j'ai créé pendant mes études ».
- **Sous-titre `<p>`** : `font-size: 0.98rem`, couleur `#8a8580`, `line-height: 1.6`, `max-width: 480px`.
  Contenu : « Du premier croquis au produit final — une sélection de projets web, mobile et game design menés du concept au code. »

**4b. Grille (`.grid`)**
- `display: grid`, `grid-template-columns: repeat(3, 1fr)`, `gap: 22px`.
- Responsive : `repeat(2,1fr)` en ≤900px, `1fr` en ≤620px.
- Une **carte projet** par item (voir composant ci-dessous).

**4c. Highlights (`.highlights`)**
- `margin-top: 26px`, fond blanc, `border-radius: 18px`, padding `8px`.
- `display: grid; grid-template-columns: repeat(3,1fr); gap: 8px` (→ `1fr` en ≤900px).
- Chaque item (`.hl`) : `flex`, `gap: 14px`, padding `20px 22px`, `border-radius: 12px`, hover `background: #fcfbfa`.
  - **Icône** : carré `44px`, `border-radius: 12px`, fond pastel, icône `21px` (stroke `1.9`).
    - Item 1 — fond `#fdeede`, stroke `#e07314` (icône dossier). Titre « 6 projets réalisés » / « en autonomie & en équipe ».
    - Item 2 — fond `#e6f5ec`, stroke `#1f9d57` (icône check-circle). Titre « Du concept au code » / « design, intégration, dev ».
    - Item 3 — fond `#e8f0fe`, stroke `#3b6fd4` (icône GitHub). Titre « Code sur GitHub » / « projets open & documentés ».
  - **Titre** (`.ht`) : `font-size: 0.92rem`, `font-weight: 700`. **Sous-titre** (`.hs`) : `0.78rem`, couleur `#8a8580`.

---

## Composant : Carte projet (`.card`)

**Conteneur**
- Fond blanc, `border-radius: 18px`, padding `14px`, `box-shadow: 0 1px 3px rgba(0,0,0,0.04)`.
- `display: flex; flex-direction: column`.
- **Hover** : `translateY(-5px)`, `box-shadow: 0 18px 44px rgba(0,0,0,0.1)`.
  Transition : `transform .3s cubic-bezier(0.23,1,0.32,1), box-shadow .3s ease`.

**Zone image (`.shot`)**
- `aspect-ratio: 4 / 3`, `border-radius: 12px`, `overflow: hidden`, `position: relative`.
- En production : `<img>` `object-fit: cover`. (Proto = `<image-slot>`.)
- **Badge numéro (`.num`)** : haut-gauche (`top/left: 10px`), `font-size: 0.68rem`, `font-weight: 700`,
  couleur `#1d1b1a`, fond `rgba(255,255,255,0.92)` + blur, `padding: 4px 9px`, `border-radius: 100px`.
  Contenu = numéro d'ordre `01`, `02`, … (zéro-paddé).
- **Bouton GitHub (`.gh`)** : haut-droite (`top/right: ~9px`), rond `32px`, fond `rgba(255,255,255,0.92)` + blur,
  icône GitHub `16px` remplie `#1d1b1a`. Hover → fond `#1d1b1a`, icône blanche. `target="_blank"`.
  ⚠️ Le clic GitHub ne doit PAS déclencher la navigation de la carte (`stopPropagation`).

**Corps (`.body`)** — c'est le **lien** vers la fiche (`<a href="Projet.html?p=<slug>">`), padding `16px 8px 8px` :
- **Titre `<h3>`** serif : `Newsreader`, `font-weight: 600`, `font-size: 1.24rem`, `letter-spacing: -0.01em`,
  `line-height: 1.15`, `margin-bottom: 10px`. Hover carte → couleur `#F97316`.
- **Ligne meta (`.meta-row`)** : `flex`, `gap: 10px`, `margin-bottom: 14px`.
  - **Badge catégorie (`.tag`)** : `font-size: 0.68rem`, `font-weight: 700`, `padding: 4px 10px`, `border-radius: 100px`.
    Couleurs selon catégorie (voir système de tags pastel ci-dessous).
  - **Dates (`.sub`)** : `font-size: 0.8rem`, `font-weight: 500`, couleur `#8a8580`. Format « Sept. 2024 → Janv. 2025 ».
- **Pied (`.foot`)** : `flex`, `space-between`, `padding-top: 12px`, `border-top: 1px solid #ececea`, `margin-top: auto`.
  - **Difficulté (`.difficulty`)** : label (`.dlab`, `0.68rem`, `700`, `uppercase`, `#8a8580`) + **5 points** (`.dots`).
    Point `6px` rond : éteint `#ddd9d4`, allumé `#F97316`. Nombre de points allumés = niveau (1–5).
  - **CTA « Voir la fiche »** (`.see`) : `font-size: 0.78rem`, `font-weight: 600`, couleur `#1d1b1a` + flèche `→`.
    Hover carte → couleur `#F97316`, `gap` passe de `5px` à `8px` (la flèche s'écarte).

---

## Données projets (modèle)

Tableau JS pilotant le rendu. Reproduire la même structure de données dans le codebase cible.

| slug | title | cat (badge) | color | start | end | difficulty | github |
|---|---|---|---|---|---|---|---|
| dashboard-analytics | Dashboard Analytics | Web App | blue | Sept. 2024 | Janv. 2025 | 4 | (lien) |
| app-bien-etre | App Bien-être | Mobile | green | Févr. 2024 | Juin 2024 | 3 | (lien) |
| identite-marque | Identité de marque | Branding | pink | Oct. 2023 | Déc. 2023 | 2 | (lien) |
| landing-ecommerce | Landing E-commerce | Web | orange | Mars 2025 | Mai 2025 | 3 | (lien) |
| design-system | Design System | UI Kit | purple | Janv. 2023 | Avril 2023 | 5 | (lien) |
| jeu-unity | Jeu Unity 2D | Game Dev | orange | Sept. 2025 | Déc. 2025 | 5 | (lien) |

**Labels de difficulté** : 1–2 = « Facile », 3 = « Intermédiaire », 4 = « Avancé », 5 = « Expert ».
Chaque carte lie vers `Projet.html?p=<slug>` (à adapter en route du framework, ex. `/projets/<slug>`).

---

## Interactions & comportement
- **Nav sticky** : reste collée en haut au scroll, fond translucide + blur.
- **Hover carte** : élévation (translateY -5px) + ombre portée + titre & CTA passent en orange + flèche s'écarte.
- **Hover bouton GitHub** : inversion fond noir / icône blanche. Ouvre le repo dans un nouvel onglet, sans naviguer vers la fiche.
- **Hover bouton Contact** : assombrit + légère élévation + ombre orange.
- **Hover liens nav** : passage gris → encre.
- Toutes les transitions : `0.2s`–`0.3s`, easing `ease` ou `cubic-bezier(0.23,1,0.32,1)`.
- **Responsive** : grille 3 → 2 → 1 colonne (breakpoints 900px / 620px) ; liens nav masqués < 620px (prévoir un menu burger en production) ; highlights 3 → 1 colonne.

## State management
Page essentiellement statique. Seul « état » = la **liste des projets** (données ci-dessus), à charger
depuis un fichier de données / CMS / front-matter. Pas de fetch ni de state runtime requis.

---

## Design tokens

**Couleurs**
| Token | Hex | Usage |
|---|---|---|
| orange | `#F97316` | accent principal, CTA, actif |
| orange-dark | `#ea6a0a` | hover CTA |
| coral | `#FF6B5E` | accent secondaire (réserve) |
| ink | `#1d1b1a` | texte principal |
| mid | `#8a8580` | texte secondaire |
| line | `#ececea` | bordures / séparateurs |
| panel | `#FBF3EC` | fond du panneau projets |
| white | `#ffffff` | fonds cartes / nav |

**Tags pastel** (fond / texte)
| color | fond | texte |
|---|---|---|
| blue | `#e8f0fe` | `#3b6fd4` |
| green | `#e6f5ec` | `#1f9d57` |
| pink | `#fdeaef` | `#d8467a` |
| orange | `#fdeede` | `#e07314` |
| purple | `#efeafd` | `#7a55cf` |

**Typographie**
- Serif (titres) : **Newsreader** (Google Fonts), poids 400–700.
- Sans (UI / texte) : **Inter** (Google Fonts), poids 400–800.

**Rayons** : cartes `18px` · panneau `30px` · hero `22px` · image carte `12px` · pills/boutons `100px` · icônes highlight `12px`.

**Ombres** : carte repos `0 1px 3px rgba(0,0,0,0.04)` · carte hover `0 18px 44px rgba(0,0,0,0.1)` ·
pill `0 2px 8px rgba(0,0,0,0.04)` · CTA hover `0 8px 20px rgba(249,115,22,0.3)`.

**Espacements clés** : `wrap` max-width `1180px`, padding latéral `40px` (mobile `20px`) ·
grille gap `22px` · panneau padding `60px 56px 56px`.

---

## Assets
- **Police** : Newsreader + Inter via Google Fonts (CDN). En production, privilégier l'auto-hébergement (`@fontsource`) si le projet l'exige.
- **Photo hero** : photo de bureau top-down (clavier, plante, carnet) — à fournir par le portfolio.
- **Photos projets** : 1 visuel par projet (ratio 4:3) — à fournir.
- **Icônes** : SVG inline (dossier, check-circle, GitHub, flèche). Remplaçables par la lib d'icônes du projet (Lucide, Heroicons…). Le logo GitHub des cartes utilise le path officiel de la marque GitHub.
- `preview-full.png` : capture de référence du rendu (haut de page).

## Fichiers de cette archive
- `Projets.html` — prototype complet (référence visuelle + structure HTML + tokens CSS).
- `image-slot.js` — composant de prototypage **uniquement** (ne pas porter ; remplacer par `<img>`).
- `preview-full.png` — capture du rendu.
