import { test, expect } from '@playwright/test'

const mockProduct = {
    id: 501,
    name: 'Sport féktárcsa szett',
    tuning_company: 'EBC Brakes',
    image: '',
    badge: 'Új',
    old_price: null,
    price: 89990,
    is_in_stock: true,
    car_model_id: 31,
    service_category_id: 4,
    car_model: {
        id: 31,
        brand_id: 3,
        model: '3 Series G20',
        brand: {
            id: 3,
            name: 'BMW'
        }
    },
    service_category: {
        id: 4,
        name: 'Fékek'
    }
}

const mockProductEndpoints = async (page) => {
    await page.route('**/api/**', async (route) => {
        const url = new URL(route.request().url())
        const pathname = url.pathname

        if (pathname === '/api/tuning-products') {
            await route.fulfill({
                status: 200,
                contentType: 'application/json',
                body: JSON.stringify({
                    data: [mockProduct]
                })
            })
            return
        }

        if (pathname === '/api/tuning-products/501') {
            await route.fulfill({
                status: 200,
                contentType: 'application/json',
                body: JSON.stringify({
                    data: mockProduct
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
                        { id: 3, name: 'BMW' }
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
                        { id: 31, brand_id: 3, model: '3 Series G20' }
                    ]
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

test.describe('Termék részletes oldal', () => {
    test.beforeEach(async ({ page }) => {
        await mockProductEndpoints(page)

        await page.addInitScript(() => {
            localStorage.removeItem('race_district_cart')
        })
    })

    test('termékkártyára kattintva átvisz a részletes oldalra', async ({ page }) => {
        await page.goto('/')

        await page.getByText('Sport féktárcsa szett').first().click()

        await expect(page).toHaveURL(/DetailedPage.*501|DetailedPage.*id=501/)
        await expect(page.getByText('Sport féktárcsa szett')).toBeVisible()
    })

    test('részletes oldalon megjelennek a termék adatai', async ({ page }) => {
        await page.goto('/DetailedPage?id=501')

        await expect(page.getByText('Sport féktárcsa szett')).toBeVisible()
        await expect(page.getByText('EBC Brakes')).toBeVisible()
        await expect(page.getByText('BMW')).toBeVisible()
        await expect(page.getByText('3 Series G20')).toBeVisible()
        await expect(page.getByText('Fékek')).toBeVisible()
        await expect(page.getByText(/89\s990 Ft/)).toBeVisible()
    })

    test('termék kosárba rakható a részletes oldalról', async ({ page }) => {
        await page.goto('/DetailedPage?id=501')

        await page.getByRole('button', { name: /kosár/i }).first().click()

        await expect.poll(async () => {
            const cart = await page.evaluate(() => localStorage.getItem('race_district_cart'))
            return cart ?? ''
        }).toContain('Sport féktárcsa szett')

        await expect.poll(async () => {
            const cart = await page.evaluate(() => localStorage.getItem('race_district_cart'))
            return cart ?? ''
        }).toContain('89990')
    })

    test('kosárba rakás után a kosár oldalon is látszik a termék', async ({ page }) => {
        await page.addInitScript(() => {
            localStorage.setItem('auth', JSON.stringify({
                token: 'product-detail-test-token',
                user: {
                    id: 12,
                    name: 'Product Detail Test User',
                    email: 'detail@example.com',
                    role: 'user'
                }
            }))
        })

        await page.goto('/DetailedPage?id=501')

        await page.getByRole('button', { name: /kosár/i }).first().click()
        await page.goto('/cart')

        await expect(page.getByText('Sport féktárcsa szett')).toBeVisible()
        await expect(page.getByText('EBC Brakes')).toBeVisible()
        await expect(page.getByText(/89\s990 Ft/)).toBeVisible()
    })
})