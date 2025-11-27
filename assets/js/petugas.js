// ============================================
// DASHBOARD INTERACTIVITY
// ============================================

document.addEventListener("DOMContentLoaded", () => {
  const sidebar = document.getElementById("sidebar")
  const sidebarToggle = document.getElementById("sidebarToggle")
  const sidebarClose = document.getElementById("sidebarClose")
  const sidebarOverlay = document.getElementById("sidebarOverlay")
  const navItems = document.querySelectorAll(".nav-item")

  // Mobile menu toggle
  if (sidebarToggle) {
    sidebarToggle.addEventListener("click", () => {
      sidebar.classList.toggle("open")
      sidebarOverlay.classList.toggle("open")
    })
  }

  // Mobile menu close
  if (sidebarClose) {
    sidebarClose.addEventListener("click", () => {
      sidebar.classList.remove("open")
      sidebarOverlay.classList.remove("open")
    })
  }

  // Sidebar overlay click
  if (sidebarOverlay) {
    sidebarOverlay.addEventListener("click", () => {
      sidebar.classList.remove("open")
      sidebarOverlay.classList.remove("open")
    })
  }

  // Nav item click
  navItems.forEach((item) => {
    item.addEventListener("click", function (e) {
      navItems.forEach((nav) => nav.classList.remove("active"))
      this.classList.add("active")

      // Close sidebar on mobile
      if (window.innerWidth <= 768) {
        sidebar.classList.remove("open")
        sidebarOverlay.classList.remove("open")
      }
    })
  })

  // Close sidebar on resize
  window.addEventListener("resize", () => {
    if (window.innerWidth > 768) {
      sidebar.classList.remove("open")
      sidebarOverlay.classList.remove("open")
    }
  })

  // Smooth scroll for activity items
  const activityItems = document.querySelectorAll(".activity-item")
  activityItems.forEach((item) => {
    item.addEventListener("click", function () {
      this.style.backgroundColor = "rgba(0, 97, 242, 0.05)"
      setTimeout(() => {
        this.style.backgroundColor = ""
      }, 300)
    })
  })

  // Export button functionality
  const exportBtn = document.querySelector(".btn-export")
  if (exportBtn) {
    exportBtn.addEventListener("click", () => {
      alert("Fitur ekspor sedang dalam pengembangan")
    })
  }
})
