/**
 * Admin Login Tests
 * 
 * Tests the Joomla 1.0 administrator login functionality
 */

describe('Joomla 1.0 Admin Login', () => {
  beforeEach(() => {
    cy.visit('/administrator')
    cy.waitForJoomlaLoad()
  })

  it('should display login form', () => {
    cy.get('form').should('be.visible')
    cy.get('input[name="username"]').should('be.visible')
    cy.get('input[name="passwd"]').should('be.visible')
    cy.get('input[type="submit"]').should('be.visible')
  })

  it('should login with valid credentials', () => {
    cy.loginAsAdmin()
    cy.url().should('include', '/administrator/index.php')
    cy.get('body').should('contain.text', 'Control Panel')
  })

  it('should show error with invalid credentials', () => {
    cy.get('input[name="username"]').type('invalid')
    cy.get('input[name="passwd"]').type('invalid')
    cy.get('input[type="submit"]').click()
    cy.get('body').should('contain.text', 'error')
  })

  it('should logout successfully', () => {
    cy.loginAsAdmin()
    cy.logout()
    cy.url().should('not.include', '/administrator')
  })

  it('should redirect to login when accessing admin without authentication', () => {
    cy.visit('/administrator/index.php?option=com_content')
    cy.url().should('include', '/administrator')
    cy.get('form').should('be.visible')
  })

  it('should maintain session across page navigation', () => {
    cy.loginAsAdmin()
    cy.visit('/administrator/index.php?option=com_content')
    cy.url().should('include', '/administrator')
    cy.get('body').should('contain.text', 'Content')
  })

  it('should handle session timeout', () => {
    cy.loginAsAdmin()
    // Simulate session timeout by clearing cookies
    cy.clearCookies()
    cy.visit('/administrator/index.php?option=com_content')
    cy.url().should('include', '/administrator')
    cy.get('form').should('be.visible')
  })
})
