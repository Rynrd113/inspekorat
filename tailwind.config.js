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
        primary: {
          50: '#eff6ff',
          100: '#dbeafe',
          200: '#bfdbfe',
          300: '#93c5fd',
          400: '#60a5fa',
          500: '#3b82f6',
          600: '#2563eb',
          700: '#1d4ed8',
          800: '#1e40af',
          900: '#1e3a8a',
          950: '#172554'
        },
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
        'admin-sidebar': 'linear-gradient(135deg, #1e3a8a 0%, #3730a3 100%)',
        'admin-card-header': 'linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%)',
        'btn-primary': 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)',
        'btn-primary-hover': 'linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%)'
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