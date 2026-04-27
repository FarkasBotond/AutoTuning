import { test, expect } from '@playwright/test'

const setAuthState = (page, authPayload) => {
  return page.addInitScript((payload) => {
    localStorage.setItem('auth', JSON.stringify(payload))
  }, authPayload)
}

test.describe('Route guards', () => {
  test('unauthenticated user is redirected to login from admin page', async ({ page }) => {
    await page.goto('/admin/brands')

    await expect(page).toHaveURL(/\/login$/)
  })

  test('non-admin user is redirected to home from admin page', async ({ page }) => {
    await setAuthState(page, {
      token: 'fake-user-token',
      user: { id: 1, name: 'Normal User', email: 'user@example.com', role: 'user' }
    })

    await page.route('http://localhost/api/**', async (route) => {
      await route.fulfill({
        status: 200,
        contentType: 'application/json',
        body: JSON.stringify({ data: [] })
      })
    })

    await page.goto('/admin/brands')

    await expect(page).toHaveURL('/')
  })

  test('authenticated user can open profile page', async ({ page }) => {
    await setAuthState(page, {
      token: 'fake-user-token',
      user: { id: 2, name: 'Profile User', email: 'profile@example.com', role: 'user' }
    })

    await page.goto('/profile')

    await expect(page.getByRole('heading', { name: 'Profile User' })).toBeVisible()
    await expect(page.getByText('profile@example.com')).toBeVisible()
  })
})
