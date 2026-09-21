document.addEventListener("DOMContentLoaded",function(){let n=[{id:"todo",name:"To Do"},{id:"in-progress",name:"In Progress"},{id:"in-review",name:"In Review"},{id:"done",name:"Done"}],c=[{id:"m1",name:"Masum xyz",initials:"MX",color:"bg-indigo-500 text-white"},{id:"m2",name:"Antigravity AI",initials:"AG",color:"bg-emerald-500 text-white"},{id:"m3",name:"John Doe",initials:"JD",color:"bg-amber-500 text-white"},{id:"m4",name:"Sarah Connor",initials:"SC",color:"bg-rose-500 text-white"}],s=[{id:"task-1",title:"Desain Layout Kanban Board",desc:"Membuat rancangan wireframe dan desain UI/UX yang modern untuk halaman Kanban admin panel.",columnId:"todo",priority:"high",date:"2026-06-18",members:["m1","m2"],checklist:[{id:"c1",text:"Desain Layout Grid Kolom",done:!0},{id:"c2",text:"Desain Mode Gelap & Terang",done:!1},{id:"c3",text:"Pencocokan Warna Brand Dashboard",done:!1}]},{id:"task-2",title:"Integrasi SortableJS CDN",desc:"Menghubungkan library SortableJS ke halaman dan melakukan inisialisasi pada container kolom board.",columnId:"in-progress",priority:"medium",date:"2026-06-14",members:["m2"],checklist:[{id:"c4",text:"Impor library via CDN link",done:!0},{id:"c5",text:"Setup container classes",done:!0}]},{id:"task-3",title:"Perbaikan Centering Title Modal",desc:"Menyelaraskan judul modal tambah/edit event agar tepat berada di sumbu tengah X.",columnId:"done",priority:"urgent",date:"2026-06-09",members:["m1","m3"],checklist:[{id:"c6",text:"Ubah flex ke block relative",done:!0},{id:"c7",text:"Uji di mode terang & gelap",done:!0}]},{id:"task-4",title:"Rilis Dashboard V2.1",desc:"Rilis versi terbaru V2.1 ke server staging untuk pengujian QA internal.",columnId:"in-review",priority:"urgent",date:"2026-06-12",members:["m1","m4"],checklist:[{id:"c8",text:"Deploy ke staging server",done:!0},{id:"c9",text:"Review log error & performance",done:!1}]},{id:"task-5",title:"Optimasi Kinerja ECharts",desc:"Melakukan lazy load pada grafik ECharts untuk mempercepat kecepatan muat halaman beranda.",columnId:"todo",priority:"low",date:"2026-06-25",members:["m3"],checklist:[{id:"c10",text:"Pisahkan bundle script",done:!1}]}],o=[],d=document.getElementById("kanban-board"),i=document.getElementById("modal-task"),r=document.getElementById("modal-title"),l=document.getElementById("form-task"),m=document.getElementById("task-id"),u=document.getElementById("task-title-input"),b=document.getElementById("task-column-input"),h=document.getElementById("task-date-input"),g=document.getElementById("task-desc-input"),k=document.getElementById("assignees-checkboxes"),p=document.getElementById("checklist-items-container"),v=document.getElementById("checklist-new-item"),t=document.getElementById("btn-add-checklist-item"),f=document.getElementById("btn-delete-task");var e=document.getElementById("btn-cancel-modal"),a=document.getElementById("modal-close");let x=document.getElementById("checklist-progress-text"),y=document.getElementById("modal-checklist-progressbar");var E=document.getElementById("btn-add-task");function I(){d&&(d.innerHTML="",n.forEach(t=>{var e=s.filter(e=>e.columnId===t.id),a=document.createElement("div");a.className="kanban-column",a.innerHTML=`
                <!-- Column Header -->
                <div class="kanban-column-header">
                    <div class="kanban-column-title-group">
                        <h4 class="kanban-column-title">${t.name}</h4>
                        <span class="kanban-column-badge" id="badge-${t.id}">${e.length}</span>
                    </div>
                </div>

                <!-- Cards Container (Sortable Target) -->
                <div class="kanban-cards-container custom-scrollbar" data-column-id="${t.id}">
                    <!-- Card items injected here -->
                </div>

                <!-- Column Footer / Add Task Button -->
                <button class="btn-add-task-col mt-4 py-2 bg-brand-500 hover:bg-brand-600 dark:bg-brand-600 dark:hover:bg-brand-700 text-white rounded-xl text-xs font-semibold flex items-center justify-center gap-1.5 transition-colors cursor-pointer w-full" data-column-id="${t.id}">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    Tambah Tugas
                </button>
            `,d.appendChild(a);let n=a.querySelector(".kanban-cards-container");e.forEach(e=>{e=(t=>{let e=document.createElement("div"),a=(e.className="kanban-card",e.dataset.taskId=t.id,new Date(t.date)),n=new Date,d=(n.setHours(0,0,0,0),a<n&&"done"!==t.columnId),i=(e=>{var t=(e=new Date(e)).getDate(),a=["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agt","Sep","Okt","Nov","Des"][e.getMonth()],e=e.getFullYear();return t+` ${a} `+e})(a),r=t.checklist?t.checklist.length:0,l=t.checklist?t.checklist.filter(e=>e.done).length:0,s=0<r?Math.round(l/r*100):0,o="";return t.members&&0<t.members.length&&(t.members.slice(0,3).forEach(t=>{var e=c.find(e=>e.id===t);e&&(o+=`<span class="avatar-group-item ${e.color}" title="${e.name}">${e.initials}</span>`)}),3<t.members.length)&&(o+=`<span class="avatar-group-item bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-semibold" title="${t.members.length-3} lainnya">+${t.members.length-3}</span>`),e.innerHTML=`
            <!-- Priority Badge -->
            <span class="priority-badge priority-${t.priority}">${{low:"Low",medium:"Medium",high:"High",urgent:"Urgent"}[t.priority]}</span>
            
            <!-- Title -->
            <h5 class="kanban-card-title">${t.title}</h5>
            
            <!-- Description -->
            <p class="kanban-card-desc">${t.desc||"Tidak ada deskripsi."}</p>
            
            <!-- Progress Bar (Only show if there are checklist items) -->
            ${0<r?`
            <div class="kanban-card-progress-wrapper">
                <div class="kanban-card-progress-text">
                    <span>Progress Checklist</span>
                    <span>${l}/${r} (${s}%)</span>
                </div>
                <div class="w-full bg-slate-100 dark:bg-[#2e2e2e] h-1 rounded-full overflow-hidden">
                    <div class="bg-brand-500 h-full rounded-full transition-all duration-300" style="width: ${s}%"></div>
                </div>
            </div>
            `:""}
            
            <!-- Card Footer (Date & Members) -->
            <div class="kanban-card-footer">
                <div class="kanban-card-date ${d?"overdue":""}">
                    <i class="fa-regular fa-calendar text-[10px]"></i>
                    <span>${i}</span>
                </div>
                <div class="avatar-group">
                    ${o}
                </div>
            </div>
        `,e.addEventListener("click",function(e){e.target.closest(".avatar-group-item")||e.target.closest("button")||w(t.id)}),e})(e);n.appendChild(e)})}),"undefined"!=typeof Sortable&&document.querySelectorAll(".kanban-cards-container").forEach(e=>{new Sortable(e,{group:"kanban-board-group",animation:180,ghostClass:"sortable-ghost",chosenClass:"sortable-chosen",dragClass:"sortable-drag",forceFallback:!1,onEnd:function(e){let t=e.item.dataset.taskId;var e=e.to.dataset.columnId,a=s.find(e=>e.id===t);a&&(a.columnId=e,n.forEach(t=>{var e=s.filter(e=>e.columnId===t.id).length,a=document.getElementById("badge-"+t.id);a&&(a.textContent=e)}))}})}),document.querySelectorAll(".btn-add-task-col").forEach(e=>{e.addEventListener("click",function(){w(null,this.dataset.columnId)})}))}function w(t=null,e="todo"){if(o=[],v.value="",b.innerHTML="",n.forEach(e=>{var t=document.createElement("option");t.value=e.id,t.textContent=e.name,b.appendChild(t)}),k.innerHTML="",c.forEach(e=>{var t=document.createElement("label");t.className="assignee-checkbox-label text-slate-600 dark:text-slate-300",t.innerHTML=`
                <input type="checkbox" value="${e.id}" class="assignee-cb rounded text-brand-500 border-slate-300 dark:border-[#2e2e2e] focus:ring-brand-500 bg-white dark:bg-[#161616]">
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded-full flex items-center justify-center text-[9px] font-semibold ${e.color}">${e.initials}</span>
                    <span>${e.name}</span>
                </span>
            `,k.appendChild(t)}),t){var a=s.find(e=>e.id===t);if(!a)return;r.textContent="Edit Detail Tugas",m.value=a.id,u.value=a.title,b.value=a.columnId,h.value=a.date,g.value=a.desc||"",f.classList.remove("hidden"),a.members&&a.members.forEach(e=>{e=document.querySelector(`.assignee-cb[value="${e}"]`);e&&(e.checked=!0)}),a.checklist&&(o=JSON.parse(JSON.stringify(a.checklist)))}else{r.textContent="Tambah Tugas Baru",l.reset(),m.value="",b.value=e,f.classList.add("hidden");a=(e=>{let t=new Date(e),a=""+(t.getMonth()+1),n=""+t.getDate(),d=t.getFullYear();return a.length<2&&(a="0"+a),n.length<2&&(n="0"+n),[d,a,n].join("-")})(new Date);h.value=a}L(),i.classList.remove("hidden"),setTimeout(()=>{i.classList.add("show")},10)}function $(){i.classList.remove("show"),setTimeout(()=>{i.classList.add("hidden")},300)}function L(){p.innerHTML="";var e=o.length,t=o.filter(e=>e.done).length,a=0<e?Math.round(t/e*100):0;x.textContent=t+"/"+e,y.style.width=a+"%",o.forEach((e,t)=>{var a=document.createElement("div");a.className="flex items-center justify-between gap-3 p-2 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl transition-colors",a.innerHTML=`
                <label class="flex items-center gap-3 cursor-pointer flex-1 select-none">
                    <input type="checkbox" ${e.done?"checked":""} data-index="${t}" class="checklist-item-cb rounded text-brand-500 border-slate-300 dark:border-[#2e2e2e] focus:ring-brand-500 bg-white dark:bg-[#161616]">
                    <span class="text-sm text-slate-700 dark:text-slate-300 transition-all ${e.done?"line-through text-slate-400 dark:text-slate-500":""}">${e.text}</span>
                </label>
                <button type="button" data-index="${t}" class="btn-delete-checklist text-slate-400 hover:text-rose-500 dark:hover:text-rose-400 transition-colors p-1" title="Hapus sub-tugas">
                    <i class="fa-regular fa-trash-can text-sm"></i>
                </button>
            `,p.appendChild(a)}),p.querySelectorAll(".checklist-item-cb").forEach(e=>{e.addEventListener("change",function(){var e=parseInt(this.dataset.index);o[e].done=this.checked,L()})}),p.querySelectorAll(".btn-delete-checklist").forEach(e=>{e.addEventListener("click",function(){var e=parseInt(this.dataset.index);o.splice(e,1),L()})})}a.addEventListener("click",$),e.addEventListener("click",$),document.getElementById("modal-backdrop").addEventListener("click",$),t.addEventListener("click",function(){var e=v.value.trim();e&&(o.push({id:"new-"+Date.now()+"-"+Math.floor(100*Math.random()),text:e,done:!1}),v.value="",L())}),v.addEventListener("keydown",function(e){"Enter"===e.key&&(e.preventDefault(),t.click())}),l.addEventListener("submit",function(e){e.preventDefault();let t=m.value;var a,e=u.value.trim(),n=b.value,d=h.value,i=g.value.trim(),r=document.querySelector('input[name="task-color"]:checked').value;let l=[];document.querySelectorAll(".assignee-cb:checked").forEach(e=>{l.push(e.value)}),t?-1!==(a=s.findIndex(e=>e.id===t))&&(s[a].title=e,s[a].columnId=n,s[a].date=d,s[a].desc=i,s[a].priority=r,s[a].members=l,s[a].checklist=o):(a={id:"task-"+Date.now(),title:e,columnId:n,date:d,desc:i,priority:r,members:l,checklist:o},s.push(a)),I(),$()}),f.addEventListener("click",function(){let t=m.value;t&&(s=s.filter(e=>e.id!==t),I(),$())}),E&&E.addEventListener("click",function(){w(null,n[0].id)}),I()});