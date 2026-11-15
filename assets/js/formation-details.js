 /* ── NAV BURGER ── */
    const burger = document.getElementById('navBurger');
    const mobile = document.getElementById('navMobile');
    burger.addEventListener('click', () => {
      const open = burger.getAttribute('aria-expanded') === 'true';
      burger.setAttribute('aria-expanded', String(!open));
      mobile.setAttribute('aria-hidden', String(open));
      mobile.classList.toggle('is-open', !open);
    });

    /* ── NAV SCROLL ── */
    const navEl = document.getElementById('nav');
    window.addEventListener('scroll', () => {
      navEl.classList.toggle('is-scrolled', window.scrollY > 10);
    }, { passive: true });

    /* ── HELPERS ── */
    function calcProgress(dateDebut, dateFin) {
      const start = new Date(dateDebut);
      const end   = dateFin ? new Date(dateFin) : new Date();
      const today = new Date();
      if (today <= start) return 0;
      if (today >= end)   return 100;
      return Math.round(((today - start) / (end - start)) * 100);
    }

    function setDonut(pct) {
      const circumference = 314.16;
      const offset = circumference * (1 - pct / 100);
      document.getElementById('fds-progress-ring').style.strokeDashoffset = offset;
      document.getElementById('fds-pct').textContent = pct + '%';
    }

    function shortDesc(desc, max = 48) {
      if (!desc) return '';
      return desc.length > max ? desc.slice(0, max).trim() + '…' : desc;
    }

    /* ── RENDER MODULES ── */
    function renderModuleIcon() {
      return `<svg width="14" height="14" viewBox="0 0 14 14" fill="none">
        <rect x="2" y="2" width="10" height="10" rx="2" stroke="#bbb" stroke-width="1.2"/>
        <path d="M5 7l2 2 2-3" stroke="#bbb" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>`;
    }

    function renderEnsItem(e) {
      const hasDesc = !!e.description;
      return `<div class="fd-ens-accordion" data-ens="${esc(String(e.id))}">
        <div class="fd-ens-item">
          <div class="fd-ens-icon">${renderModuleIcon()}</div>
          <div class="fd-ens-text">
            <div class="fd-ens-name" title="${esc(e.nom)}">${esc(e.nom)}</div>
          </div>
          ${hasDesc
            ? `<svg class="fd-ens-arrow" width="14" height="14" viewBox="0 0 14 14" fill="none">
                <path d="M5 3l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
               </svg>`
            : ''}
        </div>
        ${hasDesc
          ? `<div class="fd-ens-body">
               <div class="fd-ens-body-inner">
                 <div class="fd-ens-body-content">${esc(e.description)}</div>
               </div>
             </div>`
          : ''}
      </div>`;
    }

    /* Toggle accordion on click — delegated on the modules container */
    document.getElementById('fd-modules').addEventListener('click', e => {
      const item = e.target.closest('.fd-ens-item');
      if (!item) return;
      const acc = item.closest('.fd-ens-accordion');
      if (!acc || !acc.querySelector('.fd-ens-body')) return;
      acc.classList.toggle('is-open');
    });

    function renderModuleCard(idx, s5Module, s6Module, s5Ens, s6Ens) {
      const title = s5Module
        ? s5Module.intitule
        : s6Module.intitule;

      const desc = s5Module
        ? s5Module.description
        : s6Module.description;

      const s5Col = s5Module
        ? `<div>
            <div class="fd-sem-label">
              <svg width="8" height="8" viewBox="0 0 8 8" fill="none">
                <polygon points="0,0 8,4 0,8" fill="var(--clr-accent)"/>
              </svg>
              Semestre 5
            </div>
            <div class="fd-ens-list">
              ${s5Ens.length ? s5Ens.map(renderEnsItem).join('') : '<div class="fd-empty-col">Aucun enseignement</div>'}
            </div>
          </div>`
        : `<div class="fd-empty-col">Aucun module S5</div>`;

      const s6Col = s6Module
        ? `<div>
            <div class="fd-sem-label">
              <svg width="8" height="8" viewBox="0 0 8 8" fill="none">
                <polygon points="0,0 8,4 0,8" fill="var(--clr-accent)"/>
              </svg>
              Semestre 6
            </div>
            <div class="fd-ens-list">
              ${s6Ens.length ? s6Ens.map(renderEnsItem).join('') : '<div class="fd-empty-col">Aucun enseignement</div>'}
            </div>
          </div>`
        : `<div class="fd-empty-col">Aucun module correspondant au S6</div>`;

      return `<div class="fd-module-card">
        <div class="fd-module-header">
          <div class="fd-module-num">${idx}</div>
          <div>
            <p class="fd-module-title">${esc(title)}</p>
            ${desc ? `<p class="fd-module-desc">${esc(desc)}</p>` : ''}
          </div>
        </div>
        <div class="fd-module-cols">
          ${s5Col}
          ${s6Col}
        </div>
      </div>`;
    }

    /* ── MAIN ── */
    (async () => {
      /* Read scolarite_id from URL param (default: find the current one) */
      const params      = new URLSearchParams(window.location.search);
      const targetId    = params.get('id') ? parseInt(params.get('id')) : null;

      /* Load scolarite + modules in parallel */
      const [scolarites, allModules] = await Promise.all([
        apiFetch('scolarite'),
        apiFetch('modules'),
      ]);

      if (!scolarites || !allModules) return;

      /* Determine target scolarite */
      let scolarite = targetId
        ? scolarites.find(s => s.id === targetId)
        : scolarites.find(s => !s.date_fin || new Date(s.date_fin) >= new Date());

      if (!scolarite) scolarite = scolarites[scolarites.length - 1];

      /* ── HEADER ── */
      document.title = `${scolarite.intitule} — Mehiaoui Mohamed`;
      document.getElementById('fd-title').textContent    = `${scolarite.niveau} — ${scolarite.intitule.split('—')[1]?.trim() || scolarite.intitule}`;
      document.getElementById('fd-subtitle').textContent = scolarite.description || '';

      /* ── STATS (scolarite info) ── */
      document.getElementById('sd-niveau').textContent  = scolarite.niveau;
      document.getElementById('sd-intitule').textContent = (scolarite.intitule.split('—')[1]?.trim() || '').slice(0, 30);

      /* ── SIDEBAR ── */
      const yearStart = scolarite.date_debut ? scolarite.date_debut.slice(0, 4) : '—';
      const yearEnd   = scolarite.date_fin   ? scolarite.date_fin.slice(0, 4)   : '—';
      document.getElementById('fds-year').textContent   = `${yearStart} — ${yearEnd}`;
      document.getElementById('fds-school').textContent = scolarite.etablissement + ' · ' + scolarite.ville;

      const fmtDate = d => {
        if (!d) return '—';
        const [y, m] = d.split('-');
        const months = ['janv.','févr.','mars','avr.','mai','juin','juil.','août','sept.','oct.','nov.','déc.'];
        return months[parseInt(m, 10) - 1] + ' ' + y;
      };
      document.getElementById('fds-dates').textContent =
        fmtDate(scolarite.date_debut) + ' → ' + (scolarite.date_fin ? fmtDate(scolarite.date_fin) : 'En cours');

      /* Progress ring */
      const pct = calcProgress(scolarite.date_debut, scolarite.date_fin);
      setDonut(pct);

      /* ── MODULES ── */
      const modules = allModules.filter(m => m.scolarite_id === scolarite.id);
      const s5Mods  = modules.filter(m => m.niveau && m.niveau.toLowerCase().includes('5'));
      const s6Mods  = modules.filter(m => m.niveau && m.niveau.toLowerCase().includes('6'));

      /* Update stats */
      document.getElementById('sd-modules').textContent = modules.length;

      /* Load all enseignements in parallel */
      const ensData = await Promise.all(
        modules.map(m => apiFetch(`modules/${m.id}/enseignements`).then(d => ({ id: m.id, ens: d || [] })))
      );

      const ensMap = {};
      ensData.forEach(({ id, ens }) => { ensMap[id] = ens; });

      const totalEns = Object.values(ensMap).reduce((sum, arr) => sum + arr.length, 0);
      document.getElementById('sd-enseignements').textContent = totalEns;

      /* Sidebar enseignement info */
      const s5Ens = s5Mods.reduce((acc, m) => acc + (ensMap[m.id]?.length || 0), 0);
      document.getElementById('fds-ens-done').textContent  = `${s5Ens} enseignements S5`;
      document.getElementById('fds-ens-total').textContent = `${totalEns - s5Ens} enseignements S6`;

      /* Pair S5 + S6 by index */
      const maxRows = Math.max(s5Mods.length, s6Mods.length);
      let html = '';
      for (let i = 0; i < maxRows; i++) {
        const s5m = s5Mods[i] || null;
        const s6m = s6Mods[i] || null;
        html += renderModuleCard(
          i + 1,
          s5m, s6m,
          s5m ? (ensMap[s5m.id] || []) : [],
          s6m ? (ensMap[s6m.id] || []) : [],
        );
      }

      document.getElementById('fd-modules').innerHTML = html || '<p style="color:var(--clr-muted);font-size:14px;">Aucun module trouvé.</p>';
    })();