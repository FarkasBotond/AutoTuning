import { test, expect } from '@playwright/test'

const mockProducts = [
    {
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
    },
    {
        id: 502,
        name: 'Carbon diffúzor',
        tuning_company: 'Maxton Design',
        image: '',
        badge: 'Akció',
        old_price: 119990,
        price: 99990,
        is_in_stock: true,
        car_model_id: 11,
        service_category_id: 6,
        car_model: {
            id: 11,
            brand_id: 1,
            model: 'A4 B9',
            brand: {
                id: 1,
                name: 'Audi'
            }
        },
        service_category: {
            id: 6,
            name: 'Külső kiegészítők'
        }
    }
]

const mockSearchEndpoints = async (page) => {
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
                        { id: 11, brand_id: 1, model: 'A4 B9' },
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

test.describe('Keresés', () => {
    test.beforeEach(async ({ page }) => {
        await mockSearchEndpoints(page)
    })

    test('keresőmezőből átvisz a keresési oldalra', async ({ page }) => {
        await page.goto('/')

        await page.getByRole('textbox').first().fill('fek')
        await page.keyboard.press('Enter')

        await expect(page).toHaveURL(/\/SearchPage.*q=fek/)
        await expect(page.getByRole('heading', { name: 'Keresés' })).toBeVisible()
    })

    test('ékezet nélkül is megtalálja az ékezetes terméket', async ({ page }) => {
        await page.goto('/SearchPage?q=fek')

        await expect(page.getByRole('heading', { name: 'Keresés' })).toBeVisible()
        await expect(page.getByText('Sport féktárcsa szett')).toBeVisible()
        await expect(page.getByText('Carbon diffúzor')).not.toBeVisible()
    })

    test('keresés kategórianévre is működik', async ({ page }) => {
        await page.goto('/SearchPage?q=kulso')

        await expect(page.getByText('Carbon diffúzor')).toBeVisible()
        await expect(page.getByText('Sport féktárcsa szett')).not.toBeVisible()
    })

    test('nincs találat üzenetet jelenít meg, ha nincs egyezés', async ({ page }) => {
        await page.goto('/SearchPage?q=nincsilyen')

        await expect(page.getByText('Nincs találat a keresésre.')).toBeVisible()
    })
})