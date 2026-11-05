function showToast(message){const toast=document.querySelector('#toast');if(!toast)return;toast.textContent=message;toast.classList.add('show');setTimeout(()=>toast.classList.remove('show'),2400)}

// Ikon mata untuk tombol lihat/sembunyikan password, dipakai di beberapa halaman
// (login, buat akun siswa/guru, reset password) jadi disimpan sekali di sini.
const EYE_ICON='<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/><circle cx="12" cy="12" r="3"/></svg>';
const EYE_OFF_ICON='<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.94 10.94 0 0 1 12 19c-7 0-11-7-11-7a21.9 21.9 0 0 1 5.06-6.06M9.9 4.24A10.94 10.94 0 0 1 12 5c7 0 11 7 11 7a21.9 21.9 0 0 1-3.22 4.44M14.12 14.12a3 3 0 1 1-4.24-4.24"/><path d="M1 1l22 22"/></svg>';

// Tombol "Buatkan acak" / "Acak" di form akun siswa: isi field password dengan
// kombinasi huruf+angka acak (tanpa karakter yang gampang ketuker, mis. 0/O, 1/l),
// dan langsung tampilkan supaya petugas bisa lihat & catat sebelum submit.
function initPasswordGenerators(){
  document.querySelectorAll('.pw-generate').forEach(btn=>{
    btn.addEventListener('click',()=>{
      const input=document.getElementById(btn.dataset.target);
      if(!input)return;
      const chars='ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
      const rand=crypto.getRandomValues(new Uint32Array(8));
      let pass='';
      for(let i=0;i<8;i++)pass+=chars[rand[i]%chars.length];
      input.value=pass;
      input.type='text';
      const toggle=input.parentElement.querySelector('.pw-toggle');
      if(toggle){
        toggle.innerHTML=EYE_OFF_ICON;
        toggle.setAttribute('aria-label','Sembunyikan password');
      }
    });
  });
}

function ensureLightbox(){
  let box=document.querySelector('#lightbox');
  if(!box){
    box=document.createElement('div');
    box.id='lightbox';
    box.className='lightbox';
    box.innerHTML='<button type="button" class="lightbox-close" aria-label="Tutup">✕</button><img alt="Foto laporan diperbesar">';
    box.addEventListener('click',(e)=>{ if(e.target===box||e.target.classList.contains('lightbox-close')) closeLightbox() });
    document.body.appendChild(box);
  }
  return box;
}
function openLightbox(src){
  const box=ensureLightbox();
  box.querySelector('img').src=src;
  box.classList.add('show');
}
function closeLightbox(){
  const box=document.querySelector('#lightbox');
  if(box) box.classList.remove('show');
}

// Ganti <select class="fancy-select"> panjang (mis. daftar ruangan) jadi dropdown
// kustom yang selalu terbuka ke bawah dan bisa di-scroll, bukan mengikuti arah
// bawaan browser yang kadang membuka ke atas kalau ruang di bawahnya sempit.
function initFancySelects(){
  document.querySelectorAll('select.fancy-select').forEach(select=>{
    if(select.dataset.fancyReady)return;
    select.dataset.fancyReady='1';

    const wrap=document.createElement('div');
    wrap.className='fancy-select-wrap';
    select.parentNode.insertBefore(wrap,select);
    wrap.appendChild(select);

    const toggle=document.createElement('button');
    toggle.type='button';
    toggle.className='fancy-select-toggle';
    toggle.innerHTML='<span class="fancy-select-label"></span><svg class="chev" viewBox="0 0 14 9" fill="none"><path d="M1 1l6 6 6-6" stroke="currentColor" stroke-width="2" fill="none"/></svg>';

    const menu=document.createElement('div');
    menu.className='fancy-select-menu';

    const labelEl=toggle.querySelector('.fancy-select-label');
    const options=[...select.options];

    function renderLabel(){
      const current=select.options[select.selectedIndex];
      const isPlaceholder=!current||current.value==='';
      labelEl.textContent=current?current.textContent:'';
      labelEl.classList.toggle('ph',isPlaceholder);
    }

    function renderMenu(){
      menu.innerHTML='';
      options.forEach((option,index)=>{
        const item=document.createElement('div');
        item.className='fancy-select-option';
        item.textContent=option.textContent;
        item.classList.toggle('is-selected',index===select.selectedIndex);
        item.addEventListener('click',()=>{
          select.selectedIndex=index;
          select.dispatchEvent(new Event('change',{bubbles:true}));
          renderLabel();
          renderMenu();
          closeMenu();
        });
        menu.appendChild(item);
      });
    }

    function openMenu(){ wrap.classList.add('is-open') }
    function closeMenu(){ wrap.classList.remove('is-open') }

    toggle.addEventListener('click',()=>{
      wrap.classList.contains('is-open')?closeMenu():openMenu();
    });
    document.addEventListener('click',(e)=>{
      if(!wrap.contains(e.target))closeMenu();
    });
    document.addEventListener('keydown',(e)=>{
      if(e.key==='Escape')closeMenu();
    });

    renderLabel();
    renderMenu();
    wrap.appendChild(toggle);
    wrap.appendChild(menu);
    wrap.classList.add('is-ready');
  });
}

