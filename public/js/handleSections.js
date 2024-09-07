class SectionManager {
  constructor() {
    this.sections = document.querySelectorAll(".section");
    this.triggers = document.querySelectorAll(".section-trigger");

    this.hideAllSections();
    document.getElementById("orders").style.display = "block";

    this.triggers.forEach((trigger) => {
      trigger.addEventListener("click", () => this.toggleSection(trigger));
    });
  }

  toggleSection(clickedTrigger) {
    const sectionId = clickedTrigger.getAttribute("data-section-id");
    const section = document.getElementById(sectionId);

    if (section.style.display === "none") {
      this.hideAllSections();
      section.style.display = "block";
    } else {
      section.style.display = "none";
    }
  }

  hideAllSections() {
    this.sections.forEach((section) => {
      section.style.display = "none";
    });
  }
}

document.addEventListener("DOMContentLoaded", () => {
  new SectionManager();
});
