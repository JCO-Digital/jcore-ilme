//import "../../../../../../../tools/jcore-utils/src/jUtils";
import "@jcodigital/jutils";

/*
 * Mobile vh fix.
 */
let vh = window.innerHeight * 0.01;
document.documentElement.style.setProperty("--vh", `${vh}px`);

// We listen to the resize event
window.addEventListener("resize", () => {
  // We execute the same script as before
  let vh = window.innerHeight * 0.01;
  document.documentElement.style.setProperty("--vh", `${vh}px`);
});

/*
 * Scroll to top functionality
 */
let scrollButton: HTMLElement | null;

function footerInit() {
  scrollButton = document.getElementById("scroll-to-top");
  if (scrollButton) {
    window.addEventListener("scroll", scrollFunction);
    scrollButton.addEventListener("click", () => {
      document.body.scrollTop = 0; // For Safari
      document.documentElement.scrollTop = 0;
    });
  }
}

function scrollFunction() {
  if (
    document.body.scrollTop > 600 ||
    document.documentElement.scrollTop > 600
  ) {
    scrollButton.style.opacity = 1;
  } else {
    scrollButton.style.opacity = 0;
  }
}
/* End Scroll to top */

/**
 * Masonry Grid
 */
window.addEventListener("DOMContentLoaded", () => {
  footerInit();
  resizeMasonryGrid();
  const config = { attributes: false, childList: true, subtree: false };
  document.querySelectorAll(".masonry-grid").forEach((grid) => {
    const observer = new MutationObserver(resizeMasonryGrid);
    observer.observe(grid, config);
  });
});
window.addEventListener("resize", () => {
  resizeMasonryGrid();
});

function resizeMasonryGrid() {
  document.querySelectorAll(".masonry-grid").forEach((grid) => {
    const rowHeight = parseInt(
      window.getComputedStyle(grid).getPropertyValue("grid-auto-rows"),
    );
    const rowGap = parseInt(
      window.getComputedStyle(grid).getPropertyValue("grid-row-gap"),
    );
    for (const item of grid.children) {
      let height = rowGap;
      for (const part of item.children) {
        const style = getComputedStyle(part);
        height += parseInt(style.marginTop);
        height += part.offsetHeight;
        height += parseInt(style.marginBottom);
      }
      const rowSpan = Math.ceil(height / (rowHeight + rowGap));
      item.style.gridRowEnd = "span " + rowSpan;
    }
  });
}
/* End Masonry Grid */