// Denah asli: klik salah satu ruangan (hotspot) untuk memilihnya, lalu tombol
// "Lanjut buat laporan" otomatis membawa location_id itu ke form laporan supaya
// ruangannya sudah kepilih duluan di sana.
function initMapHotspots(){
  const frame=document.querySelector('.map-frame');
  if(!frame)return;

  const roomTitle=document.querySelector('#roomTitle');
  const roomText=document.querySelector('#roomText');
  const continueBtn=document.querySelector('#continueBtn');
  const baseHref=continueBtn?continueBtn.getAttribute('href'):'';

  frame.querySelectorAll('.map-hotspot').forEach(spot=>{
    spot.addEventListener('click',()=>{
      frame.querySelectorAll('.map-hotspot.is-selected').forEach(s=>s.classList.remove('is-selected'));
      spot.classList.add('is-selected');

      const name=spot.dataset.locationName;
      const id=spot.dataset.locationId;

      if(roomTitle)roomTitle.textContent=name;
      if(roomText)roomText.textContent=continueBtn?'Lokasi sudah dipilih. Lanjutkan ke form laporan.':'Ruangan ini ada di daftar lokasi.';
      if(continueBtn&&id)continueBtn.href=baseHref+(baseHref.includes('?')?'&':'?')+'location_id='+encodeURIComponent(id);

      showToast('Lokasi "'+name+'" dipilih.');
    });
  });
}

// Pencarian akun siswa & guru (halaman petugas "Akun siswa & guru"): memfilter daftar langsung
// saat mengetik, tanpa reload. Semua kata yang diketik harus cocok (nama atau email),
// dan bisa dipersempit lagi lewat chip peran (Semua / Siswa / Guru).
function initAccountSearch(){
  const list=document.querySelector('#accountList');
  const input=document.querySelector('#accountSearch');
  if(!list||!input)return;

  const rows=[...list.querySelectorAll('.account-row')];
  const empty=document.querySelector('#accountEmpty');
  const count=document.querySelector('#accountCount');
  const clear=document.querySelector('#accountSearchClear');
  const chips=[...document.querySelectorAll('.role-filter .chip')];
  let role='semua';

  function apply(){
    const words=input.value.toLowerCase().trim().split(/\s+/).filter(Boolean);
    let shown=0;
    rows.forEach(row=>{
      const okRole=role==='semua'||row.dataset.role===role;
      const hay=row.dataset.search||'';
      const okText=words.every(w=>hay.includes(w));
      const show=okRole&&okText;
      row.hidden=!show;
      if(show)shown++;
    });
    if(clear)clear.hidden=input.value==='';
    if(empty){
      if(rows.length>0){
        empty.textContent='Tidak ada akun yang cocok dengan pencarian.';
        empty.hidden=shown>0;
      }
    }
    if(count){
      const filtering=words.length>0||role!=='semua';
      count.textContent=filtering?('Ditemukan '+shown+' dari '+rows.length+' akun.'):('Menampilkan '+rows.length+' akun.');
    }
  }

  input.addEventListener('input',apply);
  input.addEventListener('keydown',e=>{
    if(e.key==='Escape'&&input.value!==''){input.value='';apply();e.stopPropagation()}
    if(e.key==='Enter')e.preventDefault();
  });
  if(clear)clear.addEventListener('click',()=>{input.value='';apply();input.focus()});
  chips.forEach(chip=>chip.addEventListener('click',()=>{
    role=chip.dataset.role;
    chips.forEach(c=>c.classList.toggle('is-active',c===chip));
    apply();
  }));
  apply();
}

