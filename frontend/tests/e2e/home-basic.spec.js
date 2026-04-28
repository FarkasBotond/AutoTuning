import { test, expect } from '@playwright/test'

const mockHomeEndpoints = async (page) => {
    await page.route('**/api/**', async (route) => {
        const url = new URL(route.request().url())
        const pathname = url.pathname

        if (pathname === '/api/tuning-products') {
            await route.fulfill({
                status: 200,
                contentType: 'application/json',
                body: JSON.stringify({
                    data: []
                })
            })
            return
        }

        if (pathname === '/api/car-brands') {
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
            return
        }

        if (pathname === '/api/car-models') {
            await route.fulfill({
                status: 200,
                contentType: 'application/json',
                body: JSON.stringify({
                    data: [
                        { id: 1, brand_id: 1, model: 'A4 B9' },
                        { id: 2, brand_id: 2, model: '3 Series G20' }
                    ]
                })
            })
            return
        }

        if (pathname === '/api/tuning-companies') {
            await route.fulfill({
                status: 200,
                contentType: 'application/json',
                body: JSON.stringify({
                    data: []
                })
            })
            return
        }

        await route.fulfill({
            status: 200,
            contentType: 'application/json',
            body: JSON.stringify({
                data: []
            })
        })
    })
}

test.describe('Főoldal alap működés', () => {
    test.beforeEach(async ({ page }) => {
        await mockHomeEndpoints(page)
    })

    test('a főoldal betölt és megjeleníti az alap elemeket', async ({ page }) => {
        await page.goto('/')

        await expect(page.getByRole('button', { name: 'Kategóriák' })).toBeVisible()
        await expect(page.getByRole('button', { name: 'Gyártók' })).toBeVisible()
        await expect(page.getByPlaceholder('Termék, márka vagy kulcsszó...')).toBeVisible()
        await expect(page.getByRole('link', { name: 'Mutass mindent' })).toBeVisible()
    })

    test('a Mutass mindent link a tuning cégek oldalra mutat', async ({ page }) => {
        await page.goto('/')

        const companiesLink = page.getByRole('link', { name: 'Mutass mindent' })

        await expect(companiesLink).toBeVisible()
        await expect(companiesLink).toHaveAttribute('href', '/CompaniesPage')
    })

    test('a Gyártók fülre kattintva megjelennek a gyártók', async ({ page }) => {
        await page.goto('/')

        await page.getByRole('button', { name: 'Gyártók' }).click()

        await expect(page.getByRole('button', { name: 'Audi' })).toBeVisible()
        await expect(page.getByRole('button', { name: 'BMW' })).toBeVisible()
    })
})