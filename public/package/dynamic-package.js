// Dynamic package rendering for package/*.html pages
// - Reads package by slug from localStorage.packages
// - Updates price and (optionally) description/image
// - Renders courses grid and inclusion list based on selectable mode A/B/C/D
//
// How to choose mode:
// - URL param ?mode=A|B|C|D overrides
// - Else localStorage.packageRenderMode = 'A' | 'B' | 'C' | 'D'
// - Else defaults to 'A'
//
// Option C mapping (explicit courses for a package):
// - localStorage.packageCourses = { "starter": ["social-media", "ms-word"], ... }

(function(){
  function parseJSONSafe(str, fallback){
    try { return JSON.parse(str || ''); } catch(e){ return fallback; }
  }

  function getSlugFromPath(){
    try {
      const file = (location.pathname.split('/').pop() || '').toLowerCase();
      return file.replace('.html','');
    } catch(e){ return ''; }
  }

  function getMode(){
    const params = new URLSearchParams(location.search);
    const p = (params.get('mode') || '').toUpperCase();
    if (['A','B','C','D'].includes(p)) return p;
    const stored = (localStorage.getItem('packageRenderMode') || 'C').toUpperCase();
    return ['A','B','C','D'].includes(stored) ? stored : 'C';
  }

  function moneyINR(value){
    if (value === undefined || value === null || value === '') return null;
    const num = Number(value);
    if (!isFinite(num)) return `${value}`;
    try {
      return new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR', maximumFractionDigits: 0 }).format(num);
    } catch(e){
      return `₹${num}`;
    }
  }

  function pickImage(src){
    if (!src) return null;
    if (typeof src !== 'string') return null;
    const s = src.trim();
    if (s.startsWith('data:image/')) return s; // base64 from uploads
    if (/^https?:\/\//.test(s)) return s;    // absolute URL
    if (s.includes('/')) return s;            // already a relative path
    // Fallback: assume filename that exists in public img/courses folder
    return `../img/courses/${s}`;
  }

  function updatePackageHeader(pkg){
    try {
      // Price
      const priceEl = document.querySelector('.price-amount');
      const priceText = moneyINR(pkg.price);
      if (priceEl && priceText) priceEl.textContent = priceText;

      // Insert/Update a short description inside the card (non-destructive)
      if (pkg.description){
        const card = document.querySelector('.package-card');
        if (card){
          let desc = card.querySelector('.pkg-desc');
          if (!desc){
            desc = document.createElement('p');
            desc.className = 'pkg-desc';
            desc.style.cssText = 'margin:8px 0 12px; color:#555; text-align:center; line-height:1.5;';
            const ref = card.querySelector('.buy-now-btn') || card.querySelector('.courses-inclusion') || card.lastElementChild;
            card.insertBefore(desc, ref);
          }
          desc.textContent = pkg.description;
        }
      }

      // Do NOT change the existing package images on public pages per user request.
      // We will only update price and description, leaving images as authored in HTML.
    } catch(e){ /* no-op */ }
  }

  function selectCourses(mode, slug, pkg, allCourses){
    const courses = Array.isArray(allCourses) ? allCourses : [];
    // Strict rule: only courses explicitly tagged for this package (by packageSlug)
    const selected = courses.filter(c => String(c.packageSlug || '').toLowerCase().trim() === slug && (String(c.status || 'active').toLowerCase() !== 'inactive'));
    return selected;
  }

  function renderCourses(selected){
    try {
      const grid = document.querySelector('.course-cards-grid');
      const list = document.querySelector('.courses-inclusion .courses-list');
      if (!grid && !list) return; // nothing to do

      const placeholderImg = 'https://via.placeholder.com/800x450/ffffff/28a745?text=Course';

      if (grid){
        // Clear existing grid and re-render
        grid.innerHTML = '';
        selected.forEach(c => {
          const img = pickImage(c.image) || placeholderImg;
          const title = c.name || c.slug || 'Course';
          const slug = (c.slug || '').toLowerCase();
          const card = document.createElement('a');
          card.className = 'course-card';
          card.href = `../course/detail.html?slug=${encodeURIComponent(slug)}`;
          card.style.textDecoration = 'none';
          card.innerHTML = `
            <div class="course-image">
              <img src="${img}" alt="${title}">
            </div>
            <div class="course-content">
              <h3>${title}</h3>
              <div class="course-rating">
                <span class="rating">4.9</span>
                <i class="fas fa-star"></i>
                <span class="students">(${Math.floor(Math.random()*20)+1}.0k students)</span>
              </div>
            </div>`;
          grid.appendChild(card);
        });
      }

      if (list){
        list.innerHTML = '';
        selected.forEach(c => {
          const li = document.createElement('li');
          li.innerHTML = `<i class="fas fa-check"></i><span>${c.name || c.slug || 'Course'}</span>`;
          list.appendChild(li);
        });
      }
    } catch(e){ /* no-op */ }
  }

  function init(){
    const slug = getSlugFromPath();
    if (!slug) return;

    const packages = parseJSONSafe(localStorage.getItem('packages'), []);
    const courses = parseJSONSafe(localStorage.getItem('courses'), []);

    const pkg = packages.find(p => String(p.slug || '').toLowerCase() === slug) || null;
    if (pkg) updatePackageHeader(pkg);

    const mode = getMode();
    const selected = selectCourses(mode, slug, pkg, courses);

    // Only render if we actually have something
    if (Array.isArray(selected) && selected.length) {
      renderCourses(selected);
    }
  }

  if (document.readyState === 'loading'){
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();

