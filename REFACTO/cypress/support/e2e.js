// ***********************************************************
// This example support/e2e.js is processed and
// loaded automatically before your test files.
//
// This is a great place to put global configuration and
// behavior that modifies Cypress.
//
// You can change the location of this file or turn off
// automatically serving support files with the
// 'supportFile' configuration option.
//
// You can read more here:
// https://on.cypress.io/configuration
// ***********************************************************

// Import commands.js using ES2015 syntax:
import './commands'

// Alternatively you can use CommonJS syntax:
// require('./commands')

// Global configuration
Cypress.on('uncaught:exception', (err, runnable) => {
  // Prevent Cypress from failing on uncaught exceptions
  // This is useful for legacy Joomla 1.0 JavaScript errors
  console.log('Uncaught exception:', err)
  return false
})

// Set default viewport
Cypress.on('window:before:load', (win) => {
  // Disable service workers
  delete win.navigator.serviceWorker
})

// Custom assertions
Cypress.Commands.add('shouldBeVisible', (selector) => {
  cy.get(selector).should('be.visible')
})

Cypress.Commands.add('shouldContainText', (selector, text) => {
  cy.get(selector).should('contain.text', text)
})

Cypress.Commands.add('shouldHaveClass', (selector, className) => {
  cy.get(selector).should('have.class', className)
})

Cypress.Commands.add('shouldHaveAttribute', (selector, attribute, value) => {
  cy.get(selector).should('have.attr', attribute, value)
})

// Joomla-specific commands
Cypress.Commands.add('loginAsAdmin', () => {
  cy.visit('/administrator')
  cy.get('input[name="username"]').type(Cypress.env('adminUsername'))
  cy.get('input[name="passwd"]').type(Cypress.env('adminPassword'))
  cy.get('input[type="submit"]').click()
  cy.url().should('include', '/administrator/index.php')
})

Cypress.Commands.add('loginAsUser', (username, password) => {
  cy.visit('/index.php?option=com_login')
  cy.get('input[name="username"]').type(username)
  cy.get('input[name="passwd"]').type(password)
  cy.get('input[type="submit"]').click()
})

Cypress.Commands.add('logout', () => {
  cy.visit('/index.php?option=logout')
  cy.url().should('not.include', '/administrator')
})

Cypress.Commands.add('createTestContent', (title, content) => {
  cy.loginAsAdmin()
  cy.visit('/administrator/index.php?option=com_content&task=new')
  cy.get('input[name="title"]').type(title)
  cy.get('textarea[name="introtext"]').type(content)
  cy.get('input[name="save"]').click()
  cy.shouldContainText('body', 'Content saved')
})

Cypress.Commands.add('deleteTestContent', (title) => {
  cy.loginAsAdmin()
  cy.visit('/administrator/index.php?option=com_content')
  cy.get('input[name="search"]').type(title)
  cy.get('input[name="search"]').type('{enter}')
  cy.get('input[name="cid[]"]').first().check()
  cy.get('input[name="remove"]').click()
  cy.get('input[name="confirm"]').click()
})

Cypress.Commands.add('navigateToMenu', (menuItem) => {
  cy.get('a').contains(menuItem).click()
})

Cypress.Commands.add('waitForJoomlaLoad', () => {
  // Wait for Joomla to fully load
  cy.get('body').should('be.visible')
  cy.wait(1000) // Additional wait for any JavaScript
})

Cypress.Commands.add('checkJoomlaVersion', () => {
  cy.get('body').then(($body) => {
    if ($body.text().includes('Joomla')) {
      cy.log('Joomla site detected')
    }
  })
})

// Database helpers (for integration with PHP tests)
Cypress.Commands.add('seedTestData', () => {
  cy.request('POST', '/test-api/seed-data')
})

Cypress.Commands.add('cleanupTestData', () => {
  cy.request('POST', '/test-api/cleanup-data')
})

