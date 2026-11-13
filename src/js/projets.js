 'use strict';

    /* ── Images par projet (clé = normalizeName(titre)) ── */
    const PROJ_IMAGES = {
      vosgestennis: {
        main: 'assets/projets/vosgesTennis/vosgesTennis.png',
        gallery: []
      },
      flipper3d: {
        main: 'assets/projets/flipper3D/Flipper3D.png',
        gallery: [
          'assets/projets/flipper3D/FlipperImgPrincipale.png',
          'assets/projets/flipper3D/FlipperLaunch.png',
          'assets/projets/flipper3D/flipperPanel.png',
          'assets/projets/flipper3D/FlipperCode.png'
        ]
      },
      petfinder: {
        main: 'assets/projets/petFinder/img1.png',
        gallery: [
          "assets/projets/petFinder/Capture d'écran 2026-06-10 132510.png",
          "assets/projets/petFinder/Capture d'écran 2026-06-10 132657.png"
        ]
      },
      platerecognition: {
        main: 'assets/projets/defaultProjetImg.jpg',
        gallery: []
      },
      maintenanceapplicative: {
        main: 'assets/projets/Maintenance applicative/MaintenanceApplicative.png',
        gallery: [
          'assets/projets/Maintenance applicative/page1.png',
          'assets/projets/Maintenance applicative/Code.png',
          'assets/projets/Maintenance applicative/docker.png'
        ]
      },
      /* SAE6 Développement mobile → normalizeName → 'sae6dveloppementmobile' (é supprimé) */
      sae6dveloppementmobile: {
        main: 'assets/projets/SAE6Mobile/SAE6.jpg',
        gallery: [
          'assets/projets/SAE6Mobile/Screenshot_2026-06-11-21-08-26-984_com.example.sae6.jpg',
          'assets/projets/SAE6Mobile/Screenshot_2026-06-11-21-08-40-516_com.example.sae6.jpg',
          'assets/projets/SAE6Mobile/Screenshot_2026-06-11-21-08-50-575_com.example.sae6.jpg',
          'assets/projets/SAE6Mobile/Screenshot_2026-06-11-21-08-57-815_com.example.sae6.jpg',
          'assets/projets/SAE6Mobile/Screenshot_2026-06-11-21-09-06-850_com.example.sae6.jpg',
          'assets/projets/SAE6Mobile/Screenshot_2026-06-11-21-09-12-499_com.example.sae6.jpg',
          'assets/projets/SAE6Mobile/Screenshot_2026-06-11-21-09-20-711_com.example.sae6.jpg',
          'assets/projets/SAE6Mobile/Screenshot_2026-06-11-21-09-28-576_com.example.sae6.jpg'
        ]
      },
      mehiaouilab: {
        main: 'assets/projets/defaultProjetImg.jpg',
        gallery: []
      }
    };

    /* ── Catégorie & couleur par projet ── */
    const PROJ_CAT = {
      vosgestennis:           { cat: 'Web App',     color: 'blue'   },
      flipper3d:              { cat: 'Game Dev',    color: 'purple' },
      petfinder:              { cat: 'Web App',     color: 'blue'   },
      platerecognition:       { cat: 'IA',          color: 'pink'   },
      maintenanceapplicative: { cat: 'Maintenance', color: 'orange' },
      sae6dveloppementmobile: { cat: 'Mobile',      color: 'green'  },
      mehiaouilab:            { cat: 'Fullstack',   color: 'blue'   }
    };

    /* ── Difficulté texte → niveau numérique ── */
    const DIFF_MAP = {
      facile:         { level: 1, label: 'Facile'        },
      moyen:          { level: 2, label: 'Moyen'         },
      assezdifficile: { level: 3, label: 'Intermédiaire' },
      difficile:      { level: 4, label: 'Difficile'     },
      trsdifficile:   { level: 5, label: 'Expert'        }
    };

    /* ── Helpers ── */
    function getImages(titre) {
      return PROJ_IMAGES[normalizeName(titre)] ?? { main: 'assets/projets/defaultProjetImg.jpg', gallery: [] };
    }
    function getCat(titre) {
      return PROJ_CAT[normalizeName(titre)] ?? { cat: 'Projet', color: 'blue' };
    }
    function getDiff(str) {
      return DIFF_MAP[normalizeName(str ?? '')] ?? null;
    }
    function dotsHTML(level) {
      return '<span class="pj-dots">' +
        Array.from({ length: 5 }, (_, i) => `<span class="pj-dot ${i < level ? 'on' : ''}"></span>`).join('') +
        '</span>';
    }
    function safeSrc(path) { return encodeURI(path); }

    const GH_SVG   = '<svg viewBox="0 0 24 24"><path d="M12 .5C5.7.5.5 5.7.5 12c0 5.1 3.3 9.4 7.9 10.9.6.1.8-.3.8-.6v-2c-3.2.7-3.9-1.4-3.9-1.4-.5-1.3-1.3-1.7-1.3-1.7-1.1-.7.1-.7.1-.7 1.2.1 1.8 1.2 1.8 1.2 1 1.8 2.7 1.3 3.4 1 .1-.8.4-1.3.7-1.6-2.6-.3-5.3-1.3-5.3-5.7 0-1.3.5-2.3 1.2-3.1-.1-.3-.5-1.5.1-3.1 0 0 1-.3 3.3 1.2a11.5 11.5 0 0 1 6 0C17.3 4.7 18.3 5 18.3 5c.6 1.6.2 2.8.1 3.1.8.8 1.2 1.8 1.2 3.1 0 4.4-2.7 5.4-5.3 5.7.4.4.8 1.1.8 2.2v3.3c0 .3.2.7.8.6 4.6-1.5 7.9-5.8 7.9-10.9C23.5 5.7 18.3.5 12 .5z"/></svg>';
    const ARROW_SVG = '<svg viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>';

    /* ── Rendu de la grille ── */
    function renderGrid(projets) {
      const grid = document.getElementById('pj-grid');

      if (!projets?.length) {
        grid.innerHTML = '<p style="color:var(--clr-muted);font-size:14px;padding:24px 0">Aucun projet disponible pour le moment.</p>';
        return;
      }

      grid.innerHTML = projets.map((p, i) => {
        const imgs  = getImages(p.titre);
        const cat   = getCat(p.titre);
        const diff  = getDiff(p.difficulte);
        const debut = formatDate(p.date_debut);
        const fin   = p.date_fin ? formatDate(p.date_fin) : 'En cours';

        return `
          <article class="pj-card" tabindex="0"
                   data-idx="${i}" aria-label="${esc(p.titre)} — voir la fiche détail">
            <div class="pj-card-shot">
              <img src="${safeSrc(imgs.main)}" alt="${esc(p.titre)}" loading="lazy"
                   onerror="this.src='assets/projets/defaultProjetImg.jpg'">
              <span class="pj-card-num">${String(i + 1).padStart(2, '0')}</span>
              ${p.lien ? `
                <a href="${esc(p.lien)}" target="_blank" rel="noopener noreferrer"
                   class="pj-card-ext" title="Voir sur GitHub"
                   onclick="event.stopPropagation()" aria-label="Voir ${esc(p.titre)} sur GitHub">
                  ${GH_SVG}
                </a>` : ''}
            </div>
            <div class="pj-card-body">
              <h3 class="pj-card-title">${esc(p.titre)}</h3>
              <div class="pj-card-meta">
                <span class="pj-tag pj-tag--${cat.color}">${esc(cat.cat)}</span>
                <span class="pj-card-date">${debut} → ${fin}</span>
              </div>
              <div class="pj-card-foot">
                <span class="pj-diff">
                  ${diff ? `<span class="pj-diff-label">${esc(diff.label)}</span>${dotsHTML(diff.level)}` : ''}
                </span>
                <span class="pj-see">Voir la fiche ${ARROW_SVG}</span>
              </div>
            </div>
          </article>`;
      }).join('');

      grid.querySelectorAll('.pj-card').forEach((card, i) => {
        const go = () => { window.location.href = `projet-detail.html?id=${projets[i].id}`; };
        card.addEventListener('click', go);
        card.addEventListener('keydown', e => {
          if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); go(); }
        });
      });
    }

    /* ── Init ── */
    (async () => {
      const projets = await apiFetch('projets');
      renderGrid(projets);
    })();