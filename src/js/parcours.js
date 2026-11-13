'use strict';

    /* ── HELPERS (partagés avec app.js) ── */
    function formatYear(dateStr) {
      if (!dateStr) return null;
      return new Date(dateStr).getFullYear();
    }

    function isCurrent(s) {
      if (!s.date_fin) return true;
      return new Date(s.date_fin) >= new Date();
    }

    function tagColor(niveau) {
      if (!niveau) return '';
      const n = niveau.toLowerCase();
      if (n.includes('but') || n.includes('informatique')) return 'Informatique';
      if (n.includes('licence') || n.includes('electro') || n.includes('eea')) return 'Electronique';
      return niveau.split(' ')[0];
    }

    /* ── CAROUSEL ÉTAT ── */
    let currentIntroId = null;

    function renderCarouselTabs(intros) {
      const tabsEl = document.getElementById('ph-tabs');
      tabsEl.innerHTML = intros.map((intro, i) =>
        `<button class="ph-tab ${i === 0 ? 'ph-tab--active' : 'ph-tab--inactive'}"
                 data-id="${intro.id}"
                 role="tab"
                 aria-selected="${i === 0}"
                 type="button">
           ${esc(intro.titre_court)}
         </button>`
      ).join('');

      tabsEl.addEventListener('click', e => {
        const btn = e.target.closest('.ph-tab');
        if (!btn) return;
        tabsEl.querySelectorAll('.ph-tab').forEach(b => {
          b.classList.replace('ph-tab--active', 'ph-tab--inactive');
          b.setAttribute('aria-selected', 'false');
        });
        btn.classList.replace('ph-tab--inactive', 'ph-tab--active');
        btn.setAttribute('aria-selected', 'true');
        const intro = intros.find(i => String(i.id) === btn.dataset.id);
        if (intro) updateHeroAndIntro(intro);
      });
    }

    function updateHeroAndIntro(intro) {
      currentIntroId = intro.id;

      /* Image */
      const img = document.getElementById('ph-img');
      if (intro.image) {
        img.src = intro.image;
        img.alt = esc(intro.etablissement);
        img.style.display = 'block';
      } else {
        img.style.display = 'none';
      }

      /* Intro texte */
      document.getElementById('si-name').textContent = intro.etablissement || '';
      document.getElementById('si-meta').textContent =
        [intro.ville, intro.nb_annees ? `${intro.nb_annees} ans` : null, intro.formation_niveau]
          .filter(Boolean).join(' · ');
      document.getElementById('si-desc').textContent = intro.description_intro || '';
    }

    /* ── FORMATION ACTUELLE ── */
    function renderFormationActuelle(scolarites) {
      const wrapper = document.getElementById('fa-card-wrapper');
      const current = [...scolarites].sort((a, b) =>
        new Date(b.date_debut) - new Date(a.date_debut)
      ).find(isCurrent) || scolarites[0];

      if (!current) { wrapper.innerHTML = ''; return; }

      const yearDebut = formatYear(current.date_debut);
      const yearFin   = current.date_fin ? formatYear(current.date_fin) : 'En cours';
      const tag       = tagColor(current.niveau);

      wrapper.innerHTML = `
        <div class="fa-card">
          <div class="fa-card-left">
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" aria-hidden="true">
              <path d="M6 16L20 8l14 8-14 8-14-8z" stroke="#fff" stroke-width="2" stroke-linejoin="round"/>
              <path d="M12 19v8c0 0 3 4 8 4s8-4 8-4v-8" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span class="fa-badge-encours">En cours</span>
          </div>
          <div class="fa-card-right">
            <p class="fa-dates">${yearDebut} — ${yearFin}</p>
            ${tag ? `<span class="fa-tag">${esc(tag)}</span>` : ''}
            <h3 class="fa-title">${esc(current.intitule)}</h3>
            ${current.description
              ? `<p class="fa-desc">${esc(current.description.substring(0, 220))}${current.description.length > 220 ? '…' : ''}</p>`
              : ''}
            <a href="formation-details.html?id=${current.id}" class="fa-link">
              Explorer les modules
              <svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                <path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </a>
          </div>
        </div>`;
    }

    /* ── ANCIENNES FORMATIONS ── */
    function renderAnciennesFormations(scolarites) {
      const grid = document.getElementById('af-grid');
      const current = [...scolarites].sort((a, b) =>
        new Date(b.date_debut) - new Date(a.date_debut)
      ).find(isCurrent);

      const past = scolarites
        .filter(s => !isCurrent(s) || s === null)
        .sort((a, b) => new Date(b.date_debut) - new Date(a.date_debut));

      if (!past.length) {
        grid.innerHTML = '<p style="color:var(--clr-muted);font-size:14px">Aucune ancienne formation.</p>';
        return;
      }

      grid.innerHTML = past.map(s => {
        const yearDebut = formatYear(s.date_debut);
        const yearFin   = s.date_fin ? formatYear(s.date_fin) : null;
        const dateBadge = yearFin
          ? `<span>${yearDebut}</span><span>${yearFin}</span>`
          : `<span>Avant ${yearDebut}</span>`;
        const tag = tagColor(s.niveau);
        const shortTitle = s.niveau + (s.intitule.includes('Réalisation') ? ' — Informatique' : '');

        return `
          <div class="af-card">
            <div class="af-card-top">
              <svg width="28" height="28" viewBox="0 0 28 28" fill="none" aria-hidden="true">
                <path d="M4 11L14 5l10 6-10 6-10-6z" stroke="#ccc" stroke-width="1.5" stroke-linejoin="round"/>
                <path d="M8 13v6s2 3 6 3 6-3 6-3v-6" stroke="#ccc" stroke-width="1.5" stroke-linecap="round"/>
              </svg>
            </div>
            <div class="af-card-body">
              <div class="af-card-dates">${dateBadge}</div>
              ${tag ? `<span class="af-card-tag">${esc(tag)}</span>` : ''}
              <p class="af-card-title">${esc(shortTitle)}</p>
              <p class="af-card-school">${esc(s.etablissement)} · ${esc(s.ville)}</p>
              ${s.description ? `<a href="formation-details.html?id=${s.id}" class="af-card-btn">Voir la formation</a>` : `<span class="af-card-btn">Bientôt disponible</span>`}
            </div>
          </div>`;
      }).join('');
    }

    /* ── INIT ── */
    (async () => {
      const [intros, scolarites] = await Promise.all([
        apiFetch('introduction-ecole'),
        apiFetch('scolarite'),
      ]);

      /* Carousel */
      if (intros && intros.length) {
        renderCarouselTabs(intros);
        updateHeroAndIntro(intros[0]);
      }

      /* Formations */
      if (scolarites && scolarites.length) {
        renderFormationActuelle(scolarites);
        renderAnciennesFormations(scolarites);
      }
    })();