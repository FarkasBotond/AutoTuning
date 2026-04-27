import { test, expect } from '@playwright/test'

const mockBrands = [
    {
        id: 1,
        name: 'Audi'
    },
    {
        id: 3,
        name: 'BMW'
    }
]

const mockModels = [
    {
        id: 11,
        brand_id: 1,
        model: 'A4 B9',
        name: 'A4 B9',
        start_year: 2016,
        end_year: 2020,
        brand: {
            id: 1,
            name: 'Audi'
        }
    },
    {
        id: 31,
        brand_id: 3,
        model: '3 Series G20',
        name: '3 Series G20',
        start_year: 2018,
        end_year: null,
        brand: {
            id: 3,
            name: 'BMW'
        }
    },
    {
        id: 32,
        brand_id: 3,
        model: 'M4 G82',
        name: 'M4 G82',
        start_year: 2020,
        end_year: null,
        brand: {
            id: 3,
            name: 'BMW'
        }
    }
]

const mockProducts = [
    {
        id: 501,
        name: 'Sport féktárcsa szett',
        tuning_company: 'Brembo',
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
]

const mockSideMenuEndpoints = async (page) => {
    await page.route('**/api/**', async (route) => {
        const url = new URL(route.request().url())
        const pathname = url.pathname

        if (pathname === '/api/car-brands') {
            await route.fulfill({
                status: 200,
                contentType: 'application/json',
                body: JSON.stringify({
                    data: mockBrands
                })
            })
            return
        }

        if (pathname === '/api/car-models') {
            await route.fulfill({
                status: 200,
                contentType: 'application/json',
                body: JSON.stringify({
                    data: mockModels
                })
            })
            return
        }

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

        if (pathname.startsWith('/api/tuning-products/')) {
            await route.fulfill({
                status: 200,
                contentType: 'application/json',
                body: JSON.stringify({
                    data: mockProducts[0]
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

test.describe('SideMenu szűrők', () => {
    test.beforeEach(async ({ page }) => {
        await mockSideMenuEndpoints(page)
    })

    test('kategóriára kattintva URL query változik', async ({ page }) => {
        await page.goto('/')

        await page.getByRole('button', { name: 'Fékek' }).click()

        await expect(page).toHaveURL(/service_category_id=4/)
    })

    test('termék részletes oldalról kategóriára kattintva visszavisz a főoldalra', async ({ page }) => {
        await page.goto('/DetailedPage?id=501')

        await page.getByRole('button', { name: 'Fékek' }).click()

        await expect(page).toHaveURL(/\/\?service_category_id=4/)
    })

    test('modellre kattintva car_model_id query kerül az URL-be', async ({ page }) => {
        await page.goto('/')

        await page.getByRole('button', { name: 'Gyártók' }).click()
        await page.getByRole('button', { name: 'BMW' }).click()
        await page.getByRole('button', { name: '3 Series G20' }).click()

        await expect(page).toHaveURL(/brand_id=3/)
        await expect(page).toHaveURL(/car_model_id=31/)
    })
})