// File upload helpers
Cypress.Commands.add('uploadFile', (selector, filePath) => {
  cy.get(selector).selectFile(filePath)
})

// Form helpers
Cypress.Commands.add('fillForm', (formData) => {
  Object.keys(formData).forEach(key => {
    cy.get(`[name="${key}"]`).type(formData[key])
  })
})

Cypress.Commands.add('submitForm', (formSelector = 'form') => {
  cy.get(formSelector).submit()
})

// Navigation helpers
Cypress.Commands.add('goToAdmin', () => {
  cy.visit('/administrator')
})

Cypress.Commands.add('goToFrontend', () => {
  cy.visit('/')
})

Cypress.Commands.add('goToComponent', (component) => {
  cy.visit(`/administrator/index.php?option=${component}`)
})

// Content management helpers
Cypress.Commands.add('publishContent', (title) => {
  cy.loginAsAdmin()
  cy.visit('/administrator/index.php?option=com_content')
  cy.get('input[name="search"]').type(title)
  cy.get('input[name="search"]').type('{enter}')
  cy.get('input[name="cid[]"]').first().check()
  cy.get('select[name="task"]').select('publish')
  cy.get('input[name="submit"]').click()
})

Cypress.Commands.add('unpublishContent', (title) => {
  cy.loginAsAdmin()
  cy.visit('/administrator/index.php?option=com_content')
  cy.get('input[name="search"]').type(title)
  cy.get('input[name="search"]').type('{enter}')
  cy.get('input[name="cid[]"]').first().check()
  cy.get('select[name="task"]').select('unpublish')
  cy.get('input[name="submit"]').click()
})

// User management helpers
Cypress.Commands.add('createTestUser', (userData) => {
  cy.loginAsAdmin()
  cy.visit('/administrator/index.php?option=com_users&task=new')
  cy.fillForm(userData)
  cy.submitForm()
})

Cypress.Commands.add('deleteTestUser', (username) => {
  cy.loginAsAdmin()
  cy.visit('/administrator/index.php?option=com_users')
  cy.get('input[name="search"]').type(username)
  cy.get('input[name="search"]').type('{enter}')
  cy.get('input[name="cid[]"]').first().check()
  cy.get('input[name="remove"]').click()
  cy.get('input[name="confirm"]').click()
})

// Module management helpers
Cypress.Commands.add('enableModule', (moduleName) => {
  cy.loginAsAdmin()
  cy.visit('/administrator/index.php?option=com_modules')
  cy.get('input[name="search"]').type(moduleName)
  cy.get('input[name="search"]').type('{enter}')
  cy.get('input[name="cid[]"]').first().check()
  cy.get('select[name="task"]').select('publish')
  cy.get('input[name="submit"]').click()
})

Cypress.Commands.add('disableModule', (moduleName) => {
  cy.loginAsAdmin()
  cy.visit('/administrator/index.php?option=com_modules')
  cy.get('input[name="search"]').type(moduleName)
  cy.get('input[name="search"]').type('{enter}')
  cy.get('input[name="cid[]"]').first().check()
  cy.get('select[name="task"]').select('unpublish')
  cy.get('input[name="submit"]').click()
})

// Error handling
Cypress.Commands.add('handleJoomlaError', () => {
  cy.get('body').then(($body) => {
    if ($body.text().includes('Fatal error') || $body.text().includes('Parse error')) {
      cy.log('Joomla error detected')
      cy.screenshot('joomla-error')
    }
  })
})

// Performance monitoring
Cypress.Commands.add('checkPagePerformance', () => {
  cy.window().then((win) => {
    const performance = win.performance
    const navigation = performance.getEntriesByType('navigation')[0]
    
    cy.log('Page load time:', navigation.loadEventEnd - navigation.loadEventStart)
    cy.log('DOM content loaded:', navigation.domContentLoadedEventEnd - navigation.domContentLoadedEventStart)
  })
})
