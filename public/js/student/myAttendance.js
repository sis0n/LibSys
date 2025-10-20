function initializeMyAttendance(attendanceData) {
  const tabs = document.querySelectorAll(".att-tab");
  const contents = document.querySelectorAll(".tab-content");

  if (!tabs.length || !contents.length) {
    console.error("MyAttendance: Tab or content elements not found.");
    return;
  }

  function renderContent(tabName, container) {
    const data = attendanceData[tabName] || [];
    container.innerHTML = ""; 

    if (data.length === 0) {
      container.innerHTML = `
            <div class="no-records flex flex-col items-center justify-center py-10 text-center border border-dashed border-[var(--color-border)] rounded-lg">
                <i class="ph ph-clipboard text-6xl"></i>
                <p class="text-sm font-medium">No attendance records</p>
                <p class="text-xs text-[var(--color-gray-500)]">No visits found for the selected time period.</p>
            </div>
            `;
      return;
    }

    const fragment = document.createDocumentFragment(); 
    data.forEach(item => {
      const div = document.createElement("div");
      div.className = "record p-4 mb-2 bg-[var(--color-orange-50)] rounded-lg flex justify-between";
      div.innerHTML = `
                <div>
                    <div class="flex items-center gap-2">
                        <i class="ph ph-calendar-check"></i>
                        <p class="font-medium">${item.date}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="ph ph-clock"></i>
                        <p>Check-in: ${item.time}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-sm font-medium text-green-600">${item.status}</span><br>
                    <span class="text-xs text-gray-500">Status</span>
                </div>
            `;
      fragment.appendChild(div);
    });
    container.appendChild(fragment);
  }

  const activeTab = document.querySelector(".att-tab[data-active='true']");
  if (activeTab) {
    const activeContent = document.querySelector(`[data-content="${activeTab.dataset.tab}"]`);
    if (activeContent) {
      renderContent(activeTab.dataset.tab, activeContent);
    } else {
      console.error("MyAttendance Error: Active content not found for tab:", activeTab.dataset.tab);
    }
  } else {
    console.warn("MyAttendance Warning: No active tab found.");
  }


  tabs.forEach(tab => {
    tab.addEventListener("click", () => {
      tabs.forEach(btn => btn.dataset.active = "false");
      tab.dataset.active = "true";

      contents.forEach(c => c.classList.add("hidden"));
      const target = document.querySelector(`[data-content="${tab.dataset.tab}"]`);

      if (target) { 
        target.classList.remove("hidden");
        renderContent(tab.dataset.tab, target);
      } else {
        console.error("MyAttendance Error: Target content not found for tab:", tab.dataset.tab);
      }
    });
  });
}