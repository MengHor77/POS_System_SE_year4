/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.ts",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
          boxShadow: {
            card: "0 4px 12px rgba(0,0,0,0.08)",
            soft: "0 2px 6px rgba(0,0,0,0.06)",
          },

          borderRadius: {
            xl2: "1rem",
          },

      fontSize: {
        xxs: "0.65rem",
      },

      colors: {
        /* ===== Brand Colors ===== */
        primary: "#4f46e5",     // Indigo (Main brand)
        secondary: "#22c55e",   // Green (Confirm / Success)
        danger: "#ef4444",      // Red (Error / Delete)
        warning: "#f59e0b",     // Amber (Warning)
        info: "#0ea5e9",        // Sky Blue (Info / Alerts)
        accent: "#f43f5e",      // Pink / Accent highlight
        highlight: "#f97316",   // Orange / Highlighted actions

        /* ===== Dark & Neutral ===== */
        dark: "#6fa8dc",         // Dark background (Sidebar, general dark areas)
        darkSoft: "#3480c5",     // Soft dark (hover, secondary dark elements)
        muted: "#6b7280",        // Muted / Secondary text
        border: "#e5e7eb",       // Border / Divider color
        grayLight: "#d1d5db",    // Light gray / Disabled / Placeholder

        /* ===== Backgrounds ===== */
        bgMain: "#f9fafb",       // Main app background
        bgCard: "#ffffff",       // Card background
        bgHover: "#f3f4f6",      // Hover background
        bgSidebar: "#f8fafc",    // Sidebar background
        bgHeader: "#ffffff",     // Header background

        /* ===== Status Colors ===== */
        success: "#16a34a",      // Success state
        successSoft: "#dcfce7",  // Soft success background
        dangerSoft: "#fee2e2",   // Soft error background
        warningSoft: "#fef3c7",  // Soft warning background
        infoSoft: "#e0f2fe",     // Soft info background

        /* ===== POS Specific ===== */
        cash: "#22c55e",         // Cash payment
        credit: "#6366f1",       // Credit card
        pending: "#facc15",      // Pending orders
        refund: "#8b5cf6",       // Refund / Return
        discount: "#ec4899",     // Discount / Offer
        bgfrontend: "#83bee7",

         /* ===== Buttons ===== */
        bgBtnEdit: "#3b82f6",       // Blue Edit
        bgBtnEditHover: "#2563eb",  // Darker Blue on hover
        bgBtnDelete: "#ef4444",     // Red Delete
        bgBtnDeleteHover: "#dc2626",// Darker Red on hover
        bgBtnSave: "#10b981",       // Green Save
        bgBtnSaveHover: "#059669",  // Darker Green on hover
        bgBtnCancel: "#6b7280",     // Gray Cancel
        bgBtnCancelHover: "#4b5563",// Darker Gray on hover

         /* ===== Purchase Order Status ===== */
        poPending: "#facc15",
        poApproved: "#22c55e",
        poRejected: "#ef4444",

        poPendingSoft: "#fef9c3",
        poApprovedSoft: "#dcfce7",
        poRejectedSoft: "#fee2e2",

        /* ===== Table ===== */
        tableHeader: "#f1f5f9",
        tableRowHover: "#f8fafc",

        /* ===== Icons ===== */
        iconView: "#0ea5e9",
        iconEdit: "#3b82f6",
        iconDelete: "#ef4444",
      },
    },
  },
  plugins: [],
};
