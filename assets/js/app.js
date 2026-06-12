'use strict';

/* ================================================================
   CONSTANTES
   ================================================================ */
const NAV        = document.getElementById('nav');
const NAV_BURGER = document.getElementById('navBurger');
const NAV_MOBILE = document.getElementById('navMobile');

/* mapping nom normalisé → chemin icône (fallback si icon absent de l'API) */
const urlImagesCompetence = 'assets/competencesIcones/';

/* mapping titre de projet (normalisé) → image de présentation */
const PROJECT_IMG = {
  'vosgestennis':            'assets/projets/vosgesTennis/vosgesTennis.png',
  'flipper3d':               'assets/projets/flipper3D/Flipper3D.png',
  'petfinder':               'assets/projets/petFinder/img1.png',
  'platerecognition':        'assets/projets/defaultProjetImg.jpg',
  'maintenaceapplicative':   'assets/projets/Maintenance applicative/MaintenanceApplicative.png',
  'maintenanceapplicative':  'assets/projets/Maintenance applicative/MaintenanceApplicative.png',
  'sae6developpementmobile': 'assets/projets/SAE6Mobile/SAE6.jpg',
  'sae6mobile':              'assets/projets/SAE6Mobile/SAE6.jpg',
  'mehiaouilab':             'assets/images/solarite.png',
};

/* ================================================================
   NAVIGATION
   ================================================================ */
window.addEventListener('scroll', () => {
  NAV.classList.toggle('scrolled', window.scrollY > 20);
}, { passive: true });

NAV_BURGER.addEventListener('click', () => {
  const open = NAV_BURGER.classList.toggle('open');
  NAV_MOBILE.classList.toggle('open', open);
  NAV_BURGER.setAttribute('aria-expanded', String(open));
  NAV_MOBILE.setAttribute('aria-hidden', String(!open));
});

/* Fermer le menu mobile au clic sur un lien */
NAV_MOBILE.addEventListener('click', e => {
  if (e.target.tagName === 'A') {
    NAV_BURGER.classList.remove('open');
    NAV_MOBILE.classList.remove('open');
    NAV_BURGER.setAttribute('aria-expanded', 'false');
    NAV_MOBILE.setAttribute('aria-hidden', 'true');
  }
});

/* ================================================================
   HELPER : appel API
   ================================================================ */
async function apiFetch(endpoint) {
  try {
    const res = await fetch(`/api/${endpoint}`);
    if (!res.ok) return null;
    const json = await res.json();
    return json.data ?? null;
  } catch {
    return null;
  }
}

/* ================================================================
   HELPERS UTILITAIRES
   ================================================================ */
