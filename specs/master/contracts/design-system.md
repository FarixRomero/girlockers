# Design System: Girl Lockers

**Brand Identity**: Empowering, Premium, Futuristic, Urban Street Dance
**Inspired by**: girllockers.jpg logo

---

## Color Palette

### Primary Colors

```css
--gl-pink-vibrant: #FF7BA9;      /* Main brand color - "Girls" energy */
--gl-pink-light: #FFB3D1;        /* Hover states, accents */
--gl-pink-dark: #F06292;         /* Active states */

--gl-cream-white: #FFFBF0;       /* "Lockers" elegance */
--gl-cream-light: #FFFFFF;       /* Pure white for text */

--gl-purple-deep: #3D4464;       /* Background sophistication */
--gl-purple-darker: #2A2E47;     /* Card backgrounds */
--gl-purple-darkest: #1A1D2E;    /* Deepest sections */

--gl-black-shadow: #0F1118;      /* Typography, borders */
```

### Semantic Colors

```css
--color-success: #10B981;        /* Green - access granted */
--color-warning: #F59E0B;        /* Amber - pending */
--color-error: #EF4444;          /* Red - validation errors */
--color-info: #3B82F6;           /* Blue - informational */
```

### Gradients

```css
--gradient-hero: linear-gradient(135deg, #3D4464 0%, #2A2E47 100%);
--gradient-card: linear-gradient(180deg, #2A2E47 0%, #3D4464 100%);
--gradient-pink: linear-gradient(90deg, #FF7BA9 0%, #F06292 100%);
--gradient-glow: radial-gradient(circle, rgba(255,123,169,0.2) 0%, transparent 70%);
```

---

## Typography

### Font Families

```css
/* Display/Headings - Bold, Modern */
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800;900&display=swap');
--font-display: 'Montserrat', sans-serif;

/* Body - Clean, Readable */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap');
--font-body: 'Inter', sans-serif;

/* Accent - Script for branding moments */
@import url('https://fonts.googleapis.com/css2?family=Pacifico&display=swap');
--font-accent: 'Pacifico', cursive;
```

### Type Scale (Mobile-First)

```css
/* Mobile (320px+) */
--text-xs: 0.75rem;      /* 12px */
--text-sm: 0.875rem;     /* 14px */
--text-base: 1rem;       /* 16px */
--text-lg: 1.125rem;     /* 18px */
--text-xl: 1.25rem;      /* 20px */
--text-2xl: 1.5rem;      /* 24px */
--text-3xl: 1.875rem;    /* 30px */
--text-4xl: 2.25rem;     /* 36px */
--text-5xl: 3rem;        /* 48px */

/* Desktop (768px+) */
--text-hero: 4rem;       /* 64px - Landing hero */
```

---

## Spacing System

```css
/* Tailwind-compatible 4px base */
--space-1: 0.25rem;   /* 4px */
--space-2: 0.5rem;    /* 8px */
--space-3: 0.75rem;   /* 12px */
--space-4: 1rem;      /* 16px */
--space-5: 1.25rem;   /* 20px */
--space-6: 1.5rem;    /* 24px */
--space-8: 2rem;      /* 32px */
--space-10: 2.5rem;   /* 40px */
--space-12: 3rem;     /* 48px */
--space-16: 4rem;     /* 64px */
--space-20: 5rem;     /* 80px */
--space-24: 6rem;     /* 96px */
```

---

## Components

### Buttons

```css
/* Primary CTA */
.btn-primary {
  background: linear-gradient(90deg, #FF7BA9 0%, #F06292 100%);
  color: #FFFBF0;
  font-weight: 700;
  border-radius: 9999px; /* Fully rounded */
  padding: 1rem 2rem;
  font-size: 1.125rem;
  box-shadow: 0 10px 25px rgba(255, 123, 169, 0.4);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.btn-primary:hover {
  box-shadow: 0 15px 35px rgba(255, 123, 169, 0.6);
  transform: translateY(-2px);
}

/* Secondary */
.btn-secondary {
  background: transparent;
  border: 2px solid #FF7BA9;
  color: #FF7BA9;
  /* ... similar properties */
}

/* Ghost (for dark backgrounds) */
.btn-ghost {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  color: #FFFBF0;
  /* ... */
}
```

### Cards

```css
.card-premium {
  background: linear-gradient(180deg, #2A2E47 0%, #3D4464 100%);
  border-radius: 1.5rem;
  padding: 2rem;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  border: 1px solid rgba(255, 123, 169, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card-premium:hover {
  transform: translateY(-8px);
  box-shadow: 0 25px 70px rgba(255, 123, 169, 0.2);
}

/* Glass morphism variant */
.card-glass {
  background: rgba(61, 68, 100, 0.6);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  /* ... */
}
```

### Navigation

```css
/* Mobile bottom nav */
.nav-mobile {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  background: rgba(42, 46, 71, 0.95);
  backdrop-filter: blur(20px);
  border-top: 1px solid rgba(255, 123, 169, 0.2);
  padding: 1rem;
  z-index: 50;
}

.nav-item {
  min-height: 44px;
  min-width: 44px;
  color: #FFFBF0;
  opacity: 0.6;
  transition: opacity 0.2s, transform 0.2s;
}

.nav-item.active {
  opacity: 1;
  color: #FF7BA9;
  transform: scale(1.1);
}
```

