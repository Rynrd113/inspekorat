# Frontend Testing & Quality Assurance Guide
## Portal Inspektorat Papua Tengah

### Table of Contents
1. [Testing Strategy](#testing-strategy)
2. [Unit Testing](#unit-testing)
3. [Integration Testing](#integration-testing)
4. [Browser Testing](#browser-testing)
5. [Performance Testing](#performance-testing)
6. [Accessibility Testing](#accessibility-testing)
7. [Code Quality](#code-quality)
8. [CI/CD Pipeline](#cicd-pipeline)

## Testing Strategy

### Testing Pyramid
```
    /\     E2E Tests (Few)
   /  \    
  /____\   Integration Tests (Some)
 /      \  
/________\  Unit Tests (Many)
```

### Test Types
- **Unit Tests**: Test individual functions/components
- **Integration Tests**: Test component interactions
- **E2E Tests**: Test complete user workflows
- **Visual Tests**: Test UI appearance consistency
- **Performance Tests**: Test loading times and responsiveness

## Unit Testing

### JavaScript Unit Tests with Jest

#### Setup Jest
```bash
npm install --save-dev jest @testing-library/jest-dom
```

#### Jest Configuration (`jest.config.js`)
```javascript
module.exports = {
  testEnvironment: 'jsdom',
  setupFilesAfterEnv: ['<rootDir>/tests/jest.setup.js'],
  testMatch: [
    '<rootDir>/tests/js/**/*.test.js',
    '<rootDir>/resources/js/**/*.test.js'
  ],
  collectCoverageFrom: [
    'resources/js/**/*.js',
    '!resources/js/vendor/**',
    '!**/node_modules/**'
  ],
  coverageDirectory: 'tests/coverage',
  coverageReporters: ['text', 'html', 'lcov']
};
```

#### Jest Setup File (`tests/jest.setup.js`)
```javascript
import '@testing-library/jest-dom';

// Mock global objects
global.axios = {
  get: jest.fn(() => Promise.resolve({ data: {} })),
  post: jest.fn(() => Promise.resolve({ data: {} })),
  put: jest.fn(() => Promise.resolve({ data: {} })),
  delete: jest.fn(() => Promise.resolve({ data: {} }))
};

// Mock Laravel Mix
global.mix = {
  js: jest.fn(),
  css: jest.fn(),
  sass: jest.fn(),
  options: jest.fn()
};
```

#### Example JavaScript Test
```javascript
// tests/js/utils/validation.test.js
import { validateEmail, validatePhone } from '../../../resources/js/utils/validation';

describe('Validation Utils', () => {
  describe('validateEmail', () => {
    test('should return true for valid email', () => {
      expect(validateEmail('test@example.com')).toBe(true);
      expect(validateEmail('user.name+tag@domain.co.id')).toBe(true);
    });

    test('should return false for invalid email', () => {
      expect(validateEmail('invalid-email')).toBe(false);
      expect(validateEmail('test@')).toBe(false);
      expect(validateEmail('')).toBe(false);
    });
  });

  describe('validatePhone', () => {
    test('should return true for valid Indonesian phone', () => {
      expect(validatePhone('081234567890')).toBe(true);
      expect(validatePhone('+6281234567890')).toBe(true);
    });

    test('should return false for invalid phone', () => {
      expect(validatePhone('123')).toBe(false);
      expect(validatePhone('081234')).toBe(false);
    });
  });
});
```

#### Form Component Test
```javascript
// tests/js/components/ContactForm.test.js
import { fireEvent, screen } from '@testing-library/dom';
import { ContactForm } from '../../../resources/js/components/ContactForm';

describe('ContactForm Component', () => {
  let container;

  beforeEach(() => {
    container = document.createElement('div');
    document.body.appendChild(container);
    container.innerHTML = `
      <form id="contact-form">
        <input type="text" name="name" required>
        <input type="email" name="email" required>
        <textarea name="message" required></textarea>
        <button type="submit">Submit</button>
      </form>
    `;
    
    new ContactForm('#contact-form');
  });

  afterEach(() => {
    document.body.removeChild(container);
  });

  test('should validate required fields', () => {
    const form = container.querySelector('#contact-form');
    const submitBtn = container.querySelector('button[type="submit"]');
    
    fireEvent.click(submitBtn);
    
    expect(form.checkValidity()).toBe(false);
  });

  test('should submit form with valid data', async () => {
    const nameInput = container.querySelector('input[name="name"]');
    const emailInput = container.querySelector('input[name="email"]');
    const messageInput = container.querySelector('textarea[name="message"]');
    const form = container.querySelector('#contact-form');

    fireEvent.input(nameInput, { target: { value: 'John Doe' } });
    fireEvent.input(emailInput, { target: { value: 'john@example.com' } });
    fireEvent.input(messageInput, { target: { value: 'Test message' } });

    const mockSubmit = jest.fn();
    form.addEventListener('submit', mockSubmit);
    
    fireEvent.submit(form);
    
    expect(mockSubmit).toHaveBeenCalled();
  });
});
```

### CSS Unit Tests with Quixote

#### Setup Quixote
```bash
npm install --save-dev quixote
```

#### CSS Test Example
```javascript
// tests/js/css/layout.test.js
import quixote from 'quixote';

describe('Layout CSS', () => {
  let frame;

  before((done) => {
    frame = quixote.createFrame({
      stylesheet: '/css/app.css'
    }, done);
  });

  after(() => {
    frame.remove();
  });

  beforeEach(() => {
    frame.reset();
  });

  it('should have proper header layout', () => {
    frame.add(`
      <header class="main-header">
        <div class="container">
          <nav class="navbar">
            <div class="navbar-brand">Logo</div>
            <div class="navbar-nav">Menu</div>
          </nav>
        </div>
      </header>
    `);

    const header = frame.get('.main-header');
    const container = frame.get('.container');
    
    header.assert({
      width: frame.viewport().width
    });

    container.assert({
      maxWidth: '1200px'
    });
  });
});
```

## Integration Testing

### Laravel Dusk for Browser Testing

#### Setup Dusk
```bash
composer require --dev laravel/dusk
php artisan dusk:install
```

#### Dusk Configuration
```php
// tests/DuskTestCase.php
<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Laravel\Dusk\TestCase as BaseTestCase;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;

    public static function prepare()
    {
        if (! static::runningInSail()) {
            static::startChromeDriver();
        }
    }

    protected function driver()
    {
        $options = (new ChromeOptions)->addArguments(collect([
            '--window-size=1920,1080',
            '--disable-gpu',
            '--headless',
            '--no-sandbox',
            '--disable-dev-shm-usage',
        ])->all());

        return RemoteWebDriver::create(
            $_ENV['DUSK_DRIVER_URL'] ?? 'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
        );
    }
}
```

#### Browser Test Example
```php
// tests/Browser/WbsSubmissionTest.php
<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class WbsSubmissionTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testUserCanSubmitWbsReport()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                    ->waitFor('.wbs-form')
                    ->type('judul', 'Test Report Title')
                    ->type('isi_laporan', 'This is a test report content')
                    ->type('nama_pelapor', 'John Doe')
                    ->type('kontak', 'john@example.com')
                    ->press('Submit Report')
                    ->waitFor('.alert-success')
                    ->assertSee('Laporan berhasil dikirim');
        });
    }

    public function testFormValidationWorks()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                    ->press('Submit Report')
                    ->waitFor('.alert-danger')
                    ->assertSee('Judul wajib diisi');
        });
    }

    public function testResponsiveLayout()
    {
        $this->browse(function (Browser $browser) {
            // Test mobile view
            $browser->resize(375, 667)
                    ->visit('/wbs')
                    ->waitFor('.wbs-form')
                    ->assertVisible('.mobile-menu-toggle');

            // Test desktop view
            $browser->resize(1920, 1080)
                    ->refresh()
                    ->waitFor('.wbs-form')
                    ->assertVisible('.desktop-nav');
        });
    }
}
```

### API Integration Tests
```php
// tests/Feature/Api/WbsApiTest.php
<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Wbs;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class WbsApiTest extends TestCase
{
    use RefreshDatabase;

    public function testCanCreateWbsReport()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/wbs', [
            'judul' => 'Test Report',
            'isi_laporan' => 'Test content',
            'nama_pelapor' => 'John Doe',
            'kontak' => 'john@example.com'
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'id',
                    'judul',
                    'status',
                    'created_at'
                ]);

        $this->assertDatabaseHas('wbs', [
            'judul' => 'Test Report',
            'nama_pelapor' => 'John Doe'
        ]);
    }

    public function testRequiresAuthentication()
    {
        $response = $this->postJson('/api/wbs', [
            'judul' => 'Test Report'
        ]);

        $response->assertStatus(401);
    }

    public function testValidatesRequiredFields()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/wbs', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors([
                    'judul',
                    'isi_laporan',
                    'nama_pelapor'
                ]);
    }
}
```

## Performance Testing

### Lighthouse CI Setup
```bash
npm install --save-dev @lhci/cli
```

#### Lighthouse CI Configuration (`.lighthouserc.js`)
```javascript
module.exports = {
  ci: {
    collect: {
      url: [
        'http://localhost:8000',
        'http://localhost:8000/wbs',
        'http://localhost:8000/info-kantor'
      ],
      numberOfRuns: 3,
      settings: {
        chromeFlags: '--no-sandbox --headless'
      }
    },
    assert: {
      assertions: {
        'categories:performance': ['warn', { minScore: 0.8 }],
        'categories:accessibility': ['error', { minScore: 0.9 }],
        'categories:best-practices': ['warn', { minScore: 0.8 }],
        'categories:seo': ['warn', { minScore: 0.8 }]
      }
    },
    upload: {
      target: 'temporary-public-storage'
    }
  }
};
```

### WebPageTest Integration
```javascript
// tests/performance/webpagetest.js
const WebPageTest = require('webpagetest');

const wpt = new WebPageTest('www.webpagetest.org', process.env.WPT_API_KEY);

async function runPerformanceTest() {
  const testUrl = 'https://your-domain.com';
  
  try {
    const result = await wpt.runTest(testUrl, {
      location: 'Dulles:Chrome',
      runs: 3,
      firstViewOnly: false,
      video: true
    });
    
    console.log('Test ID:', result.data.testId);
    console.log('Results URL:', result.data.summaryCSV);
    
    // Wait for test completion
    const finalResult = await wpt.getTestResults(result.data.testId);
    
    const metrics = finalResult.data.average.firstView;
    console.log('First Contentful Paint:', metrics.firstContentfulPaint);
    console.log('Largest Contentful Paint:', metrics.largestContentfulPaint);
    console.log('Speed Index:', metrics.SpeedIndex);
    
  } catch (error) {
    console.error('Performance test failed:', error);
  }
}

runPerformanceTest();
```

## Accessibility Testing

### axe-core Integration
```bash
npm install --save-dev @axe-core/cli axe-core
```

#### Accessibility Test with Dusk
```php
// tests/Browser/AccessibilityTest.php
<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AccessibilityTest extends DuskTestCase
{
    public function testHomePageAccessibility()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->runAxeAudit()
                    ->assertAxeClean();
        });
    }

    public function testWbsFormAccessibility()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/wbs')
                    ->runAxeAudit([
                        'tags' => ['wcag2a', 'wcag2aa']
                    ])
                    ->assertAxeClean();
        });
    }
}
```

#### Manual Accessibility Checklist
```markdown
# Accessibility Checklist

## Keyboard Navigation
- [ ] All interactive elements can be reached with Tab
- [ ] Tab order is logical
- [ ] Focus indicators are visible
- [ ] Escape key closes modals/dropdowns

## Screen Reader Support
- [ ] All images have alt text
- [ ] Form labels are properly associated
- [ ] Headings are in logical order (h1 → h2 → h3)
- [ ] ARIA labels for complex components

## Visual Design
- [ ] Color contrast ratio ≥ 4.5:1 for normal text
- [ ] Color contrast ratio ≥ 3:1 for large text
- [ ] Information not conveyed by color alone
- [ ] Text can be zoomed to 200% without horizontal scrolling

## Forms
- [ ] All form fields have labels
- [ ] Error messages are descriptive and associated with fields
- [ ] Required fields are clearly marked
- [ ] Success/error feedback is provided

## Media
- [ ] Videos have captions
- [ ] Audio content has transcripts
- [ ] Auto-playing media can be paused
```

## Code Quality

### ESLint Configuration
```javascript
// .eslintrc.js
module.exports = {
  env: {
    browser: true,
    es2021: true,
    jest: true
  },
  extends: [
    'eslint:recommended',
    '@typescript-eslint/recommended'
  ],
  parser: '@typescript-eslint/parser',
  parserOptions: {
    ecmaVersion: 12,
    sourceType: 'module'
  },
  rules: {
    'indent': ['error', 2],
    'linebreak-style': ['error', 'unix'],
    'quotes': ['error', 'single'],
    'semi': ['error', 'always'],
    'no-console': 'warn',
    'no-unused-vars': 'error',
    'prefer-const': 'error'
  }
};
```

### Prettier Configuration
```json
{
  "semi": true,
  "trailingComma": "es5",
  "singleQuote": true,
  "printWidth": 100,
  "tabWidth": 2,
  "useTabs": false
}
```

### Stylelint Configuration
```json
{
  "extends": [
    "stylelint-config-standard",
    "stylelint-config-recess-order"
  ],
  "rules": {
    "indentation": 2,
    "string-quotes": "single",
    "no-duplicate-selectors": true,
    "color-hex-case": "lower",
    "color-hex-length": "short",
    "selector-combinator-space-after": "always",
    "selector-attribute-quotes": "always",
    "declaration-block-trailing-semicolon": "always",
    "declaration-colon-space-before": "never",
    "declaration-colon-space-after": "always",
    "number-leading-zero": "always"
  }
}
```

## CI/CD Pipeline

### GitHub Actions Workflow
```yaml
# .github/workflows/frontend-tests.yml
name: Frontend Tests

on:
  push:
    branches: [ main, develop ]
  pull_request:
    branches: [ main ]

jobs:
  frontend-tests:
    runs-on: ubuntu-latest

    strategy:
      matrix:
        node-version: [18.x, 20.x]

    steps:
    - uses: actions/checkout@v3

    - name: Setup Node.js ${{ matrix.node-version }}
      uses: actions/setup-node@v3
      with:
        node-version: ${{ matrix.node-version }}
        cache: 'npm'

    - name: Install dependencies
      run: npm ci

    - name: Run ESLint
      run: npm run lint

    - name: Run Prettier check
      run: npm run format:check

    - name: Run Stylelint
      run: npm run lint:css

    - name: Run Jest tests
      run: npm run test:coverage

    - name: Upload coverage to Codecov
      uses: codecov/codecov-action@v3
      with:
        file: ./tests/coverage/lcov.info

    - name: Build assets
      run: npm run build

    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: 8.2

    - name: Install Composer dependencies
      run: composer install --prefer-dist --no-progress

    - name: Setup Laravel environment
      run: |
        cp .env.example .env
        php artisan key:generate

    - name: Run Dusk tests
      run: php artisan dusk

    - name: Run Lighthouse CI
      run: |
        npm install -g @lhci/cli
        php artisan serve &
        sleep 5
        lhci autorun

  accessibility-tests:
    runs-on: ubuntu-latest
    needs: frontend-tests

    steps:
    - uses: actions/checkout@v3

    - name: Setup Node.js
      uses: actions/setup-node@v3
      with:
        node-version: 18.x

    - name: Install axe-cli
      run: npm install -g @axe-core/cli

    - name: Start server
      run: |
        php artisan serve &
        sleep 10

    - name: Run accessibility tests
      run: |
        axe http://localhost:8000 --tags wcag2a,wcag2aa
        axe http://localhost:8000/wbs --tags wcag2a,wcag2aa
```

### Package.json Scripts
```json
{
  "scripts": {
    "dev": "vite",
    "build": "vite build",
    "test": "jest",
    "test:watch": "jest --watch",
    "test:coverage": "jest --coverage",
    "lint": "eslint resources/js/**/*.js",
    "lint:fix": "eslint resources/js/**/*.js --fix",
    "lint:css": "stylelint resources/css/**/*.css",
    "lint:css:fix": "stylelint resources/css/**/*.css --fix",
    "format": "prettier --write resources/js/**/*.js resources/css/**/*.css",
    "format:check": "prettier --check resources/js/**/*.js resources/css/**/*.css",
    "analyze": "webpack-bundle-analyzer public/build/manifest.json",
    "lighthouse": "lhci autorun",
    "test:e2e": "php artisan dusk",
    "test:all": "npm run lint && npm run test:coverage && npm run test:e2e"
  }
}
```

### Quality Gates
```javascript
// tests/quality-gates.js
const fs = require('fs');
const path = require('path');

// Coverage thresholds
const COVERAGE_THRESHOLDS = {
  statements: 80,
  branches: 70,
  functions: 80,
  lines: 80
};

// Performance thresholds
const PERFORMANCE_THRESHOLDS = {
  firstContentfulPaint: 2000,
  largestContentfulPaint: 4000,
  speedIndex: 3000,
  cumulativeLayoutShift: 0.1
};

function checkCoverage() {
  const coverageFile = path.join(__dirname, 'coverage/coverage-summary.json');
  
  if (!fs.existsSync(coverageFile)) {
    console.error('Coverage file not found');
    process.exit(1);
  }

  const coverage = JSON.parse(fs.readFileSync(coverageFile));
  const total = coverage.total;

  Object.keys(COVERAGE_THRESHOLDS).forEach(metric => {
    const actual = total[metric].pct;
    const threshold = COVERAGE_THRESHOLDS[metric];
    
    if (actual < threshold) {
      console.error(`Coverage ${metric}: ${actual}% < ${threshold}%`);
      process.exit(1);
    }
  });

  console.log('✅ All coverage thresholds met');
}

function checkPerformance() {
  const lighthouseFile = path.join(__dirname, 'lighthouse/lhr.json');
  
  if (!fs.existsSync(lighthouseFile)) {
    console.warn('Lighthouse report not found, skipping performance check');
    return;
  }

  const lhr = JSON.parse(fs.readFileSync(lighthouseFile));
  const metrics = lhr.audits;

  const checks = [
    {
      name: 'First Contentful Paint',
      actual: metrics['first-contentful-paint'].numericValue,
      threshold: PERFORMANCE_THRESHOLDS.firstContentfulPaint
    },
    {
      name: 'Largest Contentful Paint',
      actual: metrics['largest-contentful-paint'].numericValue,
      threshold: PERFORMANCE_THRESHOLDS.largestContentfulPaint
    },
    {
      name: 'Speed Index',
      actual: metrics['speed-index'].numericValue,
      threshold: PERFORMANCE_THRESHOLDS.speedIndex
    },
    {
      name: 'Cumulative Layout Shift',
      actual: metrics['cumulative-layout-shift'].numericValue,
      threshold: PERFORMANCE_THRESHOLDS.cumulativeLayoutShift
    }
  ];

  checks.forEach(check => {
    if (check.actual > check.threshold) {
      console.error(`${check.name}: ${check.actual} > ${check.threshold}`);
      process.exit(1);
    }
  });

  console.log('✅ All performance thresholds met');
}

// Run checks
checkCoverage();
checkPerformance();
```

---

*Testing guide last updated: January 2025*
*For questions about testing procedures, contact the QA team.*
