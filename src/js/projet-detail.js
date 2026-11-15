 'use strict';

    /* ── Image mapping (same keys as projets.html) ── */
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

    const CAT_COLOR = {
      'Frontend':              'blue',
      'Backend':               'blue',
      'Fullstack':             'blue',
      'Développement mobile':  'green',
      'Jeu vidéo':             'purple',
      'Automatisation IA':     'pink',
      'Ux/Ui':                 'orange',
      'DevOps':                'orange',
      'Déploiement':           'orange',
    };

    const DIFF_MAP = {
      facile:         { level: 1, label: 'Facile'         },
      moyen:          { level: 2, label: 'Moyen'          },
      assezdifficile: { level: 3, label: 'Intermédiaire'  },
      difficile:      { level: 4, label: 'Difficile'      },
      trsdifficile:   { level: 5, label: 'Expert'         }
    };

    /* ── Helpers ── */
    function getImages(titre) {
      return PROJ_IMAGES[normalizeName(titre)] ?? { main: 'assets/projets/defaultProjetImg.jpg', gallery: [] };
    }
    function getDiff(str) {
      return DIFF_MAP[normalizeName(str ?? '')] ?? null;
    }
    function dotsHTML(level) {
      return '<span class="pd-dots">' +
        Array.from({ length: 5 }, (_, i) =>
          `<span class="pd-dot ${i < level ? 'on' : ''}"></span>`
        ).join('') + '</span>';
    }
    function safeSrc(p) { return encodeURI(p); }
    function calcDuree(debut, fin) {
      const d1 = new Date(debut);
      const d2 = fin ? new Date(fin) : new Date();
      const months = Math.max(1,
        (d2.getFullYear() - d1.getFullYear()) * 12 + (d2.getMonth() - d1.getMonth())
      );
      return months === 1 ? '1 mois' : `${months} mois`;
    }
    function catColor(name) { return CAT_COLOR[name] ?? 'blue'; }

    const GH_SVG = '<svg viewBox="0 0 24 24"><path d="M12 .5C5.7.5.5 5.7.5 12c0 5.1 3.3 9.4 7.9 10.9.6.1.8-.3.8-.6v-2c-3.2.7-3.9-1.4-3.9-1.4-.5-1.3-1.3-1.7-1.3-1.7-1.1-.7.1-.7.1-.7 1.2.1 1.8 1.2 1.8 1.2 1 1.8 2.7 1.3 3.4 1 .1-.8.4-1.3.7-1.6-2.6-.3-5.3-1.3-5.3-5.7 0-1.3.5-2.3 1.2-3.1-.1-.3-.5-1.5.1-3.1 0 0 1-.3 3.3 1.2a11.5 11.5 0 0 1 6 0C17.3 4.7 18.3 5 18.3 5c.6 1.6.2 2.8.1 3.1.8.8 1.2 1.8 1.2 3.1 0 4.4-2.7 5.4-5.3 5.7.4.4.8 1.1.8 2.2v3.3c0 .3.2.7.8.6 4.6-1.5 7.9-5.8 7.9-10.9C23.5 5.7 18.3.5 12 .5z"/></svg>';
    const ARROW_SVG = '<svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>';
    const CLOCK_SVG = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>';
    const FOLDER_SVG = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>';

    /* ── Render ── */
    function renderDetail(p) {
      document.title = `${p.titre} — Mehiaoui Mohamed`;

      const imgs  = getImages(p.titre);
      const diff  = getDiff(p.difficulte);
      const debut = formatDate(p.date_debut);
      const fin   = p.date_fin ? formatDate(p.date_fin) : 'En cours';
      const duree = calcDuree(p.date_debut, p.date_fin);
      const color = catColor(p.categorie_name);

      /* Badges */
      document.getElementById('pd-badges').innerHTML =
        (p.categorie_name
          ? `<span class="pd-tag pd-tag--${color}">${esc(p.categorie_name)}</span>`
          : '') +
        (diff
          ? `<span class="pd-badge-sep">·</span><span style="font-size:11px;font-weight:600;color:var(--clr-muted);letter-spacing:.04em">${esc(diff.label)}</span>`
          : '');

      /* Title */
      document.getElementById('pd-title').textContent = p.titre;

      /* Main image */
      const mainImg = document.getElementById('pd-main-img');
      mainImg.src = safeSrc(imgs.main);
      mainImg.alt = esc(p.titre);

      /* Meta strip */
      document.getElementById('pd-strip').innerHTML = `
        <div class="pd-strip-cell">
          <span class="pd-strip-label">Période</span>
          <div class="pd-strip-dates">
            <span>${debut}</span>
            <span class="arrow">↓</span>
            <span>${fin}</span>
          </div>
        </div>
        <div class="pd-strip-cell">
          <span class="pd-strip-label">Difficulté</span>
          ${diff
            ? `<div class="pd-diff-row"><span class="pd-diff-label">${esc(diff.label)}</span>${dotsHTML(diff.level)}</div>`
            : '<span class="pd-strip-value">—</span>'}
        </div>
        <div class="pd-strip-cell">
          <span class="pd-strip-label">Catégorie</span>
          ${p.categorie_name
            ? `<span class="pd-tag pd-tag--${color}">${esc(p.categorie_name)}</span>`
            : '<span class="pd-strip-value">—</span>'}
        </div>`;

      /* Description */
      document.getElementById('pd-desc').textContent = p.description ?? '';

      /* But du projet */
      if (p.but) {
        document.getElementById('pd-but').textContent = p.but;
        document.getElementById('pd-but-section').hidden = false;
      }

      /* Gallery */
      if (imgs.gallery.length > 0) {
        const gallerySection = document.getElementById('pd-gallery-section');
        gallerySection.hidden = false;
        document.getElementById('pd-gallery-grid').innerHTML =
          imgs.gallery.map(src =>
            `<img class="pd-gallery-img" src="${safeSrc(src)}" alt="Capture du projet"
                  loading="lazy" onerror="this.style.display='none'">`
          ).join('');

        /* Lightbox on click */
        document.querySelectorAll('.pd-gallery-img').forEach(img => {
          img.addEventListener('click', () => openLightbox(img.src));
        });
      }

      /* Infos clés card */
      const pitch = (p.description ?? '').substring(0, 110) + ((p.description ?? '').length > 110 ? '…' : '');
      document.getElementById('pd-info-card').innerHTML = `
        <div class="pd-info-top">
          <div class="pd-ball"></div>
          <p class="pd-info-pitch">${esc(pitch)}</p>
        </div>
        <div class="pd-info-grid">
          <div class="pd-info-cell">
            <span class="pd-info-cell-label">Durée</span>
            <span class="pd-info-cell-val">
              ${CLOCK_SVG}${esc(duree)}
            </span>
          </div>
          <div class="pd-info-cell">
            <span class="pd-info-cell-label">Type</span>
            <span class="pd-info-cell-val">
              ${FOLDER_SVG}${esc(p.categorie_name ?? '—')}
            </span>
          </div>
        </div>`;

      /* Ressources card */
      document.getElementById('pd-resources').innerHTML = p.lien
        ? `<a href="${esc(p.lien)}" target="_blank" rel="noopener noreferrer" class="pd-res-item">
             <span class="pd-res-ico pd-res-ico--dark">${GH_SVG}</span>
             <span class="pd-res-text">
               <span class="pd-res-name">Repository GitHub</span>
               <span class="pd-res-sub">Code source</span>
             </span>
             <span class="pd-res-arrow">${ARROW_SVG}</span>
           </a>`
        : `<div class="pd-res-item">
             <span class="pd-res-ico pd-res-ico--gray">
               <svg viewBox="0 0 24 24"><path d="M12 .5C5.7.5.5 5.7.5 12c0 5.1 3.3 9.4 7.9 10.9.6.1.8-.3.8-.6v-2c-3.2.7-3.9-1.4-3.9-1.4-.5-1.3-1.3-1.7-1.3-1.7-1.1-.7.1-.7.1-.7 1.2.1 1.8 1.2 1.8 1.2 1 1.8 2.7 1.3 3.4 1 .1-.8.4-1.3.7-1.6-2.6-.3-5.3-1.3-5.3-5.7 0-1.3.5-2.3 1.2-3.1-.1-.3-.5-1.5.1-3.1 0 0 1-.3 3.3 1.2a11.5 11.5 0 0 1 6 0C17.3 4.7 18.3 5 18.3 5c.6 1.6.2 2.8.1 3.1.8.8 1.2 1.8 1.2 3.1 0 4.4-2.7 5.4-5.3 5.7.4.4.8 1.1.8 2.2v3.3c0 .3.2.7.8.6 4.6-1.5 7.9-5.8 7.9-10.9C23.5 5.7 18.3.5 12 .5z"/></svg>
             </span>
             <span class="pd-res-text">
               <span class="pd-res-name" style="color:var(--clr-muted)">Repository GitHub</span>
               <span class="pd-res-sub pd-res-sub--na">Non disponible</span>
             </span>
           </div>`;
    }

    /* ── Lightbox ── */
    const lightbox    = document.getElementById('pd-lightbox');
    const lightboxImg = document.getElementById('pd-lightbox-img');

    function openLightbox(src) {
      lightboxImg.src = src;
      lightbox.removeAttribute('aria-hidden');
      lightbox.classList.add('open');
      document.body.style.overflow = 'hidden';
      document.getElementById('pd-lightbox-close').focus();
    }
    function closeLightbox() {
      lightbox.setAttribute('aria-hidden', 'true');
      lightbox.classList.remove('open');
      document.body.style.overflow = '';
    }

    document.getElementById('pd-lightbox-close').addEventListener('click', closeLightbox);
    lightbox.addEventListener('click', e => { if (e.target === lightbox) closeLightbox(); });
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape' && lightbox.classList.contains('open')) closeLightbox();
    });

    /* ── Init ── */
    (async () => {
      const params = new URLSearchParams(window.location.search);
      const rawId  = params.get('id');
      const id     = parseInt(rawId, 10);

      console.log('[projet-detail] id param:', rawId, '→ parsed:', id);

      if (!id || isNaN(id)) {
        console.warn('[projet-detail] ID invalide');
        document.getElementById('pd-loading').hidden = true;
        document.getElementById('pd-error').hidden   = false;
        return;
      }

      let projet = null;
      try {
        const res = await fetch(`/api/projets/${id}`);
        console.log('[projet-detail] API status:', res.status, res.ok);
        if (res.ok) {
          const json = await res.json();
          console.log('[projet-detail] API json.data:', json.data);
          projet = json.data ?? null;
        } else {
          console.error('[projet-detail] API erreur HTTP:', res.status);
        }
      } catch (err) {
        console.error('[projet-detail] Fetch erreur:', err);
      }

      if (!projet) {
        document.getElementById('pd-loading').hidden = true;
        document.getElementById('pd-error').hidden   = false;
        return;
      }

      try {
        renderDetail(projet);
      } catch (err) {
        console.error('[projet-detail] renderDetail erreur:', err);
        document.getElementById('pd-loading').hidden = true;
        document.getElementById('pd-error').hidden   = false;
        return;
      }

      document.getElementById('pd-loading').hidden = true;
      document.getElementById('pd-page').hidden    = false;
    })();