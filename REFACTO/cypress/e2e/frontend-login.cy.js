/**
 * Frontend Login Tests
 * 
 * Tests the Joomla 1.0 frontend login functionality
 */

describe('Joomla 1.0 Frontend Login', () => {
  beforeEach(() => {
    cy.visit('/')
    cy.waitForJoomlaLoad()
  })

  it('should display login module', () => {
    cy.get('body').should('contain.text', 'Login')
    cy.get('input[name="username"]').should('be.visible')
    cy.get('input[name="passwd"]').should('be.visible')
  })

  it('should login with valid user credentials', () => {
    cy.loginAsUser('testuser', 'testpass123')
    cy.get('body').should('contain.text', 'Logout')
  })

  it('should show error with invalid credentials', () => {
    cy.get('input[name="username"]').type('invalid')
    cy.get('input[name="passwd"]').type('invalid')
    cy.get('input[type="submit"]').click()
    cy.get('body').should('contain.text', 'error')
  })

  it('should logout successfully', () => {
    cy.loginAsUser('testuser', 'testpass123')
    cy.get('a').contains('Logout').click()
    cy.get('body').should('contain.text', 'Login')
  })

  it('should redirect to login page when accessing protected content', () => {
    cy.visit('/index.php?option=com_user&task=edit')
    cy.url().should('include', 'com_login')
  })

  it('should remember login state across page navigation', () => {
    cy.loginAsUser('testuser', 'testpass123')
    cy.visit('/')
    cy.get('body').should('contain.text', 'Logout')
  })

  it('should handle password reset', () => {
    cy.visit('/index.php?option=com_login')
    cy.get('a').contains('Forgot your password?').click()
    cy.get('input[name="email"]').type('test@example.com')
    cy.get('input[type="submit"]').click()
    cy.get('body').should('contain.text', 'password')
  })

  it('should handle user registration', () => {
    cy.visit('/index.php?option=com_registration')
    cy.get('input[name="name"]').type('New User')
    cy.get('input[name="username"]').type('newuser')
    cy.get('input[name="email"]').type('newuser@example.com')
    cy.get('input[name="password"]').type('newpass123')
    cy.get('input[name="password2"]').type('newpass123')
    cy.get('input[type="submit"]').click()
    cy.get('body').should('contain.text', 'registration')
  })
})
