import Alpine from "alpinejs";
import collapse from "@alpinejs/collapse";

Alpine.prefix("xjcore-");
Alpine.plugin(collapse);

// Don't assign Alpine to the window (keep it private):
Alpine.start();
