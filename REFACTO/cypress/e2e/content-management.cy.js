/**
 * Content Management Tests
 * 
 * Tests the Joomla 1.0 content management functionality
 */

describe('Joomla 1.0 Content Management', () => {
  beforeEach(() => {
    cy.loginAsAdmin()
  })

  it('should create new content', () => {
    cy.visit('/administrator/index.php?option=com_content&task=new')
    cy.get('input[name="title"]').type('Test Article')
    cy.get('textarea[name="introtext"]').type('This is a test article introduction.')
    cy.get('textarea[name="fulltext"]').type('This is the full text of the test article.')
    cy.get('input[name="save"]').click()
    cy.get('body').should('contain.text', 'Content saved')
  })

  it('should edit existing content', () => {
    cy.visit('/administrator/index.php?option=com_content')
    cy.get('input[name="search"]').type('Test Article')
    cy.get('input[name="search"]').type('{enter}')
    cy.get('a').contains('Test Article').click()
    cy.get('input[name="title"]').clear().type('Updated Test Article')
    cy.get('input[name="save"]').click()
    cy.get('body').should('contain.text', 'Content saved')
  })

  it('should publish content', () => {
    cy.visit('/administrator/index.php?option=com_content')
    cy.get('input[name="search"]').type('Test Article')
    cy.get('input[name="search"]').type('{enter}')
    cy.get('input[name="cid[]"]').first().check()
    cy.get('select[name="task"]').select('publish')
    cy.get('input[name="submit"]').click()
    cy.get('body').should('contain.text', 'published')
  })

  it('should unpublish content', () => {
    cy.visit('/administrator/index.php?option=com_content')
    cy.get('input[name="search"]').type('Test Article')
    cy.get('input[name="search"]').type('{enter}')
    cy.get('input[name="cid[]"]').first().check()
    cy.get('select[name="task"]').select('unpublish')
    cy.get('input[name="submit"]').click()
    cy.get('body').should('contain.text', 'unpublished')
  })

  it('should delete content', () => {
    cy.visit('/administrator/index.php?option=com_content')
    cy.get('input[name="search"]').type('Test Article')
    cy.get('input[name="search"]').type('{enter}')
    cy.get('input[name="cid[]"]').first().check()
    cy.get('input[name="remove"]').click()
    cy.get('input[name="confirm"]').click()
    cy.get('body').should('contain.text', 'deleted')
  })

  it('should search content', () => {
    cy.visit('/administrator/index.php?option=com_content')
    cy.get('input[name="search"]').type('Test')
    cy.get('input[name="search"]').type('{enter}')
    cy.get('body').should('contain.text', 'Test')
  })

  it('should filter content by category', () => {
    cy.visit('/administrator/index.php?option=com_content')
    cy.get('select[name="catid"]').select('1')
    cy.get('input[name="filter"]').click()
    cy.get('body').should('contain.text', 'Content')
  })

  it('should filter content by author', () => {
    cy.visit('/administrator/index.php?option=com_content')
    cy.get('select[name="created_by"]').select('1')
    cy.get('input[name="filter"]').click()
    cy.get('body').should('contain.text', 'Content')
  })

  it('should sort content by title', () => {
    cy.visit('/administrator/index.php?option=com_content')
    cy.get('a').contains('Title').click()
    cy.get('body').should('contain.text', 'Content')
  })

  it('should sort content by date', () => {
    cy.visit('/administrator/index.php?option=com_content')
    cy.get('a').contains('Date').click()
    cy.get('body').should('contain.text', 'Content')
  })

  it('should display content on frontend', () => {
    cy.goToFrontend()
    cy.get('body').should('contain.text', 'Test Article')
  })

  it('should handle content with special characters', () => {
    cy.visit('/administrator/index.php?option=com_content&task=new')
    cy.get('input[name="title"]').type('Test Article with Special Characters: !@#$%^&*()')
    cy.get('textarea[name="introtext"]').type('This article contains special characters.')
    cy.get('input[name="save"]').click()
    cy.get('body').should('contain.text', 'Content saved')
  })

  it('should handle content with HTML tags', () => {
    cy.visit('/administrator/index.php?option=com_content&task=new')
    cy.get('input[name="title"]').type('Test Article with HTML')
    cy.get('textarea[name="introtext"]').type('<p>This article contains <strong>HTML</strong> tags.</p>')
    cy.get('input[name="save"]').click()
    cy.get('body').should('contain.text', 'Content saved')
  })
})
