# Design - NQT Dev

A locked Hallmark design system for this Laravel storefront. Frontend changes should keep business logic, routes, data and SEO intact while using this visual system.

## Genre
modern-minimal technical commerce

## Macrostructure Family
- Marketing/home pages: Map / Diagram + Catalogue. The hero behaves like a circuit map connecting brief, featured product, production case and checkout.
- Catalog pages: Catalogue with product-card grid, filter rail and tabular price rhythm.
- Detail/account/order pages: Workbench surfaces with strong media, compact forms and clear action panels.

## Theme
- `--color-paper` oklch(12.5% 0.026 220)
- `--color-paper-2` oklch(15.5% 0.032 218)
- `--color-paper-3` oklch(19% 0.038 216)
- `--color-ink` oklch(96% 0.018 198)
- `--color-ink-2` oklch(88% 0.02 205)
- `--color-muted` oklch(67% 0.04 212)
- `--color-rule` oklch(58% 0.055 205 / 0.24)
- `--color-rule-2` oklch(72% 0.075 196 / 0.42)
- `--color-accent` oklch(86% 0.19 148)
- `--color-accent-ink` oklch(14% 0.032 218)
- `--color-focus` oklch(82% 0.17 188)

## Typography
- Display: Space Grotesk, weight 700, roman.
- Body: IBM Plex Sans, weight 400-700.
- Outlier: JetBrains Mono for labels, numbers and technical chips.
- Display max: `clamp(2.7rem, 6.2vw, 5.05rem)`.

## Spacing
4-point scale in `tokens.css`: `--space-3xs` through `--space-4xl`.

## Motion
- One hero orchestration plus small transform/opacity reveal.
- Hover feedback is lift or spotlight, never scale-heavy.
- No typing loops, scroll-scrub parallax, layout-property animation or infinite heavy mesh.
- Reduced motion collapses spatial movement.

## Navigation / Footer
- Nav: N13 inline search pill with Ctrl/Command K focus shortcut.
- Footer: Ft5 statement footer, not a multi-column sitemap.

## Shared Rules
- Accent footprint stays small and functional.
- Use real product/project/skill data only.
- No fake browser, terminal or phone chrome.
- Cards use one containment layer with 8px radius.
- Mobile must be checked at 320, 375, 414 and 768px with no horizontal scroll.

## Exports

### tokens.css
```css
:root {
    --color-paper: oklch(12.5% 0.026 220);
    --color-paper-2: oklch(15.5% 0.032 218);
    --color-paper-3: oklch(19% 0.038 216);
    --color-rule: oklch(58% 0.055 205 / 0.24);
    --color-rule-2: oklch(72% 0.075 196 / 0.42);
    --color-muted: oklch(67% 0.04 212);
    --color-neutral: oklch(78% 0.038 210);
    --color-ink-2: oklch(88% 0.02 205);
    --color-ink: oklch(96% 0.018 198);
    --color-accent: oklch(86% 0.19 148);
    --color-accent-ink: oklch(14% 0.032 218);
    --color-focus: oklch(82% 0.17 188);
}
```

### Tailwind v4 @theme
```css
@theme {
    --font-sans: var(--font-body);
    --font-mono: var(--font-outlier);
    --color-primary: oklch(82% 0.15 198);
    --color-accent: oklch(86% 0.19 148);
    --color-surface: oklch(15.5% 0.032 218);
    --color-surface-dark: oklch(12.5% 0.026 220);
    --color-surface-light: oklch(19% 0.038 216);
}
```

### DTCG tokens.json
```json
{
  "$schema": "https://design-tokens.github.io/community-group/format/",
  "color": {
    "paper": { "$value": "oklch(12.5% 0.026 220)", "$type": "color" },
    "ink": { "$value": "oklch(96% 0.018 198)", "$type": "color" },
    "accent": { "$value": "oklch(86% 0.19 148)", "$type": "color" }
  },
  "font": {
    "display": { "$value": "Space Grotesk", "$type": "fontFamily" },
    "body": { "$value": "IBM Plex Sans", "$type": "fontFamily" },
    "outlier": { "$value": "JetBrains Mono", "$type": "fontFamily" }
  }
}
```

### shadcn/ui variables
```css
:root {
    --background: 12.5% 0.026 220;
    --foreground: 96% 0.018 198;
    --primary: 86% 0.19 148;
    --primary-foreground: 14% 0.032 218;
    --muted: 58% 0.055 205;
    --muted-foreground: 67% 0.04 212;
    --border: 58% 0.055 205;
    --input: 58% 0.055 205;
    --ring: 82% 0.17 188;
    --radius: 8px;
}
```