// Tab di form akun: "Buat satu akun" (manual) dan "Impor banyak akun" (upload Excel / Word).
function initAccountTabs(){
  const box=document.querySelector('#accountTabs');
  if(!box)return;
  const tabs=[...box.querySelectorAll('.form-tabs .chip')];
  const panels=[...box.querySelectorAll('.tab-panel')];
  function show(name){
    tabs.forEach(t=>t.classList.toggle('is-active',t.dataset.tab===name));
    panels.forEach(p=>{p.hidden=p.dataset.panel!==name});
  }
  tabs.forEach(t=>t.addEventListener('click',()=>show(t.dataset.tab)));
  show(box.dataset.open||'single');
}

// Setelah impor, password hanya tampil sekali. Tombol ini mengunduh tabel hasil impor sebagai file yang bisa dibuka di Excel
// (pemisah titik koma + BOM supaya langsung rapi kalau dibuka di Excel).
function initCredentialsDownload(){
  const btn=document.querySelector('#downloadCredentials');
  const table=document.querySelector('#credentialsTable');
  if(!btn||!table)return;
  btn.addEventListener('click',()=>{
    const esc=v=>'"'+String(v).replace(/"/g,'""')+'"';
    const lines=[['Nama','Email','Peran','Password']];
    table.querySelectorAll('tbody tr').forEach(tr=>{
      lines.push([...tr.cells].map(td=>td.textContent.trim()));
    });
    const csv='\uFEFF'+lines.map(l=>l.map(esc).join(';')).join('\r\n');
    const blob=new Blob([csv],{type:'text/csv;charset=utf-8'});
    const a=document.createElement('a');
    const d=new Date();
    const stamp=d.getFullYear()+String(d.getMonth()+1).padStart(2,'0')+String(d.getDate()).padStart(2,'0');
    a.href=URL.createObjectURL(blob);
    a.download='akun-baru-'+stamp+'.csv';
    document.body.appendChild(a);
    a.click();
    a.remove();
    setTimeout(()=>URL.revokeObjectURL(a.href),1000);
  });
}

// Hamburger: di layar kecil sidebar menjadi panel geser. Tutup dengan klik di luar panel,
// klik salah satu menu, tombol Esc, atau saat layar dilebarkan kembali ke desktop.
function initNavToggle(){
  const btn=document.querySelector('.nav-toggle');
  const side=document.querySelector('#sidebar');
  if(!btn||!side)return;
  const setOpen=(open)=>{
    document.body.classList.toggle('nav-open',open);
    btn.setAttribute('aria-expanded',open);
    btn.setAttribute('aria-label',open?'Tutup menu':'Buka menu');
  };
  btn.addEventListener('click',()=>setOpen(!document.body.classList.contains('nav-open')));
  const backdrop=document.querySelector('.side-backdrop');
  if(backdrop)backdrop.addEventListener('click',()=>setOpen(false));
  side.querySelectorAll('a').forEach(a=>a.addEventListener('click',()=>setOpen(false)));
  document.addEventListener('keydown',(e)=>{ if(e.key==='Escape') setOpen(false) });
  window.matchMedia('(min-width:1001px)').addEventListener('change',(e)=>{ if(e.matches) setOpen(false) });
}

document.addEventListener('DOMContentLoaded',()=>{
  initNavToggle();
  initFancySelects();
  initMapHotspots();
  initPasswordGenerators();
  initAccountSearch();
  initAccountTabs();
  initCredentialsDownload();
  document.querySelectorAll('[data-print]').forEach(el=>el.addEventListener('click',()=>window.print()));
  document.querySelectorAll('[data-toast]').forEach(el=>el.addEventListener('click',()=>showToast(el.dataset.toast)));
  document.querySelectorAll('.photo-zoom[data-full]').forEach(el=>{
    el.addEventListener('click',(e)=>{ e.preventDefault(); openLightbox(el.dataset.full) });
  });
  document.addEventListener('keydown',(e)=>{ if(e.key==='Escape') closeLightbox() });

  document.querySelectorAll('.pw-toggle').forEach(btn=>{
    btn.addEventListener('click',()=>{
      const input=document.getElementById(btn.dataset.target);
      if(!input) return;
      const willShow=input.type==='password';
      input.type=willShow?'text':'password';
      btn.innerHTML=willShow?EYE_OFF_ICON:EYE_ICON;
      btn.setAttribute('aria-label',willShow?'Sembunyikan password':'Tampilkan password');
    });
  });
});
