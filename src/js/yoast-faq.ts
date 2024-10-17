// Make YOAST FAQ block an accordion.
document.addEventListener("DOMContentLoaded", () => {
  const faqQuestions = document.querySelectorAll(
    ".schema-faq-section .schema-faq-question",
  );

  faqQuestions.forEach((question) => {
    // Convert the question to a button element for accessibility.
    const button = document.createElement("button");
    button.className = question.className;
    button.tabIndex = 0;
    button.innerHTML = question.innerHTML;
    question.replaceWith(button);

    // Add the click event listener. This will toggle the answer.
    button.addEventListener("click", () => {
      const answer = button.nextElementSibling as HTMLElement | null;
      if (!answer) return;
      const isVisible = answer.classList.contains("faq-a-open");

      const newFaqQuestions = document.querySelectorAll(
        ".schema-faq-section .schema-faq-question",
      );
      newFaqQuestions.forEach((q) => {
        const ans = q.nextElementSibling as HTMLElement | null;
        if (ans) {
          ans.classList.remove("faq-a-open");
          ans.style.height = "0";
        }
        q.classList.remove("faq-q-open");
      });

      if (!isVisible) {
        button.classList.add("faq-q-open");
        answer.classList.add("faq-a-open");
        answer.style.height = `${answer.scrollHeight}px`;
      }
    });
  });
});
