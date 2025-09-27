/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
    './resources/css/**/*.css',
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: [
          'Instrument Sans',
          'ui-sans-serif',
          'system-ui',
          'sans-serif',
          'Apple Color Emoji',
          'Segoe UI Emoji',
          'Segoe UI Symbol',
          'Noto Color Emoji'
        ],
        inter: [
          'Inter',
          'ui-sans-serif',
          'system-ui',
          '-apple-system',
          'BlinkMacSystemFont',
          'Segoe UI',
          'Roboto',
          'Helvetica Neue',
          'Arial',
          'Noto Sans',
          'sans-serif',
          'Apple Color Emoji',
          'Segoe UI Emoji',
          'Segoe UI Symbol',
          'Noto Color Emoji'
        ]
      },
      colors: {
        // Standard Government Color Palette - Limited & Accessible
        primary: {
          50: '#eff6ff',
          100: '#dbeafe',
          200: '#bfdbfe',
          300: '#93c5fd',
          400: '#60a5fa',
          500: 'var(--brand-primary, #3b82f6)',
          600: '#2563eb',
          700: '#1d4ed8',
          800: '#1e40af',
          900: '#1e3a8a',
          950: '#172554'
        },
        secondary: {
          50: '#ecfdf5',
          100: '#d1fae5',
          200: '#a7f3d0',
          300: '#6ee7b7',
          400: '#34d399',
          500: 'var(--brand-secondary, #10b981)',
          600: '#059669',
          700: '#047857',
          800: '#065f46',
          900: '#064e3b',
          950: '#022c22'
        },
        accent: {
          50: '#fef2f2',
          100: '#fee2e2',
          200: '#fecaca',
          300: '#fca5a5',
          400: '#f87171',
          500: 'var(--brand-accent, #ef4444)',
          600: '#dc2626',
          700: '#b91c1c',
          800: '#991b1b',
          900: '#7f1d1d',
          950: '#450a0a'
        },
        // Government standard colors (not customizable)
        gov: {
          blue: {
            50: '#eff6ff',
            500: '#1e40af', // Standard government blue
            900: '#1e3a8a'
          },
          green: {
            50: '#ecfdf5', 
            500: '#059669', // Standard government green
            900: '#064e3b'
          },
          red: {
            50: '#fef2f2',
            500: '#dc2626', // Standard government red
            900: '#7f1d1d'
          }
        },
        // Papua regional colors (preset only)
        papua: {
          50: '#f0f9ff',
          100: '#e0f2fe',
          200: '#bae6fd',
          300: '#7dd3fc',
          400: '#38bdf8',
          500: '#0ea5e9',
          600: '#0284c7',
          700: '#0369a1',
          800: '#075985',
          900: '#0c4a6e'
        },
        // Administrative interface colors (fixed)
        admin: {
          sidebar: '#1e3a8a',
          'sidebar-end': '#3730a3',
          content: '#f8fafc',
          card: '#ffffff',
          'card-header': '#f8fafc',
          'card-border': '#e5e7eb'
        }
      },
      backgroundImage: {
        // Fixed gradients for admin interface
        'admin-sidebar': 'linear-gradient(135deg, var(--brand-primary, #1e3a8a) 0%, #3730a3 100%)',
        'admin-card-header': 'linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%)',
        
        // Dynamic branding gradients (controlled by presets)
        'brand-gradient': 'var(--brand-gradient, linear-gradient(135deg, #1e40af 0%, #3730a3 100%))',
        'brand-primary': 'linear-gradient(135deg, var(--brand-primary, #3b82f6) 0%, var(--brand-primary, #2563eb) 100%)',
        
        // Button gradients (accessibility-validated)
        'btn-primary': 'linear-gradient(135deg, var(--brand-primary, #3b82f6) 0%, #2563eb 100%)',
        'btn-primary-hover': 'linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%)',
        'btn-secondary': 'linear-gradient(135deg, var(--brand-secondary, #10b981) 0%, #059669 100%)',
        'btn-accent': 'linear-gradient(135deg, var(--brand-accent, #ef4444) 0%, #dc2626 100%)'
      },
      boxShadow: {
        'admin-card': '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)',
        'admin-sidebar': '0 4px 6px -1px rgba(0, 0, 0, 0.1)',
        'admin-modal': '0 25px 50px -12px rgba(0, 0, 0, 0.25)',
        'btn-hover': '0 4px 6px -1px rgba(0, 0, 0, 0.1)'
      },
      backdropBlur: {
        'modal': '4px'
      },
      animation: {
        'fade-in': 'fadeIn 0.3s ease-out'
      },
      keyframes: {
        fadeIn: {
          '0%': {
            opacity: '0',
            transform: 'translateY(10px)'
          },
          '100%': {
            opacity: '1',
            transform: 'translateY(0)'
          }
        }
      },
      screens: {
        'print': { 'raw': 'print' }
      }
    }
  },
  plugins: [
    // Add any additional plugins here if needed
  ]
}