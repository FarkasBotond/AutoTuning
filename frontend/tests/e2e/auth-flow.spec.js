import { test, expect } from '@playwright/test'

test('user can login and gets redirected to home', async ({ page }) => {
  await page.route('**/api/login', async (route) => {
    await route.fulfill({
      status: 200,
      contentType: 'application/json',
      body: JSON.stringify({
        data: {
          token: 'pw-test-token',
          user: {
            id: 99,
            name: 'Playwright User',
            email: 'playwright@example.com',
            role: 'user'
          }
        }
      })
    })
  })

  await page.route('**/api/**', async (route) => {
    const url = route.request().url()

    if (url.endsWith('/api/login')) {
      await route.fallback()
      return
    }

    await route.fulfill({
      status: 200,
      contentType: 'application/json',
      body: JSON.stringify({ data: [] })
    })
  })

  await page.goto('/login')

  await page.getByLabel('Email').fill('playwright@example.com')
  await page.getByLabel('Jelszó').fill('secret12345')
  await page.getByRole('button', { name: 'Belépés' }).click()

  await expect.poll(async () => {
    const auth = await page.evaluate(() => localStorage.getItem('auth'))
    return auth ?? ''
  }).toContain('pw-test-token')

  await expect(page).toHaveURL('/')

  const persistedAuth = await page.evaluate(() => localStorage.getItem('auth'))
  expect(persistedAuth).toContain('pw-test-token')
})
