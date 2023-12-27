import type { Bud } from "@roots/bud";

export default async (bud: Bud) => {
  bud.runtime(false);
  bud.setPath("@src", "src");
  bud.entry("theme", ["./js/theme.ts", "./css/theme.css"]);
  bud.entry("admin", ["./js/admin.ts", "./css/admin.css"]);
  bud.entry("wplogin", "./css/wplogin.css");
};