---

## Landing Page Sections

### Hero Section

```html
<section class="hero">
  <div class="hero-content">
    <h1 class="hero-title">
      <span class="text-pink-gradient">Empodera</span>
      <span class="text-cream">tu movimiento</span>
    </h1>
    <p class="hero-subtitle">
      Escuela Internacional de Locking para chicas que quieren conquistar el mundo del street dance
    </p>
    <div class="hero-cta">
      <button class="btn-primary">Comienza Gratis</button>
      <button class="btn-secondary">Ver Clases</button>
    </div>
  </div>
  <div class="hero-visual">
    <!-- Animated dancer silhouette with glow effects -->
  </div>
</section>
```

**Style Goals**:
- Large text (48px mobile, 64px desktop)
- Pink gradient on "Empodera"
- Glow effect around CTA buttons
- Smooth fade-in animation on load

### Secciones Clarificadas

1. **Visión**: Misión de empoderar chicas lockers mundialmente
2. **Beneficios**: Acceso 24/7, instructores top, comunidad global
3. **Niveles**: Beginner, Intermediate, Advanced con preview cards
4. **Instructores**: Cards con foto, bio, estilo de enseñanza
5. **Comunidad**: Testimonios, estadísticas, galería de alumnas

---

## Animation Principles

### Page Transitions (Livewire wire:navigate)

```css
/* Fade + slide up */
@keyframes fadeSlideUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.page-enter {
  animation: fadeSlideUp 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}
```

### Micro-interactions

```css
/* Hover lift */
.hover-lift {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.hover-lift:hover {
  transform: translateY(-4px);
}

/* Pulse glow (for CTAs) */
@keyframes pulseGlow {
  0%, 100% {
    box-shadow: 0 0 20px rgba(255, 123, 169, 0.4);
  }
  50% {
    box-shadow: 0 0 40px rgba(255, 123, 169, 0.6);
  }
}

.btn-pulse {
  animation: pulseGlow 2s infinite;
}
```

### Loading States

```css
/* Skeleton shimmer */
@keyframes shimmer {
  0% {
    background-position: -1000px 0;
  }
  100% {
    background-position: 1000px 0;
  }
}

.skeleton {
  background: linear-gradient(
    90deg,
    #2A2E47 0%,
    #3D4464 50%,
    #2A2E47 100%
  );
  background-size: 1000px 100%;
  animation: shimmer 2s infinite;
}
```

---

## Responsive Breakpoints

```css
/* Mobile-first (default) */
/* 320px - 639px */

/* Tablet */
@media (min-width: 640px) { /* sm */ }

/* Laptop */
@media (min-width: 1024px) { /* lg */ }

/* Desktop */
@media (min-width: 1280px) { /* xl */ }
```

---

## Accessibility

```css
/* High contrast mode */
@media (prefers-contrast: high) {
  :root {
    --gl-pink-vibrant: #FF4D91;
    --gl-cream-white: #FFFFFF;
  }
}

/* Reduced motion */
@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    transition-duration: 0.01ms !important;
  }
}

/* Focus states */
*:focus-visible {
  outline: 3px solid #FF7BA9;
  outline-offset: 2px;
}
```

---

## Layout Patterns

### Container

```css
.container {
  width: 100%;
  margin: 0 auto;
  padding: 0 1rem;
}

@media (min-width: 640px) {
  .container {
    max-width: 640px;
    padding: 0 1.5rem;
  }
}

@media (min-width: 1024px) {
  .container {
    max-width: 1024px;
  }
}

@media (min-width: 1280px) {
  .container {
    max-width: 1280px;
  }
}
```

### Section Spacing

```css
.section {
  padding: 4rem 0; /* Mobile */
}

@media (min-width: 1024px) {
  .section {
    padding: 6rem 0; /* Desktop */
  }
}
```

---

## Tailwind Config Integration

```javascript
// tailwind.config.js
module.exports = {
  theme: {
    extend: {
      colors: {
        'pink-vibrant': '#FF7BA9',
        'pink-light': '#FFB3D1',
        'pink-dark': '#F06292',
        'cream': '#FFFBF0',
        'purple-deep': '#3D4464',
        'purple-darker': '#2A2E47',
        'purple-darkest': '#1A1D2E',
      },
      fontFamily: {
        display: ['Montserrat', 'sans-serif'],
        body: ['Inter', 'sans-serif'],
        accent: ['Pacifico', 'cursive'],
      },
      animation: {
        'fade-up': 'fadeSlideUp 0.4s ease-out',
        'pulse-glow': 'pulseGlow 2s infinite',
      },
      boxShadow: {
        'glow': '0 0 40px rgba(255, 123, 169, 0.4)',
        'glow-lg': '0 0 60px rgba(255, 123, 169, 0.6)',
      },
    },
  },
}
```

---

## Implementation Notes

- All components use Tailwind utility classes + custom CSS variables
- Animations powered by Alpine.js (built-in with Livewire)
- Optional Motion.js for hero section parallax
- Mobile-first: Design for 320px, enhance for desktop
- Premium feel: Heavy use of shadows, gradients, glass morphism
- Futuristic: Rounded corners, glow effects, smooth transitions

**Design Philosophy**: "Empoderamiento visual" - cada elemento debe sentirse premium, accesible, y energizante.

