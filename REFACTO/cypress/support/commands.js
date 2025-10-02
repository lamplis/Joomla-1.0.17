// ***********************************************
// This example commands.js shows you how to
// create various custom commands and overwrite
// existing commands.
//
// For more comprehensive examples of custom
// commands please read more here:
// https://on.cypress.io/custom-commands
// ***********************************************

// Custom commands for Joomla 1.0 testing

/**
 * Login to Joomla admin panel
 */
Cypress.Commands.add('loginAsAdmin', (username = Cypress.env('adminUsername'), password = Cypress.env('adminPassword')) => {
  cy.visit('/administrator')
  cy.get('input[name="username"]').clear().type(username)
  cy.get('input[name="passwd"]').clear().type(password)
  cy.get('input[type="submit"]').click()
  cy.url().should('include', '/administrator/index.php')
  cy.waitForJoomlaLoad()
})

/**
 * Login to Joomla frontend
 */
Cypress.Commands.add('loginAsUser', (username, password) => {
  cy.visit('/index.php?option=com_login')
  cy.get('input[name="username"]').clear().type(username)
  cy.get('input[name="passwd"]').clear().type(password)
  cy.get('input[type="submit"]').click()
  cy.waitForJoomlaLoad()
})

/**
 * Logout from Joomla
 */
Cypress.Commands.add('logout', () => {
  cy.visit('/index.php?option=logout')
  cy.url().should('not.include', '/administrator')
})

/**
 * Wait for Joomla to fully load
 */
Cypress.Commands.add('waitForJoomlaLoad', () => {
  cy.get('body').should('be.visible')
  cy.wait(1000) // Additional wait for any JavaScript
})

/**
 * Check if Joomla is running
 */
Cypress.Commands.add('checkJoomlaVersion', () => {
  cy.get('body').then(($body) => {
    if ($body.text().includes('Joomla')) {
      cy.log('Joomla site detected')
    }
  })
})

/**
 * Navigate to a specific menu item
 */
Cypress.Commands.add('navigateToMenu', (menuItem) => {
  cy.get('a').contains(menuItem).click()
  cy.waitForJoomlaLoad()
})

/**
 * Fill a form with data
 */
Cypress.Commands.add('fillForm', (formData) => {
  Object.keys(formData).forEach(key => {
    cy.get(`[name="${key}"]`).clear().type(formData[key])
  })
})

/**
 * Submit a form
 */
Cypress.Commands.add('submitForm', (formSelector = 'form') => {
  cy.get(formSelector).submit()
  cy.waitForJoomlaLoad()
})

/**
 * Upload a file
 */
Cypress.Commands.add('uploadFile', (selector, filePath) => {
  cy.get(selector).selectFile(filePath)
})

/**
 * Go to admin panel
 */
Cypress.Commands.add('goToAdmin', () => {
  cy.visit('/administrator')
  cy.waitForJoomlaLoad()
})

/**
 * Go to frontend
 */
Cypress.Commands.add('goToFrontend', () => {
  cy.visit('/')
  cy.waitForJoomlaLoad()
})

/**
 * Go to a specific component
 */
Cypress.Commands.add('goToComponent', (component) => {
  cy.visit(`/administrator/index.php?option=${component}`)
  cy.waitForJoomlaLoad()
})

/**
 * Create test content
 */
Cypress.Commands.add('createTestContent', (title, content) => {
  cy.loginAsAdmin()
  cy.visit('/administrator/index.php?option=com_content&task=new')
  cy.fillForm({
    title: title,
    introtext: content
  })
  cy.submitForm()
  cy.shouldContainText('body', 'Content saved')
})

/**
 * Delete test content
 */
Cypress.Commands.add('deleteTestContent', (title) => {
  cy.loginAsAdmin()
  cy.visit('/administrator/index.php?option=com_content')
  cy.get('input[name="search"]').clear().type(title)
  cy.get('input[name="search"]').type('{enter}')
  cy.get('input[name="cid[]"]').first().check()
  cy.get('input[name="remove"]').click()
  cy.get('input[name="confirm"]').click()
})

/**
 * Publish content
 */
Cypress.Commands.add('publishContent', (title) => {
  cy.loginAsAdmin()
  cy.visit('/administrator/index.php?option=com_content')
  cy.get('input[name="search"]').clear().type(title)
  cy.get('input[name="search"]').type('{enter}')
  cy.get('input[name="cid[]"]').first().check()
  cy.get('select[name="task"]').select('publish')
  cy.get('input[name="submit"]').click()
})

/**
 * Unpublish content
 */
Cypress.Commands.add('unpublishContent', (title) => {
  cy.loginAsAdmin()
  cy.visit('/administrator/index.php?option=com_content')
  cy.get('input[name="search"]').clear().type(title)
  cy.get('input[name="search"]').type('{enter}')
  cy.get('input[name="cid[]"]').first().check()
  cy.get('select[name="task"]').select('unpublish')
  cy.get('input[name="submit"]').click()
})

/**
 * Create test user
 */
Cypress.Commands.add('createTestUser', (userData) => {
  cy.loginAsAdmin()
  cy.visit('/administrator/index.php?option=com_users&task=new')
  cy.fillForm(userData)
  cy.submitForm()
})

/**
 * Delete test user
 */
Cypress.Commands.add('deleteTestUser', (username) => {
  cy.loginAsAdmin()
  cy.visit('/administrator/index.php?option=com_users')
  cy.get('input[name="search"]').clear().type(username)
  cy.get('input[name="search"]').type('{enter}')
  cy.get('input[name="cid[]"]').first().check()
  cy.get('input[name="remove"]').click()
  cy.get('input[name="confirm"]').click()
})

/**
 * Enable module
 */
Cypress.Commands.add('enableModule', (moduleName) => {
  cy.loginAsAdmin()
  cy.visit('/administrator/index.php?option=com_modules')
  cy.get('input[name="search"]').clear().type(moduleName)
  cy.get('input[name="search"]').type('{enter}')
  cy.get('input[name="cid[]"]').first().check()
  cy.get('select[name="task"]').select('publish')
  cy.get('input[name="submit"]').click()
})

/**
 * Disable module
 */
Cypress.Commands.add('disableModule', (moduleName) => {
  cy.loginAsAdmin()
  cy.visit('/administrator/index.php?option=com_modules')
  cy.get('input[name="search"]').clear().type(moduleName)
  cy.get('input[name="search"]').type('{enter}')
  cy.get('input[name="cid[]"]').first().check()
  cy.get('select[name="task"]').select('unpublish')
  cy.get('input[name="submit"]').click()
})

/**
 * Handle Joomla errors
 */
Cypress.Commands.add('handleJoomlaError', () => {
  cy.get('body').then(($body) => {
    if ($body.text().includes('Fatal error') || $body.text().includes('Parse error')) {
      cy.log('Joomla error detected')
      cy.screenshot('joomla-error')
    }
  })
})

/**
 * Check page performance
 */
Cypress.Commands.add('checkPagePerformance', () => {
  cy.window().then((win) => {
    const performance = win.performance
    const navigation = performance.getEntriesByType('navigation')[0]
    
    cy.log('Page load time:', navigation.loadEventEnd - navigation.loadEventStart)
    cy.log('DOM content loaded:', navigation.domContentLoadedEventEnd - navigation.domContentLoadedEventStart)
  })
})

/**
 * Seed test data
 */
Cypress.Commands.add('seedTestData', () => {
  cy.request('POST', '/test-api/seed-data')
})

/**
 * Cleanup test data
 */
Cypress.Commands.add('cleanupTestData', () => {
  cy.request('POST', '/test-api/cleanup-data')
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
