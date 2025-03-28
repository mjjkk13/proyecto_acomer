import { defineConfig } from "vite";
import react from "@vitejs/plugin-react";
import path from "path";

// https://vite.dev/config/
export default defineConfig({
  plugins: [react()],
  server: {
    proxy: {
      "/server": {
        target: "http://localhost",
        changeOrigin: true,
        rewrite: (path) => path.replace(/^\/server/, ""),
      },
    },
  },
});
