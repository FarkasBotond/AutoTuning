import { test, expect } from '@playwright/test'

const setAdminAuth = (page) => {
  return page.addInitScript(() => {
    localStorage.setItem('auth', JSON.stringify({
      token: 'admin-token',
      user: {
        id: 10,
        name: 'Admin User',
        email: 'admin@example.com',
        role: 'admin'
      }
    }))
  })
}

const mockAdminCatalogEndpoints = async (page) => {
  await page.route('http://localhost/api/car-brands', async (route) => {
    await route.fulfill({
      status: 200,
      contentType: 'application/json',
      body: JSON.stringify({
        data: [
          { id: 1, name: 'Audi' },
          { id: 2, name: 'BMW' }
        ]
      })
    })
  })

  await page.route('http://localhost/api/car-models', async (route) => {
    await route.fulfill({
      status: 200,
      contentType: 'application/json',
      body: JSON.stringify({
        data: [
          {
            id: 11,
            brand_id: 1,
            name: 'A4',
            gen: 'B9',
            mod: 'Sedan',
            startyear: 2016,
            endyear: 2020,
            brand: { id: 1, name: 'Audi' }
          }
        ]
      })
    })
  })
}

test.describe('Admin brands/models pages', () => {
  test('admin can view brands list page', async ({ page }) => {
    await setAdminAuth(page)
    await mockAdminCatalogEndpoints(page)

    await page.goto('/admin/brands')

    await expect(page.getByRole('heading', { name: 'Gyártók kezelése' })).toBeVisible()
    await expect(page.getByRole('button', { name: 'Új gyártó' })).toBeVisible()
    await expect(page.getByText('Audi')).toBeVisible()
  })

  test('admin can view models list page', async ({ page }) => {
    await setAdminAuth(page)
    await mockAdminCatalogEndpoints(page)

    await page.goto('/admin/models')

    await expect(page.getByRole('heading', { name: 'Modellek kezelése' })).toBeVisible()
    await expect(page.getByText('Szűrés márkára:')).toBeVisible()
    await expect(page.getByText('A4')).toBeVisible()
    await expect(page.getByRole('cell', { name: 'Audi' })).toBeVisible()
  })
})