function esc(str) {
  if (!str) return '';
  return str
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

function formatDate(dateStr) {
  if (!dateStr) return '';
  const d = new Date(dateStr);
  return d.toLocaleDateString('fr-FR', { month: 'short', year: 'numeric' });
}

function normalizeName(str) {
  return (str || '').toLowerCase().replace(/[^a-z0-9]/g, '');
}

function getIcon(name, iconFromApi) {
  return iconFromApi || null;
}

function getProjectImg(titre) {
  return PROJECT_IMG[normalizeName(titre)] || 'assets/projets/defaultProjetImg.jpg';
}

/* ================================================================
   COMPÉTENCES — chargement + filtres
   ================================================================ */
async function loadCompetences() {
  const [categories, langages, frameworks, logiciels, competences] = await Promise.all([
    apiFetch('categorie-competences'),
    apiFetch('langages'),
    apiFetch('frameworks'),
    apiFetch('logiciels'),
    apiFetch('competences'),
  ]);

  if (!categories) return;

  const catById = Object.fromEntries(categories.map(c => [c.id, c.name]));

  /* Fusion de toutes les sources, on garde uniquement celles avec icône */
  const skills = [
    ...(langages     || []).map(l => ({ ...l, _type: 'Langage' })),
    ...(frameworks   || []).map(f => ({ ...f, _type: 'Framework' })),
    ...(logiciels    || []).map(l => ({ ...l, _type: 'Logiciel' })),
    ...(competences  || []).map(c => ({ ...c, name: c.nom, _type: 'Compétence' })),
  ].filter(s => getIcon(s.name, s.icon));

  if (!skills.length) return;

  /* Tabs de filtre */
  const filtersEl = document.getElementById('competences-filters');
  const usedCatIds = [...new Set(skills.map(s => s.categorie_id))];
  const usedCats = categories.filter(c => usedCatIds.includes(c.id));

  filtersEl.innerHTML =
    `<button class="filter-btn active" data-cat="all">Tout</button>` +
    usedCats.map(c =>
      `<button class="filter-btn" data-cat="${c.id}">${esc(c.name)}</button>`
    ).join('');

  /* Grille de cartes */
  const gridEl = document.getElementById('competences-grid');
  gridEl.innerHTML = skills.map(s =>
    `<div class="skill-card is-visible" data-cat="${s.categorie_id}">
       <img class="skill-icon" src="${getIcon(s.name, s.icon)}" alt="${esc(s.name)}" loading="lazy">
       <span class="skill-name">${esc(s.name)}</span>
       <span class="skill-cat">${esc(catById[s.categorie_id] || '')}</span>
     </div>`
  ).join('');

  /* Filtrage */
  filtersEl.addEventListener('click', e => {
    const btn = e.target.closest('.filter-btn');
    if (!btn) return;

    filtersEl.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const cat = btn.dataset.cat;
    gridEl.querySelectorAll('.skill-card').forEach(card => {
      const matches = cat === 'all' || card.dataset.cat === cat;
      card.classList.toggle('is-hidden',   !matches);
      card.classList.toggle('is-visible',   matches);
    });
  });
}

/* ================================================================
   PROJETS — chargement + layout éclaté
   ================================================================ */
async function loadProjets() {
  const projets = await apiFetch('projets');
  if (!projets || !projets.length) return;

  const stage  = document.getElementById('projets-stage');
  const center = stage.querySelector('.projets-center');

  /* On affiche max 5 cards dans le layout éclaté */
  projets.slice(0, 5).forEach(p => {
    const card = document.createElement('article');
    card.className = 'projet-card';
    card.innerHTML =
      `<img src="${getProjectImg(p.titre)}"
            alt="${esc(p.titre)}"
            loading="lazy"
            onerror="this.src='assets/projets/defaultProjetImg.jpg'">
       <div class="projet-info">
         <h3>${esc(p.titre)}</h3>
         ${p.difficulte ? `<span class="projet-diff">${esc(p.difficulte)}</span>` : ''}
       </div>`;
    stage.insertBefore(card, center);
  });
}

/* ================================================================
   PARCOURS — 2 dernières scolarités
   ================================================================ */
async function loadParcours() {
  const scolarites = await apiFetch('scolarite');
  if (!scolarites || !scolarites.length) return;

  const container = document.getElementById('parcours-cards');
  const slots = container.querySelectorAll('.parcours-card');
  const latest = scolarites.slice(0, 2);

  latest.forEach((s, i) => {
    if (!slots[i]) return;
    slots[i].classList.remove('skeleton');
    slots[i].innerHTML =
      `<span class="pc-badge">${esc(s.niveau)}</span>
       <h4 class="pc-title">${esc(s.intitule)}</h4>
       <p class="pc-school">${esc(s.etablissement)} · ${esc(s.ville)}</p>
       <p class="pc-dates">${formatDate(s.date_debut)} — ${s.date_fin ? formatDate(s.date_fin) : 'En cours'}</p>`;
  });
}

/* ================================================================
   EXPÉRIENCES
   ================================================================ */
async function loadExperiences() {
  const exps = await apiFetch('experiences');
  const list = document.getElementById('experiences-list');
  if (!list) return;

  if (!exps || !exps.length) {
    list.innerHTML = '';
    return;
  }

  list.innerHTML = exps.map(e =>
    `<article class="exp-card">
       <div class="exp-meta">
         <span class="exp-date">
           ${formatDate(e.date_debut)} — ${e.date_fin ? formatDate(e.date_fin) : 'Présent'}
         </span>
         ${e.type_contrat ? `<span class="exp-tag">${esc(e.type_contrat)}</span>` : ''}
       </div>
       <h3 class="exp-title">${esc(e.intitule)}</h3>
       <p class="exp-company">${esc(e.entreprise)} · ${esc(e.ville)}</p>
       ${e.description ? `<p class="exp-desc">${esc(e.description)}</p>` : ''}
     </article>`
  ).join('');
}

/* ================================================================
   TRACKING VISITE
   ================================================================ */
function trackVisit() {
  const blob = new Blob([JSON.stringify({ page: 'home' })], { type: 'application/json' });
  if (navigator.sendBeacon) {
    navigator.sendBeacon('/api/visites', blob);
  }
}

/* ================================================================
   INIT
   ================================================================ */
(async () => {
  trackVisit();
  await Promise.all([
    loadCompetences(),
    loadProjets(),
    loadParcours(),
    loadExperiences(),
  ]);
})();
