import { test, expect } from '@playwright/test'

const createMockProduct = (id, overrides = {}) => {
    return {
        id,
        name: `Teszt termék ${id}`,
        tuning_company: 'EBC Brakes',
        image: '',
        badge: 'Új',
        old_price: null,
        price: 10000 + id,
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
        },
        ...overrides
    }
}

const mockProducts = [
    createMockProduct(1),
    createMockProduct(2),
    createMockProduct(3),
    createMockProduct(4),
    createMockProduct(5),
    createMockProduct(6),
    createMockProduct(7),
    createMockProduct(8)
]

const mockHomePageEndpoints = async (page) => {
    await page.route('**/api/**', async (route) => {
        const url = new URL(route.request().url())
        const pathname = url.pathname

        if (pathname === '/api/tuning-products') {
            await route.fulfill({
                status: 200,
                contentType: 'application/json',
                body: JSON.stringify({
                    data: mockProducts
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

test.describe('Főoldal', () => {
    test.beforeEach(async ({ page }) => {
        await mockHomePageEndpoints(page)
        await page.addInitScript(() => {
            localStorage.removeItem('race_district_cart')
        })
    })

    test('főoldalon megjelennek az API mockból érkező termékek', async ({ page }) => {
        await page.goto('/')

        await expect(page.getByText('Teszt termék 1')).toBeVisible()
        await expect(page.getByText('Teszt termék 2')).toBeVisible()
        await expect(page.getByText('Teszt termék 6')).toBeVisible()
    })

    test('lapozás működik, ha több mint hat termék van', async ({ page }) => {
        await page.goto('/')

        await expect(page.getByText('Teszt termék 1')).toBeVisible()
        await expect(page.getByText('Teszt termék 7')).not.toBeVisible()

        await page.getByRole('button', { name: 'Következő' }).click()

        await expect(page.getByText('Teszt termék 7')).toBeVisible()
        await expect(page.getByText('Teszt termék 1')).not.toBeVisible()

        await page.getByRole('button', { name: 'Előző' }).click()

        await expect(page.getByText('Teszt termék 1')).toBeVisible()
    })

    test('tuning céges szekció megjelenik a főoldalon', async ({ page }) => {
        await page.goto('/')

        await expect(page.getByText(/Tuningcégek|Tuning cégek/)).toBeVisible()
        await expect(page.getByRole('link', { name: 'Mutass mindent' })).toBeVisible()
    })

    test('termék kosárba rakása után toast jelenik meg', async ({ page }) => {
        await page.goto('/')

        const productCard = page.locator('article').filter({
            hasText: 'Teszt termék 1'
        })

        await productCard.getByRole('button', { name: /kosár|hozzá/i }).click()

        await expect(page.getByText('Teszt termék 1 hozzáadva a kosárhoz')).toBeVisible()
    })
})